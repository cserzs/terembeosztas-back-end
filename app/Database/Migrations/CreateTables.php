<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTables extends Migration
{
        public function up() {
            $this->createRooms();
            $this->createClasses();
            $this->createAssignment();
        }

        public function createRooms() {
                $this->forge->addField([
                        'terem_id' => [
                        'type'           => 'INT',
                        'unsigned'       => true,
                        'auto_increment' => true,
                ],
                        'nev'       => [
                        'type'       => 'VARCHAR',
                        'constraint' => '20',
                ],
                        'rovid_nev' => [
                        'type' => 'VARCHAR',
                        'constraint' => '5',
                ],
                        'megjegyzes' => [
                        'type' => 'VARCHAR',
                        'constraint' => '50',
                ],
                ]);
                $this->forge->addKey('terem_id', true);
                $this->forge->createTable('tb_terem');
        }

        public function createClasses() {
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
        
        public function createAssignment() {
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
        
        public function down()
        {
                $this->forge->dropTable('tb_terem');
        }
}