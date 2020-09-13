<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersTable extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'          		=> [
				'type'          => 'BIGINT',
				'constraint'    => 20,
				'unsigned'      => true,
				'auto_increment' => true
			],
			'name'       		=> [
				'type'          => 'VARCHAR',
				'constraint'    => '255'
			],
			'email'       		=> [
				'type'          => 'VARCHAR',
				'constraint'    => '255'
			],
			'password'       	=> [
				'type'          => 'VARCHAR',
				'constraint'    => '255',
				'null'     		=> true
			],
			'token'       		=> [
				'type'          => 'VARCHAR',
				'constraint'    => '255'
			],
			'created_at'       	=> [
				'type'          => 'DATETIME',
				'null'     		=> true
			],
			'updated_at'       	=> [
				'type'          => 'DATETIME',
				'null'     		=> true
			],
		]);
		$this->forge->addPrimaryKey('id', true);
		$this->forge->createTable('users');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('users');
	}
}
