<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          $this->call(UsersGenerate::class);
          $this->call(AirtableGenerate::class);
          $this->call(LayoutGenerate::class);
          $this->call(PagesGenerate::class);
    }
}
