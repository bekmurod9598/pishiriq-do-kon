<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Foydalanuvchi qo'shish
        DB::table('users')->insert([
            [
                'name' => 'Humoyun',   // Foydalanuvchi nomi
                'email' => 'meliquziyevxumoyun@gmail.com',  // Foydalanuvchi emaili
                'password' => Hash::make('Admin2001'), // Parolni xeshlash
                'created_at' => now(),  // Hozirgi vaqt
                'updated_at' => now()
            ],
            [
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => Hash::make('User1234'), // Foydalanuvchi paroli
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
