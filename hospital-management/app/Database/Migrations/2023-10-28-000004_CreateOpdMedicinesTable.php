<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateOpdMedicinesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'opd_visit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'medicine_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'strength' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'dosage' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'frequency' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'duration' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'instructions' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('opd_visit_id', 'opd_visits', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('opd_medicines');
    }

    public function down()
    {
        $this->forge->dropTable('opd_medicines');
    }
}
