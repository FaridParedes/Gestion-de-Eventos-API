<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = array(
            array('name' => 'Conferencias académicas', 'created_at' => Carbon::now()),
            array('name' => 'Seminarios y talleres', 'created_at' => Carbon::now()),
            array('name' => 'Eventos deportivos', 'created_at' => Carbon::now()),
            array('name' => 'Actividades culturales', 'created_at' => Carbon::now()),
            array('name' => 'Club estudiantil', 'created_at' => Carbon::now()),
            array('name' => 'Eventos de networking', 'created_at' => Carbon::now()),
            array('name' => 'Charlas motivacionales', 'created_at' => Carbon::now()),
            array('name' => 'Presentaciones artísticas', 'created_at' => Carbon::now()),
            array('name' => 'Ferias y exposiciones', 'created_at' => Carbon::now()),
            array('name' => 'Eventos de recaudación de fondos', 'created_at' => Carbon::now())
        );
        

        DB::table('categorias')->insert($data);
    }
}
