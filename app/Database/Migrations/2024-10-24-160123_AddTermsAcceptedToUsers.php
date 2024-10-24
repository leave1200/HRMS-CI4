<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTermsAcceptedToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'terms_accepted' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,  // Default value is 0 (not accepted)
                'null'       => false,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'terms_accepted');
    }
}
