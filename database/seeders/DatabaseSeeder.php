<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            AdministratorSeeder::class,
            SettingSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            SellerSeeder::class,
            TellerSeeder::class,
            CategorySeeder::class,
            StatusSeeder::class
        ]);

        Product::factory(100)->create();
    }
}
