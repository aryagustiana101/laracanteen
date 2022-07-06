<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Makanan', 'Minuman'];
        for ($i = 1; $i <= count($categories); $i++) {
            Category::create([
                "name" => $categories[$i - 1],
            ]);
        }
    }
}
