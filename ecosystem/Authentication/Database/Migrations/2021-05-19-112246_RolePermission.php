<?php

namespace Ecosystem\Authentication\Database\Migrations;

use CodeIgniter\Database\Migration;

class RolePermission extends Migration
{
	public function up()
	{
		$fields = [
			'id' => [
				'type' 				=> 'INT',
				'constraint'     	=> 11,
				'unsigned'       	=> true,
				'auto_increment' 	=> true,
				'null' 				=> false,
			],
			'role_id' => [
				'type' 				=> 'INT',
				'constraint'     	=> 11,
				'unsigned'       	=> true,
				'null' 				=> false,
			],
			'permission_id' => [
				'type' 				=> 'INT',
				'constraint'     	=> 11,
				'unsigned'       	=> true,
				'null' 				=> false,
			],
			'can_create' => [
				'type'       		=> 'ENUM',
				'constraint' 		=> ['0', '1'],
				'default'        	=> '0',
			],
			'can_read' => [
				'type'       		=> 'ENUM',
				'constraint' 		=> ['0', '1'],
				'default'        	=> '0',
			],
			'can_update' => [
				'type'       		=> 'ENUM',
				'constraint' 		=> ['0', '1'],
				'default'        	=> '0',
			],
			'can_delete' => [
				'type'       		=> 'ENUM',
				'constraint' 		=> ['0', '1'],
				'default'        	=> '0',
			],
			'is_active' => [
				'type'       		=> 'ENUM',
				'constraint' 		=> ['0', '1'],
				'default'			=> '0',
			],
			'created_at' => [
				'type' 				=> 'DATETIME',
			],
			'updated_at' => [
				'type' 				=> 'DATETIME',
			]
		];

		$this->db->disableForeignKeyChecks();

		$this->forge->addField($fields);
		$this->forge->addPrimaryKey('id');
		$this->forge->addKey('role_id');
		$this->forge->addForeignKey('role_id','roles','id','RESTRICT','CASCADE');
		$this->forge->addKey('permission_id');
		$this->forge->addForeignKey('permission_id','permissions','id','RESTRICT','CASCADE');
		$this->forge->createTable('role_permissions');

		$this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('role_permissions');
	}
}
