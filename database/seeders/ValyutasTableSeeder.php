<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValyutasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Valyutalarni qo'shish
        DB::table('valyutas')->insert([
            ['valyuta' => 'So\'m', 'created_at' => now(), 'updated_at' => now()],
            ['valyuta' => 'Dollar', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
