<?php

namespace Ecosystem\Authentication\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionGroup extends Seeder
{
	public function run()
	{
		$data = [
			'0' => [
				'id' 				=> 1,
				'group_name' 		=> 'Application Administration',
				'group_slug' 		=> 'app_administration',
				'group_description' => 'Application Settings Administration',
				'is_active' 		=> '1',
			],
		];

		$model = model('Ecosystem\Authentication\Models\PermissionGroup');
		$model->insertBatch($data);
	}
}
