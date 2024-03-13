<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SesionSeeder extends Seeder
{
    public function run()
    {
        $json = File::get(base_path("sesions.json"));
        $data = json_decode($json);

        foreach ($data as $session) {
            DB::table('sessions')->insert([
                'pelicula_id' => $session->pelicula_id,
                'fecha' => $session->fecha,
                'hora' => $session->hora,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

