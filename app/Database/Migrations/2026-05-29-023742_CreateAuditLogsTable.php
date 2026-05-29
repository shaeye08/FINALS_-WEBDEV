<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
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
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true, // Nullable to catch anonymous or public scan events
            ],
            'operator_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => '100', // e.g., 'CREATE', 'UPDATE', 'DELETE'
            ],
            'target_item' => [
                'type'       => 'VARCHAR',
                'constraint' => '255', // e.g., 'Asset: LAP-2026-001'
            ],
            'details' => [
                'type' => 'TEXT', // Stores JSON formatting arrays of modified states
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id'); // Indexed for fast filtering by user
        $this->forge->createTable('audit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs');
    }
}