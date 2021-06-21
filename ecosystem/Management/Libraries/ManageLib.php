<?php

namespace Ecosystem\Management\Libraries;

use Config\Database;
use Ecosystem\Authentication\Entities\User as EntitiesUser;
use Ecosystem\Authentication\Models\{User, UserProfile, UserRole};

class ManageLib
{
    /**
     * Get all User record
     *
     * @return void
     */
    public function get_users()
    {
        $user = new User();
        return $user->orderBy('id', 'DESC')->findAll();
    }

    /**
     * Create or Update a user account
     *
     * @param array $data
     * @return void
     */
    public function set_user(array $data)
    {
        $result = [];

        $db = Database::connect();
        $db->transStart(); // start transaction

        // User Table
        $user = new User();

        $user_entity = new EntitiesUser();
        $user_entity->fill($data);
        
        // Set into user table
        try {
            $user->save($user_entity);
            if (!isset($data['id'])) {
                $data['user_id'] = $user->insertID(); // last user insert id
            }
        } catch (\ReflectionException $e) {
        }

        //  Profile Table
        $profile = new UserProfile();
        
        try {
            if (!isset($data['id'])) {
                $profile->insert($data);
            } else {
                $profile->update(['user_id' => $data['id']], $data);
            }
        } catch (\ReflectionException $e) {
        }

        // User Role Table
        $user_role = new UserRole();

        if (!isset($data['id'])) {
            $user_role->insert($data);
        } else {
            if (!empty($data['role_id'])) {
                // Update into user role table
                try {
                    $user_role->update(['user_id' => $data['id']], $data);
                } catch (\ReflectionException $e) {
                }
            }
        }

        // end transaction
        $db->transComplete();
        if ($db->transStatus() === false) {
            $result['error'] = 'Unable to perform this request';
        } else {
            $result['success'] = true;
        }
        return $result;
    }

    /**
     * Delete a user 
     *
     * @param integer $id
     * @return void
     */
    public function delete_user(int $id)
    {
        $result = [];

        $db = Database::connect();
        $db->transStart(); // start transaction

        // User Table
        $user = new User();
        
        // Set into user table
        try {
            $user->delete($id);
        } catch (\ReflectionException $e) {
        }

        // end transaction
        $db->transComplete();
        if ($db->transStatus() === false) {
            $result['error'] = 'Unable to perform this request';
        } else {
            $result['success'] = true;
        }
        return $result;
    }

}