<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 15; $i++) {
            $imagePath = '/public/img/default/' . ($i + 1) . '.jpg';

            DB::table('products')->insert([
                'name'        => $faker->word,
                'description' => $faker->sentence(10),
                'image'       => $imagePath,
                'price'       => $faker->numberBetween(10, 1000),
                'stock'       => $faker->numberBetween(2, 60), // Algunos con poco stock para alertas
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $this->command->info('15 productos fueron agregados exitosamente.');
    }
}
