<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ManagerLaporan extends Migration
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
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'jenis_laporan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // barang, stok, purchasing
            ],
            'periode' => [
                'type'       => 'VARCHAR',
                'constraint' => '100', // e.g., "Januari 2024" or "2024-01-01 - 2024-01-31"
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'uploaded_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->createTable('manager_laporan');
    }

    public function down()
    {
        $this->forge->dropTable('manager_laporan');
    }
}
