<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(['name' => 'Felipe Salinas',  'email' => 'fsalinas@circleone.cl', 'NumeroContacto' => '988841198', 'EstadoContacto' => 'Activo',  'password' => Hash::make('password')]);
        DB::table('users')->insert(['name' => 'Raúl Letelier Gonzalez',  'email' => 'raul_letelier@outlook.cl', 'NumeroContacto' => '965971100', 'EstadoContacto' => 'Activo',  'password' => Hash::make('password')]);
        DB::table('users')->insert(['name' => 'Tanya Valenzuela B',  'email' => 'tanyavalenzuelab@gmail.com', 'NumeroContacto' => '2147483647', 'EstadoContacto' => 'Activo',  'password' => Hash::make('password')]);
    }
}
