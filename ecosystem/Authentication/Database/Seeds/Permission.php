<?php

namespace Ecosystem\Authentication\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Permission extends Seeder
{
	public function run()
	{
		$data = [
			'0' => [
				'id' 				=> 1,
				'perm_group_id' 	=> 1,
				'permission' 		=> 'Role',
				'permission_slug' 	=> 'role',
				'enable_create' 	=> '1',
				'enable_read' 		=> '1',
				'enable_update' 	=> '1',
				'enable_delete' 	=> '1',
			],
			'1' => [
				'id' 				=> 2,
				'perm_group_id' 	=> 1,
				'permission' 		=> 'Permission Group',
				'permission_slug' 	=> 'permission_group',
				'enable_create' 	=> '1',
				'enable_read' 		=> '1',
				'enable_update' 	=> '1',
				'enable_delete' 	=> '1',
			],
			'2' => [
				'id' 				=> 3,
				'perm_group_id' 	=> 1,
				'permission' 		=> 'Permission',
				'permission_slug' 	=> 'permission',
				'enable_create' 	=> '1',
				'enable_read' 		=> '1',
				'enable_update' 	=> '1',
				'enable_delete' 	=> '1',
			],
			'3' => [
				'id' 				=> 4,
				'perm_group_id' 	=> 1,
				'permission' 		=> 'Role Permission',
				'permission_slug' 	=> 'role_permission',
				'enable_create' 	=> '1',
				'enable_read' 		=> '1',
				'enable_update' 	=> '1',
				'enable_delete' 	=> '1',
			],
		];

		$model = model('Ecosystem\Authentication\Models\Permission');
		$model->insertBatch($data);
	}
}
