<?php

namespace Ecosystem\Management\Libraries;

use Config\{Database, Services};
use Ecosystem\Authentication\Models\{Permission, PermissionGroup, RolePermission};

class PermissionsLib
{
    /**
     * Add a group into the PermissionGroup table and set the permissions into the Permission table
     *
     * @param int $data
     * @return void
     */
    public function add_group(array $data) 
    {
        $result = [];

        $db = Database::connect();
        $db->transStart(); // start transaction

        $group = new PermissionGroup(); // permission-group table
        try {
            $group->save($data); // add/edit
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

    public function add_permission(array $data) 
    {
        $result = [];

        $db = Database::connect();
        $db->transStart(); // start transaction

        $permission = new Permission(); // permission table
        try {
            $permission->save($data); // add/edit
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

    /**
     * Get a Permission group by its id
     *
     * @param int $id
     * @return void
     */
    public function find_permission_group(int $id) 
    {
        $group = new PermissionGroup();
        return $group->find($id);
    }

    public function find_permission(int $id) 
    {
        $permission = new Permission();
        return $permission->find($id);
    }

    /**
     * Get a Role Permission
     *
     * @param integer $id
     * @return void
     */
    public function find_role_permission(int $id) 
    {
        $role_perm = new RolePermission();
        return $role_perm->find($id);
    }

    public function get_permissions_by_group(int $id)
    {
        $permission = new Permission();
        return $permission->fetchPermissionByGroup($id);
    }

    /**
     * Get all permission group
     *
     * @return void
     */
    public function get_permission_groups():array {
        $group = new PermissionGroup(); // permission group table
        return $group->findAll();
    }
    
    /**
     * Fetch Role Permissions by conditions
     *
     * @param integer $role_id
     * @return array
     */
    public function get_role_permissions(int $role_id = 0):array {
        $role_perm = new RolePermission();
        if ($role_id) {
            return $role_perm->where('role_id', $role_id)->get()->getResultObject();
        }
        return $role_perm->findAll();
    }

    /**
     * Get all Permissions
     *
     * @return array
     */
    public function get_permissions():array {
        $permission = new Permission();
        return $permission->findAll();
    }

    /**
     * Set Permissions to a role
     *
     * @param array $data
     * @return void
     */
    public function set_role_permission(array $data) {
        $result = $update_batch_data = $insert_batch_data = [];
        $role_id = $data['role_id'];

        // new
        $can_create = $data['can_create'] ?? [];
        $can_read = $data['can_read'] ?? [];
        $can_update = $data['can_update'] ?? [];
        $can_delete = $data['can_delete'] ?? [];
        $is_active = $data['is_active'] ?? [];

        $db = Database::connect();
        $db->transStart(); // start transaction

        $role_permissions = $this->get_role_permissions($role_id); // permissions associated with role id

        $general_permisssions = $this->get_permissions(); // general permissions

        $rp = new RolePermission();

        if (empty($role_permissions)) { // if role doesn't have stored db role permissions
            foreach ($general_permisssions as $gen_perm) {
                $insert_batch_data[] = [
                    'role_id' => $role_id,
                    'permission_id' => $gen_perm->id,
                    'can_create' => in_array($gen_perm->id, $can_create) ? '1' : '0',
                    'can_read' => in_array($gen_perm->id, $can_read) ? '1' : '0',
                    'can_update' => in_array($gen_perm->id, $can_update) ? '1' : '0',
                    'can_delete' => in_array($gen_perm->id, $can_delete) ? '1' : '0',
                    'is_active' => in_array($gen_perm->id, $is_active) ? '1' : '0',
                ];
            }

            try {
                $rp->insertBatch($insert_batch_data, 'id');
            } catch (\ReflectionException $e) {
            }
        } else { // if role haves stored db permissions
            foreach ($role_permissions as $permission) {
                $update_batch_data[] = [
                    'role_id' => $role_id,
                    'permission_id' => $permission->permission_id,
                    'can_create' => in_array($permission->permission_id, $can_create) ? '1' : '0',
                    'can_read' => in_array($permission->permission_id, $can_read) ? '1' : '0',
                    'can_update' => in_array($permission->permission_id, $can_update) ? '1' : '0',
                    'can_delete' => in_array($permission->permission_id, $can_delete) ? '1' : '0',
                    'is_active' => in_array($permission->permission_id, $is_active) ? '1' : '0',
                ];
            }
            if (!empty($update_batch_data)) {
                try {
                    $rp->updateBatch($update_batch_data, 'permission_id');
                } catch (\ReflectionException $e) {
                }
            }
            
            if (count($general_permisssions) > count($role_permissions)) {

                foreach($role_permissions as $permission) {
                    $role_permissions_id[] = $permission->permission_id;
                }

                foreach($general_permisssions as $gen_perm) {
                    $gen_role_permissions_id[] = $gen_perm->id;
                }

                $perm_id_diff = array_diff($gen_role_permissions_id, $role_permissions_id);

                foreach ($perm_id_diff as $perm_id) {
                    $insert_batch_data[] = [
                        'role_id' => $role_id,
                        'permission_id' => $perm_id,
                        'can_create' => in_array($perm_id, $can_create) ? '1' : '0',
                        'can_read' => in_array($perm_id, $can_read) ? '1' : '0',
                        'can_update' => in_array($perm_id, $can_update) ? '1' : '0',
                        'can_delete' => in_array($perm_id, $can_delete) ? '1' : '0',
                        'is_active' => in_array($perm_id, $is_active) ? '1' : '0',
                    ];
                }
                
                if (!empty($insert_batch_data)) {
                    try {
                        $rp->insertBatch($insert_batch_data);
                    } catch (\ReflectionException $e) {
                    }
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

}