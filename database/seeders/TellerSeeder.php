<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teller;
use App\Models\Cashier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cashiers = [
            [
                'name' => 'Kasir Kantin Depan',
                'location' => 'Kantin Depan'
            ],
            [
                'name' => 'Kasir Kantin Belakang',
                'location' => 'Kantin Belakang'
            ],
        ];
        $tellers = [
            [
                "name" => "Novita Rangkuti",
                "email" => "teller1@smknegeri1garut.sch.id",
                "phone_number" => "081331199901",
                "gender" => "Perempuan",
                "birth_place" => "Bandung",
                "birth_date" => "2001-09-11",
                "address" => "Tarogong Kaler"
            ],
            [
                "name" => "Raden Rahmat",
                "email" => "teller2@smknegeri1garut.sch.id",
                "phone_number" => "08133908891",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2001-09-11",
                "address" => "Garut Kota"
            ],
        ];
        for ($i = 1; $i <= count($cashiers); $i++) {
            Cashier::create([
                "name" => $cashiers[$i - 1]["name"],
                "initials" => createInitials($cashiers[$i - 1]["name"]),
                "location" => $cashiers[$i - 1]["location"],
            ]);
            Teller::create([
                "cashier_id" => $i,
                "name" => $tellers[$i - 1]["name"],
                "gender" => $tellers[$i - 1]['gender'],
                "birth_place" => $tellers[$i - 1]['birth_place'],
                "birth_date" => $tellers[$i - 1]['birth_date'],
                "address" => $tellers[$i - 1]['address']
            ]);
            User::create([
                "student_id" => null,
                "teacher_id" => null,
                "seller_id" => null,
                "teller_id" => $i,
                "email" => $tellers[$i - 1]['email'],
                "phone_number" => $tellers[$i - 1]['phone_number'],
                "password" => Hash::make($tellers[$i - 1]['birth_date'])
            ]);
        }
    }
}
