<?php

namespace Ecosystem\Authentication\Libraries;

use Config\Database;
use Ecosystem\Authentication\Models\{UserRole, Role, RolePermission, PermissionGroup, Permission};

class RoleLib
{
    /**
     * Get All roles
     *
     * @return void
     */
    public function get_roles() 
    {
        $role = new Role(); // role table
        return $role->findAll();
    }

    
    public function find_role(int $role_id) {
        $role = new Role();
        return $role->find($role_id);
    }

    /**
     * Get all Permissions
     *
     * @return void
     */
    public function get_permissions()
    {
        return $this->permissions;
    }

    /**
     * Check if (a) permission(s) is set
     *
     * @param mixed $permission         
     * @return boolean
     */
    public function has_permission(mixed $permission):bool
    {
        if (is_string($permission)) {
            return isset($this->permissions[$permission]);
        } elseif (is_array($permission)) {
            foreach ($permission as $value) {
                if(!isset($this->permissions[$value])) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }


    /**
     * Add or Edit a role
     *
     * @param array $data
     * @return void
     */
    public function add_role($data) 
    {
        $result = [];
        
        $db = Database::connect();

        $db->transStart(); // start transaction

        $roles = new Role();

        try {
            $roles->save($data);
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
