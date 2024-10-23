<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            's_id'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=>true,
            ],
            'title'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255'
            ],
            'email'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255'
            ],
            'phone'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>true,
            ],
            'meta_keywords'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>true,
            ],
            'description'=>[
                'type'=>'TEXT',
                'null'=>true,
            ],
            'logo'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>true,
            ],
            'favicon'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>true,
            ],
            'created_at' => [
                    'type' => 'DATETIME',
                    'default' => 'CURRENT_TIMESTAMP',
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'default' => 'CURRENT_TIMESTAMP',
                    'on_update' => 'CURRENT_TIMESTAMP',
                ],

        ]);
        $this->forge->addKey('s_id', true);
        $this->forge->createTable('settings');
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
