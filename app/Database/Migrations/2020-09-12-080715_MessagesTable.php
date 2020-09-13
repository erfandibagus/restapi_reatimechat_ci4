<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MessagesTable extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'          		=> [
				'type'          => 'BIGINT',
				'constraint'    => 20,
				'unsigned'      => true,
				'auto_increment' => true,
			],
			'user_id'       	=> [
				'type'          => 'BIGINT',
				'constraint'    => 20,
				'unsigned'      => true
			],
			'message'       	=> [
				'type'          => 'TEXT'
			],
			'attachment'       	=> [
				'type'          => 'VARCHAR',
				'constraint'    => '255',
				'null'			=> true
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
		$this->forge->createTable('messages');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('messages');
	}
}
