<?php

namespace Ecosystem\Authentication\Database\Migrations;

use CodeIgniter\Database\Migration;

class PermissionGroup extends Migration
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
			'group_name' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 50,
				'null' 				=> false,
			],
			'group_slug' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 100,
				'null' 				=> false,
			],
			'group_description' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 256,
				'null' 				=> true,
			],
			'is_active' => [
				'type'       		=> 'ENUM',
				'constraint' 		=> ['0', '1'],
				'default'        	=> '0',
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
		$this->forge->addUniqueKey('group_name');
		$this->forge->addUniqueKey('group_slug');
		$this->forge->createTable('permission_groups');

		$this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('permission_groups');
	}
}
