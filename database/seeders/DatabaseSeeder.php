<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MataKuliahSeeder::class,
            KelompokSeeder::class,
<<<<<<< HEAD
            ContactSeeder::class,
            NotificationSeeder::class,
=======
>>>>>>> 7e4c8f16039fdbc37547803aea3ddc7c4610d0c4
        ]);
    }
}
