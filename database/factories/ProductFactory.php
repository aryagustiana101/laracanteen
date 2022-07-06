<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $category_id = $this->faker->numberBetween(1, 2);

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\id_ID\Restaurant($faker));

        $name = 'default';
        if ($category_id == 1) {
            $name = $faker->foodName();
        }
        if ($category_id == 2) {
            $name = $faker->beverageName();
        }

        $description = $this->faker->sentence(mt_rand(3, 8));

        $price = $this->faker->numberBetween(500, 35000);
        $price_rounded = ((int) $price < 500) ? 500 : round((int) $price / 500) * 500;

        $admin_price = (int) $price * 5 / 100;
        $admin_price_rounded = ($price_rounded * 5 / 100 < 500) ? 500 : round($price_rounded * 5 / 100 / 500) * 500;

        $tax_price = (int) $price * 10 / 100;
        $tax_price_rounded = ($price_rounded * 10 / 100 < 500) ? 500 : round($price_rounded * 10 / 100 / 500) * 500;

        $user_price = $tax_price + $admin_price + (int) $price;
        $user_price_rounded = $price_rounded + $admin_price_rounded + $tax_price_rounded;

        return [
            'store_id' => $this->faker->numberBetween(1, 5),
            'category_id' => $category_id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'price_rounded' => $price_rounded,
            'admin_price' => $admin_price,
            'admin_price_rounded' => $admin_price_rounded,
            'tax_price' => $tax_price,
            'tax_price_rounded' => $tax_price_rounded,
            'user_price' => $user_price,
            'user_price_rounded' => $user_price_rounded,
            'image' => null,
        ];
    }
}
