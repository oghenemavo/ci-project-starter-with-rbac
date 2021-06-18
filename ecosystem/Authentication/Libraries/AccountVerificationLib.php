<?php

namespace Ecosystem\Authentication\Libraries;

use Config\{Database, Services};
use CodeIgniter\I18n\Time;
use Ecosystem\Authentication\Models\{User, AccountVerification};

class AccountVerificationLib 
{
    /**
     * Verify a token
     *
     * @param string $token
     * @return void
     */
    public function verify_token(string $token) 
    {
        $verification = new AccountVerification();
        $verification->where('account_token', $token);
        return $verification->where('expires_at >=', Time::now()->toDateTimeString())->first();
    }

    /**
     * Verify a user
     *
     * @param array $user_data
     * @return void
     */
    public function verify_user(array $user_data) 
    {
        helper('encryption'); // custom encryption helper function
        
        $activation_token = hash_data($user_data['code']); // goes to the database 

        $result = [];
        
        $db = Database::connect(); // create a database connection
        $db->transStart(); // start transaction automatically

        // User Table
        $user = new User();

        $account = $user->where('activation_token', $activation_token)->where('is_active', '0')->first();
        if ($account) {
            $user->set('activation_token', null, true);
            $user->set('is_active', '1');
            $user->where('id', $account->id);

            try {
                $user->update();
            } catch (\ReflectionException $e) {
            }
    
            // Verification Table
            $verification = new AccountVerification();
            try {
                $verification->where('user_id', $account->id)->where('account_token', $user_data['account_token']);
                $verification->delete();
            } catch (\ReflectionException $e) {
            }
            
            $db->transComplete(); // complete transaction
    
            if ($db->transStatus() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                $result['error'] = 'Unable to Verify User';
            } else {
                $result['success'] = true;
            }
        } else {
            $result['error'] = 'Invalid Token';
        }

        return $result;
    }

    /**
     * Resend a token verification
     *
     * @param string $token
     * @return void
     */
    public function resend_verification(string $token) 
    {
        helper(['encryption', 'sender']); // custom encryption helper function
        $result = [];

        // Verification Table
        $verification = new AccountVerification();
        $account = $verification->where('account_token', $token)->first();

        if (!$account) {
            $result['error'] = false;
        } else {
            $user = new User();
            $user_account = $user->where('id', $account->user_id)->first();

            if ($user_account) {
                $encrypted_token = encrypt_data(6);  // goes to the user email address
                $hashed_token = hash_data($encrypted_token); // goes to the database 

                try {
                    $user->update($user_account->id, ['activation_token' => $hashed_token]);
                } catch (\ReflectionException $e) {
                }

                // Update into verification table
                try {
                    $result['verify_token'] = md5($hashed_token);
                    $verification->set('account_token', $result['verify_token']); // send to db and output
                    $verification->set('expires_at', Time::now()->addMinutes(60)->toDateTimeString());
                    $verification->where('user_id', $user_account->id);
                    $verification->update();
                } catch (\ReflectionException $e) {
                }

                // send email
                $data = [
                    'name' => $user_account->last_name, 
                    'token' => $encrypted_token,
                ];

                $address = [
                    'to' => $user_account->user_email,
                ];

                send_mail('authentication', $address, $data);
                $result['success'] = true;
            }
        }
        return $result;
    }

}