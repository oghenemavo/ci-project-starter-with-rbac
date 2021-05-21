<?php

namespace Ecosystem\Authentication\Libraries;

use Config\Database;
use Ecosystem\Authentication\Models\{UserRole, RolePermission, Permission};

class Rbac
{
    public $roles = [];
    public $permissions = [];

    /**
     * Get a User Role details
     *
     * @param integer $user_id              user id
     * @return object
     */
    public function find_user_role(int $user_id)
    {
        $user_role = new UserRole();
        return $user_role->fetchRoleInfo($user_id);
    }

    /**
     * Get Permissions associated with a role
     *
     * @param integer $role_id                  role id
     * @return array
     */
    public function get_role_permission_info(int $role_id): array
    {
        $permission = new Permission();
        $role_permission = new RolePermission();
        $record = $role_permission->fetchRolePermissionInfo($role_id);
        if ($record) {
            foreach ($record as $data) {
                if ($data->is_active) { // role permission is active
                    $permission_record = $permission->getPermissionById($data->permission_id);
                    if (!$permission_record) {
                        return false;
                    } else {
                        // check if permission is enable else role permission is disabled
                        $create = $permission_record->enable_create == '0' && $data->can_create == '1' ? '0' : $data->can_create;
                        $read = $permission_record->enable_read == '0' && $data->can_read == '1' ? '0' : $data->can_read;
                        $update = $permission_record->enable_update == '0' && $data->can_update == '1' ? '0' : $data->can_update;
                        $delete = $permission_record->enable_delete == '0' && $data->can_delete == '1' ? '0' : $data->can_delete;

                        // assign role permission attribute to permission
                        $this->permissions[$data->permission_slug] = (object) [
                            'can_create' => $create,
                            'can_read' => $read,
                            'can_update' => $update,
                            'can_delete' => $delete,
                        ];
                    }
                }
            }
            return $this->permissions;
        }
        return false;
    }

    /**
     * Set role to have requiste permissions
     *
     * @param integer $user_id
     * @return array
     */
    public function set_role(int $user_id): array
    {
        $record = $this->find_user_role($user_id);
        if ($record->is_active) { // check user role is active
            $this->roles[$record->role] = $this->get_role_permission_info($record->role_id);
            return $this->roles;
        }
        return false;
    }

    /**
     * Set a user role to have requiste permissions
     *
     * @param integer $user_id
     * @return mixed
     */
    public function set_user_role(int $user_id): mixed
    {
        return $this->set_role($user_id);
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
     * Check for the set privileges of a permission
     *
     * @param mixed $permission         
     * @return boolean
     */
    public function has_permission(mixed $permission, string $privilege):bool
    {
        $user = service('userlib')->get_user();
        if ($user) {
            if ($this->set_role($user->id)) {
                if (is_string($permission)) {
                    return $this->has_privilege($permission, $privilege);
                } elseif (is_array($permission)) {
                    foreach ($permission as $value) {
                        if(!$this->has_privilege($value, $privilege)) {
                            return false;
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if user privilege is sufficient to access a page
     *
     * @param mixed $privilege
     * @return boolean
     */
    public function has_permission_or_exception(mixed $permission, string $privilege) 
    {
        if (! $this->has_permission($permission, $privilege)) {
            throw new \CodeIgniter\Router\Exceptions\RedirectException(403);
        }
        return true;
    }

    /**
     * check if permission & privilege exists
     *
     * @param string $permission
     * @param string $privilege
     * @return boolean
     */
    protected function has_privilege(string $permission, string $privilege)
    {
        return isset($this->permissions[$permission]->$privilege) && $this->permissions[$permission]->$privilege == '1';
    }

}
