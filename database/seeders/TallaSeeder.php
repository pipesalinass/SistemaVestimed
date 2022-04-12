<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TallaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('man_talla_prendas')->insert(['ManNombre' => 'XXS']);
        DB::table('man_talla_prendas')->insert(['ManNombre' => 'XS']);
        DB::table('man_talla_prendas')->insert(['ManNombre' => 'S']);
        DB::table('man_talla_prendas')->insert(['ManNombre' => 'M']);
        DB::table('man_talla_prendas')->insert(['ManNombre' => 'L']);
        DB::table('man_talla_prendas')->insert(['ManNombre' => 'XL']);
        DB::table('man_talla_prendas')->insert(['ManNombre' => 'XXL']);
        DB::table('man_talla_prendas')->insert(['ManNombre' => 'XXXL']);
    }
}
