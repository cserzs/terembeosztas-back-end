<?php
namespace App\Database\Migration;

use CodeIgniter\Database\Migration;

class AddAssignment extends Migration
{
    public function up() {
        $this->forge->addField([
            'osztaly_id' => [
                'type' => "INT",
                "unsigned" => true,
            ],
            "nap" => [
                'type' => "TINYINT",
            ],
            "idopont" => [
                "type" => "TINYINT",
            ],
            "pozicio" => [
                "type" => "TINYINT",
            ],
            "terem_id" => [
                "type" => "INT",
                "unsigned" => true
            ]
        ]);
        $this->forge->createTable("tb_beosztas");
    }

    public function down() {
        $this->forge->dropTable('tb_beosztas');
    }
}