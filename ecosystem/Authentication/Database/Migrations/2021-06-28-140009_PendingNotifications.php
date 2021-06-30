<?php

namespace Ecosystem\Authentication\Database\Migrations;

use CodeIgniter\Database\Migration;

class PendingNotifications extends Migration
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
			'user_id' => [
				'type'       		=> 'INT',
				'constraint' 		=> 11,
				'unsigned'       	=> true,
				'null' 				=> false,
			],
			'notification_type' => [
				'type'       		=> 'ENUM',
				'constraint' 		=> ['0', '1'],
				'default'        	=> '0',
			],
			'notification_subject' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 256,
				'null' 				=> true,
			],
			'notification_body' => [
				'type'       		=> 'TEXT',
				'null' 				=> false,
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
		$this->forge->addKey('user_id');
		$this->forge->addForeignKey('user_id','users','id','RESTRICT','CASCADE');
		$this->forge->createTable('pending_notifications');

		$this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('pending_notifications');
	}
}
