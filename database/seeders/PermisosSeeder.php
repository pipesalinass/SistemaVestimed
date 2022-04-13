<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos = [
            'Editar pedidos',
            'Ver modelos',
            'Realizar Tallajes',
            'Asignar Personas',
            'Ver estado',
            'No Ver estado',
            'Quitar personas',
            'Generar Pedidos',
            'Agregar modelos',
            'Anular modelos',
            'Editar modelos',
            'Agregar colores',
            'Vincular modelo pedido',
            'Anular colores',
            'Editar colores',
            'Quitar vinculacion modelo persona',
            'Agregar prendas',
            'Ver usuarios',
            'Ver pedidos',
            'Ver pedidos admin',
            'Hacer pedidos',
        ];
        foreach($permisos as $permiso){
            Permission::create(['name'=>$permiso]);
        }
    }
}