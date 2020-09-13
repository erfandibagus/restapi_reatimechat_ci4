<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LogsTable extends Migration
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
			'user_id'       	=> [
				'type'          => 'BIGINT',
				'constraint'    => 20,
				'unsigned'      => true
			],
			'ip_address'       	=> [
				'type'          => 'VARCHAR',
				'constraint'    => '255'
			],
			'login_at'       	=> [
				'type'          => 'DATETIME',
				'null'     		=> true
			],
			'leave_at'       	=> [
				'type'          => 'DATETIME',
				'null'     		=> true
			],
		]);
		$this->forge->addPrimaryKey('id', true);
		$this->forge->createTable('logs');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('logs');
	}
}
