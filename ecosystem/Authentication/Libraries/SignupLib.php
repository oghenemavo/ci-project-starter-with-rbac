<?php

namespace Ecosystem\Authentication\Libraries;

use Config\{Database, Services};
use CodeIgniter\I18n\Time;
use Ecosystem\Authentication\Entities\User as EntitiesUser;
use Ecosystem\Authentication\Models\{User, UserProfile, UserRole, AccountVerification};

class SignupLib 
{

    /**
     * Create a User account in 4 tables
     *
     * @param array $user_data
     * @param string $role_id
     * @return void
     */
    public function create_user(array $user_data, $role_id = '1') 
    {
        helper(['encryption', 'sender']); // custom encryption helper function

        $encrypted_token = encrypt_data(6);  // goes to the user email address
        $hashed_token = hash_data($encrypted_token); // goes to the database 

        $result = [];
        $user_data['activation_token'] = $hashed_token;
        $user_data['role_id'] = $role_id;
        
        $db = Database::connect(); // create a database connection

        $db->transStart(); // start transaction automatically

        // User Table
        $user = new User();

        $user_entity = new EntitiesUser();
        $user_entity->fill($user_data);

        // Insert into user table
        try {
            $user->save($user_entity);
            $user_data['user_id'] = $user->insertID(); // last user insert id
        } catch (\ReflectionException $e) {
        }

        // Profile Table
        $profile = new UserProfile();

        // Insert into Profile table
        try {
            $profile->insert($user_data);
        } catch (\ReflectionException $e) {
        }

        // User Role Table
        $user_role = new UserRole();

        // Insert into user role table
        try {
            $user_role->insert($user_data);
        } catch (\ReflectionException $e) {
        }

        // Verification Table
        $verification = new AccountVerification();

        // Insert into user table
        try {
            $user_data['account_token'] = $result['verify'] = md5($hashed_token); // send to db and output
            $user_data['expires_at'] = Time::now()->addMinutes(60)->toDateTimeString(); // expires at 60 mins
            $verification->insert($user_data);
        } catch (\ReflectionException $e) {
        }

        $db->transComplete(); // complete transaction

        if ($db->transStatus() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            $result['error'] = 'Unable to sign up user';
        } else {
            // send email
            $address = [
                'to' => $user_data['user_email'], 
            ];
            
            $data = [
                'name' => $user_data['last_name'], 
                'token' => $encrypted_token,
            ];

            send_mail('sign_up', $address, $data);
            $result['success'] = true;
        }
        return $result;
    }

}