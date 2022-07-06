<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teachers = [
            [
                "name" => "Asep Ulumudin",
                "nuptk" => "2151766667130103",
                "nip" => null,
                "email" => "hab.diens@gmail.com",
                "phone_number" => "085294993268",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "1988-08-19",
                "address" => "Kp.Nagrak rt 7 rw 2 Desa Jati Kec. Tarogong Kaler",
                "information" => "Guru SIJ"
            ],
            [
                "name" => "Revy Cahya Alamsyah",
                "nuptk" => "734318903",
                "nip" => null,
                "email" => "revy_cahya@smknegeri1garut.sch.id",
                "phone_number" => null,
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "1998-05-21",
                "address" => "KP. CIPAPAR KALER rt 4 rw 1 Desa Leles Kec. Leles",
                "information" => "Guru SIJ"
            ],
            [
                "name" => "Andriansyah Maulana",
                "nuptk" => "528233703",
                "nip" => null,
                "email" => "andriansyah_maulana@smknegeri1garut.sch.id",
                "phone_number" => null,
                "gender" => "Laki-Laki",
                "birth_place" => "Tanggerang",
                "birth_date" => "1995-08-03",
                "address" => "PERUM BUMI MALAYU ASRI BLOK G 10 RT 2 RW 10 Desa Mekarwangi Kec. Tarogong Kaler",
                "information" => "Guru SIJ"
            ],
            [
                "name" => "Rahmi Aprianti",
                "nuptk" => "100696129",
                "nip" => null,
                "email" => "rahmiaprianti6@gmail.com",
                "phone_number" => null,
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "1997-04-25",
                "address" => "KP. TANJUNG 01/01 Desa PASAWAHAN Kec. Tarogong Kaler",
                "information" => "Guru SIJ"
            ]
        ];
        for ($i = 1; $i <= count($teachers); $i++) {
            Teacher::create([
                "name" => $teachers[$i - 1]["name"],
                "nuptk" => $teachers[$i - 1]["nuptk"],
                "nip" => $teachers[$i - 1]["nip"],
                "gender" => $teachers[$i - 1]['gender'],
                "birth_place" => $teachers[$i - 1]['birth_place'],
                "birth_date" => $teachers[$i - 1]['birth_date'],
                "address" => $teachers[$i - 1]['address']
            ]);
            User::create([
                "student_id" => null,
                "teacher_id" => $i,
                "seller_id" => null,
                "teller_id" => null,
                "email" => $teachers[$i - 1]['email'],
                "phone_number" => $teachers[$i - 1]['phone_number'],
                "password" => Hash::make($teachers[$i - 1]['birth_date'])
            ]);
        }
    }
}
