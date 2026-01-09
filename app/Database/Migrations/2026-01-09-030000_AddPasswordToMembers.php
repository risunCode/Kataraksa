<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordToMembers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('members', [
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('members', 'password');
    }
}
