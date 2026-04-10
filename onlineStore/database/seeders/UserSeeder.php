<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('adminpassword'), 
            'role' => 'admin',  
            'balance' => 5000, 
        ]);

        $faker = Faker::create();
        for ($i = 0; $i < 20; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'client',
                'balance' => $faker->numberBetween(100, 2000),
            ]);
        }

        $this->command->info('Usuario administrador y 20 clientes creados correctamente.');
    }
}
