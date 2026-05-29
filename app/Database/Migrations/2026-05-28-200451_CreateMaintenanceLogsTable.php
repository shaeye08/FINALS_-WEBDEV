<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMaintenanceLogsTable extends Migration
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
            'asset_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'issue' => [
                'type' => 'TEXT',
            ],
            'repair_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Pending', 'Ongoing', 'Completed'],
                'default'    => 'Pending',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('asset_id', 'assets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('maintenance_logs');
    }

    public function down()
    {
        $this->forge->dropTable('maintenance_logs');
    }
}