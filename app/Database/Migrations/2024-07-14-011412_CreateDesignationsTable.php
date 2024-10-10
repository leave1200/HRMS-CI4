<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDesignationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('designations');
    }

    public function down()
    {
        $this->forge->dropTable('designations');
    }
}
