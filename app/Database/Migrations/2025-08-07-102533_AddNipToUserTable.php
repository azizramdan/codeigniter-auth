<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNipToUserTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'after'      => 'username',
                'null'       => false,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => 18,
                'after'      => 'name',
                'null'       => true,
                'unique'     => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'nip');
    }
}
