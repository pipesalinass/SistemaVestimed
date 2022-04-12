<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('man_universidads')->insert(['ManNombre' => 'No Aplica']);
        DB::table('man_universidads')->insert(['ManNombre' => 'Universidad Católica del Norte']);
        DB::table('man_universidads')->insert(['ManNombre' => 'Universidad de Antofagasta']);
        DB::table('man_universidads')->insert(['ManNombre' => 'Universidad Pedro de Valdivia']);
        DB::table('man_universidads')->insert(['ManNombre' => 'Universidad Santo Tomás']);
        DB::table('man_universidads')->insert(['ManNombre' => 'AIEP']);
        DB::table('man_universidads')->insert(['ManNombre' => 'Liceo Técnico Antofagasta']);
    }
}
