<?php

namespace Ecosystem\Authentication\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolePermission extends Seeder
{
	public function run()
	{
		$data = [
			'0' => [
				'id' 			=> 1,
				'role_id' 		=> 1,
				'permission_id' => 1,
				'can_create' 	=> '1',
				'can_read' 		=> '1',
				'can_update' 	=> '1',
				'can_delete' 	=> '0',
				'is_active' 	=> '1',
			],
			'1' => [
				'id' 			=> 2,
				'role_id' 		=> 1,
				'permission_id' => 2,
				'can_create' 	=> '1',
				'can_read' 		=> '1',
				'can_update' 	=> '1',
				'can_delete' 	=> '0',
				'is_active' 	=> '1',
			],
			'2' => [
				'id' 			=> 2,
				'role_id' 		=> 1,
				'permission_id' => 3,
				'can_create' 	=> '1',
				'can_read' 		=> '1',
				'can_update' 	=> '1',
				'can_delete' 	=> '0',
				'is_active' 	=> '1',
			],
			'3' => [
				'id' 			=> 2,
				'role_id' 		=> 1,
				'permission_id' => 4,
				'can_create' 	=> '1',
				'can_read' 		=> '1',
				'can_update' 	=> '1',
				'can_delete' 	=> '0',
				'is_active' 	=> '1',
			],
		];

		$model = model('Ecosystem\Authentication\Models\RolePermission');
		$model->insertBatch($data);
	}
}
