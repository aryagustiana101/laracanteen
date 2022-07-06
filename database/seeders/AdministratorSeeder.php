<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Administrator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adiminstartors = [
            [
                "name" => "Master Gustiana",
                "email" => "root",
                "phone_number" => "081212341234",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-03-03",
                "address" => "Samarang"
            ]
        ];
        for ($i = 1; $i <= count($adiminstartors); $i++) {
            Administrator::create([
                "name" => $adiminstartors[$i - 1]["name"],
                "gender" => $adiminstartors[$i - 1]['gender'],
                "birth_place" => $adiminstartors[$i - 1]['birth_place'],
                "birth_date" => $adiminstartors[$i - 1]['birth_date'],
                "address" => $adiminstartors[$i - 1]['address']
            ]);
            User::create([
                "student_id" => null,
                "teacher_id" => null,
                "seller_id" => null,
                "teller_id" => null,
                "administrator_id" => $i,
                "email" => $adiminstartors[$i - 1]['email'],
                "phone_number" => $adiminstartors[$i - 1]['phone_number'],
                "password" => Hash::make('123')
            ]);
        }
    }
}
