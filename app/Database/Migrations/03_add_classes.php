<?php
namespace App\Database\Migration;

use CodeIgniter\Database\Migration;

class AddClasses extends Migration
{
    public function up() {
        $this->forge->addField([
            'osztaly_id' => [
                'type' => "INT",
                "unsigned" => true,
                "auto_increment" => true
            ],
            "evfolyam" => [
                'type' => "TINYINT",
                "default" => 9,
            ],
            "betujel" => [
                "type" => "VARCHAR",
                "constraint" => 1
            ],
            "nev" => [
                "type" => "VARCHAR",
                "constraint" => 20
            ],
            "rovid_nev" => [
                "type" => "VARCHAR",
                "constraint" => 20
            ]
        ]);
        $this->forge->addKey("osztaly_id", true);
        $this->forge->createTable("tb_osztaly");
    }

    public function down() {
        $this->forge->dropTable('tb_osztaly');
    }
}