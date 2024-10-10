<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'username'    => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'email'       => [
                'type'       => 'VARCHAR',
                'constraint' => '255', 
                'null'       => false,
            ],
            'password'    => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'picture'     => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'bio'         => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Remove default value for 'created_at' here
            'created_at'  => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
            ],
            'updated_at'  => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);

        // Alter table to set default values and update values
        $this->db->query('ALTER TABLE `users` MODIFY `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->db->query('ALTER TABLE `users` MODIFY `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
