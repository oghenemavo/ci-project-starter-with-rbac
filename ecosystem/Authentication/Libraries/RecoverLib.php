<?php

namespace Ecosystem\Authentication\Libraries;

use Config\{Database, Services};
use CodeIgniter\I18n\Time;
use Ecosystem\Authentication\Entities\User as EntitiesUser;
use Ecosystem\Authentication\Models\{User, AccountVerification};

class RecoverLib
{

    /**
     * Reset a forgotten user password, send a token verification
     * @param string $email         captured form email
     * @return array
     * @throws \ReflectionException
     */
    public function start_reset(string $email):array
    {
        helper(['encryption', 'sender']); // custom encryption helper function
        $userlib = service('userlib');

        $encrypted_token = encrypt_data();  // goes to the user email address
        $hashed_token = hash_data($encrypted_token); // goes to the database 

        $result = [];

        $person = $userlib->get_by_email($email);
        if (!$person) {
            $result['error'] = 'no user found';
        } else {
            $db = Database::connect(); // create a database connection
            $db->transStart(); // start transaction automatically

            $expiry = Time::now()->addMinutes(60)->toDateTimeString(); // 60 min expires
            
            // User Table
            $user = new User();
            $user->set('password_reset_token', $hashed_token);
            $user->set('password_reset_expires_at', $expiry);
            $user->where('user_email', $person->user_email);
    
            try {
                $user->update();
            } catch (\ReflectionException $e) {
            }

            $db->transComplete(); // complete transaction
    
            if ($db->transStatus() === false) {
                // generate an error... or use the log_message() function to log your error
                $result['error'] = 'Unable to start up recover process';
            } else {
                // send email
                $link = base_url('/reset/password/' . $encrypted_token);

                $data = [
                    'name' => $person->last_name, 
                    'link' => $link
                ];

                $address = [
                    'to' => $person->user_email,
                    'user_id' => $person->id,
                ];

                send_mail('reset_password', $address, $data);
                $result['success'] = true;
            }
        }
        return $result;
    }

    /**
     * Find a user by hashed token
     * @param string $token
     * @return array|object|null
     */
    public function get_user_by_token($token) {
        helper('encryption');

        $hashed_token = hash_data($token);

        $user = new User();
        $user->where('password_reset_token', $hashed_token);
        return $user->where('password_reset_expires_at >', date('Y-m-d H:i:s'))->first();
    }

    /**
     * Reset a user password 
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     */
    public function reset_password($data) {
        $result = [];

        $person = $this->get_user_by_token($data['token']);
        if ($person) {
            $db = Database::connect(); // create a database connection
            $db->transStart(); // start transaction automatically

            unset($data['token']);
            $data['id'] = $person->id;

            $user = new User();

            $user_entity = new EntitiesUser();
            $user_entity->fill($data);

            $user->set('password_reset_token', null);
            $user->set('password_reset_expires_at', null);
            $user->where('id', $person->id);

            // Update into Users table
            try {
                $user->save($user_entity);
            } catch (\ReflectionException $e) {
            }

            $db->transComplete(); // complete transaction

            if ($db->transStatus() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                $result['error'] = 'Unable to reset password';
            } else {
                $result['success'] = true;
            }
        } else {
            $result['error'] = 'Unable to reset password, token error';
        }
        return $result;
    }

}
