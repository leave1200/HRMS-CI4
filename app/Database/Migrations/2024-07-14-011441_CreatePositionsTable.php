<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePositionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'designation_id' => [
                'type' => 'INT'
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('designation_id', 'designations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('positions');
    }

    public function down()
    {
        $this->forge->dropTable('positions');
    }
}
