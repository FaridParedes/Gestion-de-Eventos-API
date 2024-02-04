<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = array(
            array('name'=>'Programado', 'created_at' => Carbon::now()),
            array('name'=>'En curso',  'created_at' => Carbon::now()),
            array('name'=>'Finalizado', 'created_at' => Carbon::now()),
            array('name'=>'Cancelado', 'created_at' => Carbon::now()),
        );
        DB::table('estados')->insert($data);
    }
}
