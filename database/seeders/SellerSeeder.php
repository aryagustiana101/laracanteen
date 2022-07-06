<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = [
            [
                'name' => 'Toko Cina Cuan',
                'location' => 'Kantin Belakang'
            ],
            [
                'name' => 'Rafles Kantin Terpadu',
                'location' => 'Kantin Rafles'
            ],
            [
                'name' => 'Tukang Minuman Kekinian',
                'location' => 'Kantin Depan'
            ],
            [
                'name' => 'Batagor Enak Kekinian',
                'location' => 'Kantin Belakang'
            ],
            [
                'name' => 'Kantin Rame',
                'location' => 'Kantin Belakang'
            ]
        ];
        $sellers = [
            [
                "name" => "Dimas Aditya",
                "email" => "seller1@smknegeri1garut.sch.id",
                "phone_number" => "081331197881",
                "gender" => "Laki-Laki",
                "birth_place" => "Bandung",
                "birth_date" => "2001-09-11",
                "address" => "Tarogong Kaler"
            ],
            [
                "name" => "Agus Setiawan",
                "email" => "seller2@smknegeri1garut.sch.id",
                "phone_number" => "081339090681",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2001-09-11",
                "address" => "Garut Kota"
            ],
            [
                "name" => "Diandra Sari",
                "email" => "seller3@smknegeri1garut.sch.id",
                "phone_number" => "081339098911",
                "gender" => "Perempuan",
                "birth_place" => "Tasikmalaya",
                "birth_date" => "2001-09-11",
                "address" => "Maktal"
            ],
            [
                "name" => "Rizky Dwi Prasetya",
                "email" => "seller4@smknegeri1garut.sch.id",
                "phone_number" => "081337090981",
                "gender" => "Laki-Laki",
                "birth_place" => "Tasikmalaya",
                "birth_date" => "2001-09-11",
                "address" => "Rancabango"
            ],
            [
                "name" => "Fajar Kurniawan",
                "email" => "seller5@smknegeri1garut.sch.id",
                "phone_number" => "081334090881",
                "gender" => "Laki-Laki",
                "birth_place" => "Tasikmalaya",
                "birth_date" => "2001-09-11",
                "address" => "Samarang"
            ]
        ];
        for ($i = 1; $i <= count($stores); $i++) {
            Store::create([
                "name" => $stores[$i - 1]["name"],
                "initials" => createInitials($stores[$i - 1]["name"]),
                "location" => $stores[$i - 1]["location"],
            ]);
            Seller::create([
                "store_id" => $i,
                "name" => $sellers[$i - 1]["name"],
                "gender" => $sellers[$i - 1]['gender'],
                "birth_place" => $sellers[$i - 1]['birth_place'],
                "birth_date" => $sellers[$i - 1]['birth_date'],
                "address" => $sellers[$i - 1]['address']
            ]);
            User::create([
                "student_id" => null,
                "teacher_id" => null,
                "seller_id" => $i,
                "teller_id" => null,
                "email" => $sellers[$i - 1]['email'],
                "phone_number" => $sellers[$i - 1]['phone_number'],
                "password" => Hash::make($sellers[$i - 1]['birth_date'])
            ]);
        }
    }
}
