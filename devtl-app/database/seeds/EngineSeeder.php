<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EngineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('engines')->truncate();
        DB::table('engines')->insert([
            ['name' => 'InnoDB'],
            ['name' => 'MyISAM'],
            ['name' => 'Aria'],
            ['name' => 'MEMORY'],
            ['name' => 'MRG_MyISAM'],
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
