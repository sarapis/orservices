<?php

use Illuminate\Database\Seeder;

class SqlDumpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $path = base_path().'/database/dump/ms_server.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
