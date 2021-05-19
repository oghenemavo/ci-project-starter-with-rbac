<?php

namespace Ecosystem\Authentication\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CentralSeeder extends Seeder
{
	public function run()
	{
		$this->call('Ecosystem\Authentication\Database\Seeds\Role');
		$this->call('Ecosystem\Authentication\Database\Seeds\PermissionGroup');
		$this->call('Ecosystem\Authentication\Database\Seeds\Permission');
		$this->call('Ecosystem\Authentication\Database\Seeds\RolePermission');
		$this->call('Ecosystem\Authentication\Database\Seeds\MailClient');
	}
}
