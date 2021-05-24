<?php

namespace Ecosystem\Management\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;
use Ecosystem\Management\Libraries\PermissionsLib;

class RolePermission extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Management\\Views\\settings\\';

	/**
	 * Role Permissions page
	 *
	 * @param integer $role_id
	 * @return void
	 */
	public function index($role_id)
	{
		// $this->userLib->has_privilege_or_exception('edit_role'); // check privilege or 404

		$role_info = $this->roleLib->find_role($role_id);
		if ($role_info) {
			$data['page_title'] = 'Edit Role Permission';
			$data['validation'] = $this->validation;

			$data['role_info'] = $role_info;
			$data['role_permissions'] = $role_permissions = service('permissionsLib')->get_role_permissions($role_id);
			$data['permission_group'] = service('permissionsLib')->get_permission_groups(); // fetch permission groups
            $data['permissions'] = $permissions = service('permissionsLib')->get_permissions(); // get permissions

			$privileges = function() use ($role_permissions) {
				$privileges = [];
				if (!empty($role_permissions)) {
					foreach ($role_permissions as $rp) {
						$privileges[$rp->permission_id]['can_create'] = $rp->can_create;
						$privileges[$rp->permission_id]['can_read'] = $rp->can_read;
						$privileges[$rp->permission_id]['can_update'] = $rp->can_update;
						$privileges[$rp->permission_id]['can_delete'] = $rp->can_delete;
			
						$privileges[$rp->permission_id]['is_active'] = $rp->is_active;
					}
				}
				return $privileges;
			};

			$user_rp = function() use ($permissions, $privileges) {
				$data['is_checked'] = [];
				$data['is_disabled'] = [];
				$data['is_active'] = [];
				
				$set_privileges = $privileges();
				if (!empty($set_privileges)) {
					foreach($permissions as $permission){
						if (isset($set_privileges[$permission->id])) {
							$can_create = $permission->enable_create == '0' && $set_privileges[$permission->id]['can_create'] == '1' ? '0' : $set_privileges[$permission->id]['can_create'];
							$can_read = $permission->enable_read == '0' && $set_privileges[$permission->id]['can_read'] == '1' ? '0' : $set_privileges[$permission->id]['can_read'];
							$can_update = $permission->enable_update == '0' && $set_privileges[$permission->id]['can_update'] == '1' ? '0' : $set_privileges[$permission->id]['can_update'];
							$can_delete = $permission->enable_delete == '0' && $set_privileges[$permission->id]['can_delete'] == '1' ? '0' : $set_privileges[$permission->id]['can_delete'];
						} else {
							$can_create = 0;
							$can_read = 0;
							$can_update = 0;
							$can_delete = 0;
						}
		
						$is_checked[$permission->id]['_create'] = $can_create == '1' ? 'checked' : '';
						$is_checked[$permission->id]['_read'] = $can_read == '1' ? 'checked' : '';
						$is_checked[$permission->id]['_update'] = $can_update == '1' ? 'checked' : '';
						$is_checked[$permission->id]['_delete'] = $can_delete == '1' ? 'checked' : '';
		
						$is_active[$permission->id] = isset($set_privileges[$permission->id]) && $set_privileges[$permission->id]['is_active'] == '1' ? 'checked' : '';
					}
					$data['is_checked'] = $is_checked;
					$data['is_active'] = $is_active;
				}

				// check if permission is disabled
				if (count($permissions)) {
					foreach($permissions as $permission){
						$is_disabled[$permission->id]['_create'] = $permission->enable_create == '0' ? 'disabled' : '';
						$is_disabled[$permission->id]['_read'] = $permission->enable_read == '0' ? 'disabled' : '';
						$is_disabled[$permission->id]['_update'] = $permission->enable_update == '0' ? 'disabled' : '';
						$is_disabled[$permission->id]['_delete'] = $permission->enable_delete == '0' ? 'disabled' : '';	
					}
					$data['is_disabled'] = $is_disabled;
				}
				return $data;
			};

			$data['is_checked'] = $user_rp()['is_checked'];
			$data['is_disabled'] = $user_rp()['is_disabled'];
			$data['is_active'] = $user_rp()['is_active'];

			return  view($this->_setPagePath($this->viewPath, 'edit-role-permission'), $data);
		}
		return redirect()->back()->with('error', 'Role not found');
	}

	/**
	 * Set role permissions
	 *
	 * @param integer $role_id
	 * @return void
	 */
	public function edit($role_id) {
        // $this->userLib->has_privilege_or_exception('edit_role'); // check if user has the privilege or show 404
		
		$data = [
			'role_id' => $role_id,
			'can_create' => $this->request->getVar('can_create'),
			'can_read' => $this->request->getVar('can_read'),
			'can_update' => $this->request->getVar('can_update'),
			'can_delete' => $this->request->getVar('can_delete'),
			'is_active' => $this->request->getVar('is_active'),
		];
		
		$result = service('permissionsLib')->set_role_permission($data); // add role
		if ($this->request->isAJAX()) { // if request is ajax
			return $this->response->setJSON($result); // $result['error'] or $result['success']
		} else {
			if (isset($result['success'])) {
				$this->session->setFlashData('success', 'Role Permissions set successfully');
			} else {
				$this->session->setFlashData('error', 'Unable to set Role Permissions');
			}
		}
		return redirect()->back();
    }

}
