<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFileDataToFiles extends Migration
{
    public function up()
    {
        $fields = [
            'file_data' => [
                'type'       => 'LONGBLOB', // Suitable for large files
                'null'       => true, // Allow NULL for existing records
                'comment'    => 'Binary data of the uploaded file'
            ],
        ];

        $this->forge->addColumn('files', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('files', 'file_data');
    }
}
