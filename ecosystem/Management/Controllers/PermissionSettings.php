<?php

namespace Ecosystem\Management\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;
use Ecosystem\Authentication\Models\Permission;

class PermissionSettings extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Management\\Views\\settings\\';

	/**
	 * Permission Group Page
	 *
	 * @return void
	 */
	public function index()
	{
		// $this->userLib->has_privilege_or_exception('view_role'); // check privilege or 404

        $data['page_title'] = 'Permission Group';
        $data['validation'] = $this->validation;
        $data['groups'] = service('permissionsLib')->get_permission_groups(); // fetch permission groups
		$data['permissions_count'] = function($id) 
		{
			$permission = new Permission();
			return $permission->where('perm_group_id', $id)->countAllResults();
		};

        echo  view($this->_setPagePath($this->viewPath, 'permission-group'), $data);
	}

	/**
     * Create Role page
     *
     * @return void
     */
    public function createPermissionGroup() {
        // $this->userLib->has_privilege_or_exception('add_role'); // check if user has the privilege or show 404

		// create validation rules
		$rules = [
			'group' => [
				'label' => 'Group Name',
				'rules' => 'required|alpha_numeric_space|min_length[4]|is_unique[permission_groups.group_name]',
				'errors' => [
					'is_unique' => 'This {field} already exists'
				]
			],
			'group_slug' => [
				'label' => 'Group Slug',
				'rules' => 'required|alpha_dash|min_length[3]|is_unique[permission_groups.group_slug]',
				'errors' => [
					'is_unique' => 'This {field} already exists'
				]
			],
			'group_description' => [
				'label' => 'Group Description',
				'rules' => 'permit_empty|alpha_numeric_space|min_length[10]',
			],
		];

		// set validation rules
		$this->validation->setRules($rules);
		// validate request
		if (! $this->validation->withRequest($this->request)->run()) { // fails
			if ($this->request->isAJAX()) { // if ajax request
				return $this->response->setJSON($this->validation->getErrors());    
			} else {
				return redirect()->back()->withInput()->with('error', 'Invalid details found!'); // return to sign up page
			}
		} else { // passes
			helper('inflector');
			$slug = strtolower(trim($this->request->getVar('group_slug')));

			$data = [
				'group_name' => ucwords($this->request->getVar('group')),
				'group_slug' => underscore($slug),
				'group_description' => $this->request->getVar('group_description'),
				'is_active' => $this->request->getVar('is_active') ? '1' : '0',
			];
			
			$result = service('permissionsLib')->add_group($data); // add role
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashData('success', 'Permission Group successfully');
				} else {
					$this->session->setFlashData('error', 'Unable to create Permission Group');
				}
			}
			return redirect()->back();
		}
    }

	/**
	 * Edit Permission Group page
	 *
	 * @param integer $id
	 * @return void
	 */
	public function edit($id) {
		// $this->userLib->has_privilege_or_exception('edit_role');

		$permission_group = service('permissionsLib')->find_permission_group($id);
		if ($permission_group) {
			$data['page_title'] = 'Edit Permission Group';
			$data['validation'] = $this->validation;
			$data['group'] = $permission_group;
			return view($this->_setPagePath($this->viewPath, 'edit-permission-group'), $data);
		}
		return redirect()->back()->with('error', 'Permission Group not found');
	}

	/**
	 * Edit a Permission Group
	 *
	 * @param integer $id
	 * @return void
	 */
	public function editPermissionGroup($id) {
        // $this->userLib->has_privilege_or_exception('edit_role'); // check if user has the privilege or show 404

		// create validation rules
		$rules = [
			'group' => [
				'label' => 'Group Name',
				'rules' => "required|alpha_numeric_space|min_length[4]|is_unique[permission_groups.group_name,id,{$id}]",
				'errors' => [
					'is_unique' => 'This {field} already exists'
				]
			],
			'group_slug' => [
				'label' => 'Group Slug',
				'rules' => "required|alpha_dash|min_length[3]|is_unique[permission_groups.group_slug,id,{$id}]",
				'errors' => [
					'is_unique' => 'This {field} already exists'
				]
			],
			'group_description' => [
				'label' => 'Group Description',
				'rules' => 'permit_empty|alpha_numeric_space|min_length[10]',
			],
		];

		// set validation rules
		$this->validation->setRules($rules);
		// validate request
		if (! $this->validation->withRequest($this->request)->run()) { // fails
			if ($this->request->isAJAX()) { // if ajax request
				return $this->response->setJSON($this->validation->getErrors());    
			} else {
				return redirect()->back()->withInput()->with('error', 'Invalid details found!'); // return to sign up page
			}
		} else { // passes
			helper('inflector');
			$slug = strtolower(trim($this->request->getVar('group_slug')));

			$data = [
				'id' => $id,
				'group_name' => ucwords($this->request->getVar('group')),
				'group_slug' => underscore($slug),
				'group_description' => $this->request->getVar('group_description'),
				'is_active' => $this->request->getVar('is_active') ? '1' : '0',
			];
			
			$result = service('permissionsLib')->add_group($data); // add role
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashData('success', 'Permission Group Edited successfully');
				} else {
					$this->session->setFlashData('error', 'Unable to edit Permission Group');
				}
			}
			return redirect()->back();
		}
    }

	/**
	 * View a Permission
	 *
	 * @param integer $perm_group_id
	 * @return void
	 */
	public function viewPermission($perm_group_id)
	{
		// $this->userLib->has_privilege_or_exception('view_role'); // check privilege or 404
		$permissions = service('permissionsLib')->get_permissions_by_group($perm_group_id);

		$data['page_title'] = 'Permissions';
		$data['validation'] = $this->validation;
		$data['perm_group_id'] = $perm_group_id;
		$data['permissions'] = $permissions;
		if (count($permissions) < 1) {
			$this->session->setFlashData('error', 'No permissions set yet');
		}
		return view($this->_setPagePath($this->viewPath, 'permission'), $data);
	}

	public function createPermission($perm_group_id) {
        // $this->userLib->has_privilege_or_exception('add_role'); // check if user has the privilege or show 404

		// create validation rules
		$rules = [
			'permission' => [
				'label' => 'Permission Name',
				'rules' => 'required|alpha_numeric_space|min_length[4]|is_unique[permissions.permission]',
				'errors' => [
					'is_unique' => 'This {field} already exists'
				]
			],
			'permission_slug' => [
				'label' => 'Permission Slug',
				'rules' => 'required|alpha_dash|min_length[3]|is_unique[permissions.permission_slug]',
				'errors' => [
					'is_unique' => 'This {field} already exists'
				]
			],
		];

		// set validation rules
		$this->validation->setRules($rules);
		// validate request
		if (! $this->validation->withRequest($this->request)->run()) { // fails
			if ($this->request->isAJAX()) { // if ajax request
				return $this->response->setJSON($this->validation->getErrors());    
			} else {
				return redirect()->back()->withInput()->with('error', 'Invalid details found!'); // return to sign up page
			}
		} else { // passes
			helper('inflector');
			$slug = strtolower(trim($this->request->getVar('permission_slug')));

			$data = [
				'perm_group_id' => $perm_group_id,
				'permission' => ucwords($this->request->getVar('permission')),
				'permission_slug' => underscore($slug),
				'enable_create' => $this->request->getVar('enable_create') ? '1' : '0',
				'enable_read' => $this->request->getVar('enable_read') ? '1' : '0',
				'enable_update' => $this->request->getVar('enable_update') ? '1' : '0',
				'enable_delete' => $this->request->getVar('enable_delete') ? '1' : '0',
			];
			
			$result = service('permissionsLib')->add_permission($data); // add role
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashData('success', 'Permission created successfully');
				} else {
					$this->session->setFlashData('error', 'Unable to create Permission');
				}
			}
			return redirect()->back();
		}
    }

	/**
	 * Edit a Permission page
	 *
	 * @param integer $id
	 * @return void
	 */
	public function editPermission($id) {
		// $this->userLib->has_privilege_or_exception('edit_role');

		$permission = service('permissionsLib')->find_permission($id);
		if ($permission) {
			$data['page_title'] = 'Edit Permission';
			$data['validation'] = $this->validation;
			$data['permission'] = $permission;
			return view($this->_setPagePath($this->viewPath, 'edit-permission'), $data);
		}
		return redirect()->back()->with('error', 'Permission not found');
	}

	/**
	 * Edit a Permission 
	 *
	 * @param integer $id
	 * @return void
	 */
	public function processEditPermission($id) {
        // $this->userLib->has_privilege_or_exception('edit_role'); // check if user has the privilege or show 404

		// create validation rules
		$rules = [
			'permission' => [
				'label' => 'Permission Name',
				'rules' => "required|alpha_numeric_space|min_length[4]|is_unique[permissions.permission,id,{$id}]",
				'errors' => [
					'is_unique' => 'This {field} already exists'
				]
			],
			'permission_slug' => [
				'label' => 'Permission Slug',
				'rules' => "required|alpha_dash|min_length[3]|is_unique[permissions.permission_slug,id,{$id}]",
				'errors' => [
					'is_unique' => 'This {field} already exists'
				]
			],
		];

		// set validation rules
		$this->validation->setRules($rules);
		// validate request
		if (! $this->validation->withRequest($this->request)->run()) { // fails
			if ($this->request->isAJAX()) { // if ajax request
				return $this->response->setJSON($this->validation->getErrors());    
			} else {
				return redirect()->back()->withInput()->with('error', 'Invalid details found!'); // return to sign up page
			}
		} else { // passes
			helper('inflector');
			$slug = strtolower(trim($this->request->getVar('permission_slug')));

			$data = [
				'id' => $id,
				'permission' => ucwords($this->request->getVar('permission')),
				'permission_slug' => underscore($slug),
				'enable_create' => $this->request->getVar('enable_create') ? '1' : '0',
				'enable_read' => $this->request->getVar('enable_read') ? '1' : '0',
				'enable_update' => $this->request->getVar('enable_update') ? '1' : '0',
				'enable_delete' => $this->request->getVar('enable_delete') ? '1' : '0',
			];
			
			$result = service('permissionsLib')->add_permission($data); // add role
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashData('success', 'Permission Edited successfully');
				} else {
					$this->session->setFlashData('error', 'Unable to edit Permission');
				}
			}
			return redirect()->back();
		}
    }


}
