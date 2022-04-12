<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('man_carreras')->insert(['ManNombre' => 'No Aplica']);
        DB::table('man_carreras')->insert(['ManNombre' => 'Enfermería']);
        DB::table('man_carreras')->insert(['ManNombre' => 'Kinesiología']);
        DB::table('man_carreras')->insert(['ManNombre' => 'Nutrición']);
        DB::table('man_carreras')->insert(['ManNombre' => 'Obstetricia']);
        DB::table('man_carreras')->insert(['ManNombre' => 'Odontología']);
    }
}
