<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TipoPrendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('man_tipo_prendas')->insert(['ManNombre' => 'Top']);
        DB::table('man_tipo_prendas')->insert(['ManNombre' => 'Pantalón']);
        DB::table('man_tipo_prendas')->insert(['ManNombre' => 'Gorro']);
        DB::table('man_tipo_prendas')->insert(['ManNombre' => 'Chaqueta']);
        DB::table('man_tipo_prendas')->insert(['ManNombre' => 'Polar']);
        DB::table('man_tipo_prendas')->insert(['ManNombre' => 'Delantal']);
    }
}
