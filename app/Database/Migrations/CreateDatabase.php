<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDatabase extends Migration {
    public function up() {
        $this->forge->createDatabase('terembeosztas', true);
    }

    public function down() {
        
    }
}