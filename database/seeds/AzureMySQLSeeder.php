<?php

use Illuminate\Database\Seeder;

class AzureMySQLSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $path = base_path().'/database/dump/azure_mysql.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
