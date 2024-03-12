<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class MoviesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get(database_path('../movies.json'));
        $movies = json_decode($json, true);

        foreach ($movies as $movie) {
            Product::create([
                'name' => $movie['name'],
                'price' => $movie['price'],
                'image_url' => $movie['image_url'],
            ]);
        }
    }
}
