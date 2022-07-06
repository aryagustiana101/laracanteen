<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = [
            [
                "name" => "Achmad Fajar Mursyidan",
                "nis" => "192010037",
                "nisn" => "0044329346",
                "email" => "iylb0k@erapor-smk.net",
                "phone_number" => "081320297563",
                "gender" => "Laki-Laki",
                "birth_place" => "Bandung",
                "birth_date" => "2004-03-05",
                "address" => "Kp. Paledang 04/02 rt 4 rw 10 Desa TANJUNGLAYA Kec. Cikancung",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Ai Rohimah",
                "nis" => "192010040",
                "nisn" => "0045841881",
                "email" => "mh48bp@erapor-smk.net",
                "phone_number" => "081320199072",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2004-04-26",
                "address" => "Babakan Abid rt 5 rw 2 Desa Suci Kaler Kec. Karangpawitan",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Alif Sulaiman Suryaningrat",
                "nis" => "192010041",
                "nisn" => "0049313662",
                "email" => "1eufpe@erapor-smk.net",
                "phone_number" => "081320199073",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-11-29",
                "address" => "Kp. Sawah Lega rt 4 rw 1 Desa Ngamplangsari Kec. Garut Kota",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Alpa Rizki Ramadhan",
                "nis" => "192010043",
                "nisn" => "0041400373",
                "email" => "scjbjx@erapor-smk.net",
                "phone_number" => "081320199074",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-11-26",
                "address" => "KP. SITU RT/RW: 004/006 rt 4 rw 6 Desa RANCABANGO Kec. Tarogong Kaler",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Alvira Annisa Nuraliza",
                "nis" => "192010044",
                "nisn" => "0046404075",
                "email" => "jswnrs@erapor-smk.net",
                "phone_number" => "081320199075",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2004-09-24",
                "address" => "PERUM BCI BLOK 6 NO 116 rt 3 rw 15 Desa LEBAKJAYA Kec. Karangpawitan",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Andhika Ahmad Abdillah",
                "nis" => "192010045",
                "nisn" => "0037552068",
                "email" => "xinxql@erapor-smk.net",
                "phone_number" => "081320199076",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-07-13",
                "address" => "Cikajang rt 4 rw 6 Desa Cibodas Kec. Cikajang",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Dedi Samolin Asrizal",
                "nis" => "192010050",
                "nisn" => "0039146282",
                "email" => "daittu@erapor-smk.net",
                "phone_number" => "081320199077",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-07-24",
                "address" => "Kp. Tanjungpura rt 2 rw 6 Desa Lengkongjaya Kec. Karangpawitan",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Desta Fajar Ferdian",
                "nis" => "192010051",
                "nisn" => "0032604051",
                "email" => "mwgmji@erapor-smk.net",
                "phone_number" => "081320199078",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-12-29",
                "address" => "KP. SAWAHLEGA rt 2 rw 2 Desa NGAMPLANGSARI Kec. Cilawu",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Dhifa Marti Nagie",
                "nis" => "192010054",
                "nisn" => "0045559775",
                "email" => "dc2cin@erapor-smk.net",
                "phone_number" => "081320199080",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-03-25",
                "address" => "KP. CISOMPET KAUM 02/02 rt 0 rw 0 Desa CISOMPET Kec. Cisompet",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Dini Ayu Lestari",
                "nis" => "192010056",
                "nisn" => "0034708393",
                "email" => "avpkcn@erapor-smk.net",
                "phone_number" => "081320199081",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2003-06-18",
                "address" => "KH. HASBULOH rt 2 rw 10 Desa SUKAKARYA Kec. Tarogong Kidul",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Ferdi Dwi Putra",
                "nis" => "192010058",
                "nisn" => "0032383569",
                "email" => "o9yp1s@erapor-smk.net",
                "phone_number" => "081320199082",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-09-27",
                "address" => "GORDAH rt 4 rw 9 Desa JAYAWARAS Kec. Tarogong Kidul",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Haris Ramdan Putra",
                "nis" => "192010061",
                "nisn" => "0036568001",
                "email" => "vbfkk0@erapor-smk.net",
                "phone_number" => "081320199083",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-11-06",
                "address" => "KP. BOJONG CAMPAKA RT/RW: 003/006 rt 3 rw 6 Desa WANAMEKAR Kec. Wanaraja",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Ilham Kurniawan",
                "nis" => "192010066",
                "nisn" => "0038255356",
                "email" => "u1wirc@erapor-smk.net",
                "phone_number" => "081320199085",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-10-07",
                "address" => "Jln Cimanuk rt 2 rw 7 Desa Jayaraga Kec. Tarogong Kidul",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Ilham Mulahman",
                "nis" => "192010067",
                "nisn" => "0049396323",
                "email" => "elledp@erapor-smk.net",
                "phone_number" => "081320199086",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-01-13",
                "address" => "Kp. Pasir Pogor rt 1 rw 15 Desa Margawati Kec. Garut Kota",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Irfan Nurhakim",
                "nis" => "192010070",
                "nisn" => "0042460705",
                "email" => "2vo6rz@erapor-smk.net",
                "phone_number" => "081320199087",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-06-12",
                "address" => "KP. Pogor rt 1 rw 6 Desa Cintarasa Kec. Samarang",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Jihan Fadliyyah",
                "nis" => "192010071",
                "nisn" => "0043998157",
                "email" => "s2mcw4@erapor-smk.net",
                "phone_number" => "081320199088",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2004-03-30",
                "address" => "Kp.Babakan Wetan rt 5 rw 3 Desa Sukakarya Kec. Banyuresmi",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "M Zidan Cahya Diningrat",
                "nis" => "192010075",
                "nisn" => "0049440675",
                "email" => "de1h7r@erapor-smk.net",
                "phone_number" => "081320199089",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-01-14",
                "address" => "Jl. Leuwidaun rt 3 rw 8 Desa Haurpanggung Kec. Tarogong Kidul",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "M Arya Anggara Gustiana",
                "nis" => "192010076",
                "nisn" => "0045998701",
                "email" => "skyanggara123@gmail.com",
                "phone_number" => "081320199090",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-03-03",
                "address" => "Kp. Waluran rt 1 rw 5 Desa SUKALAKSANA Kec. Samarang",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Najib Algifari",
                "nis" => "192010082",
                "nisn" => "0044515696",
                "email" => "sbjl2z@erapor-smk.net",
                "phone_number" => "081320199091",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-03-12",
                "address" => "Kp. Pamoyanan rt 6 rw 2 Desa Sukagalih Kec. Tarogong Kidul",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Putri Putriyani",
                "nis" => "192010085",
                "nisn" => "0044683531",
                "email" => "npf6st@erapor-smk.net",
                "phone_number" => "081320199092",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2004-04-03",
                "address" => "KP. PASIR DOMAS rt 3 rw 6 Desa LANGENSARI Kec. Tarogong Kaler",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Ramdan Abdul Rahman",
                "nis" => "192010087",
                "nisn" => "0036167319",
                "email" => "hmojjh@erapor-smk.net",
                "phone_number" => "081320199093",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-02-07",
                "address" => "Kp. Cikahuripan rt 3 rw 5 Desa Pananjung Kec. Tarogong Kaler",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Ridwan Sunda Buana",
                "nis" => "192010089",
                "nisn" => "0043213956",
                "email" => "zllxie@erapor-smk.net",
                "phone_number" => "081320199094",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-08-18",
                "address" => "Kp. Lunjuk Hilir rt 2 rw 13 Desa Talagasari Kec. Kadungora",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Riski Hermawan",
                "nis" => "192010090",
                "nisn" => "0032841171",
                "email" => "i0m1jc@erapor-smk.net",
                "phone_number" => "081320199095",
                "gender" => "Laki-Laki",
                "birth_place" => "Bandung",
                "birth_date" => "2003-08-29",
                "address" => "Sangiang Lawang rt 1 rw 1 Desa Parakan Kec. Samarang",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Risna Fitriani",
                "nis" => "192010091",
                "nisn" => "0035887419",
                "email" => "f49sox@erapor-smk.net",
                "phone_number" => "081320199096",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2003-11-07",
                "address" => "Jl. Pataruman rt 3 rw 10 Desa PATARUMAN Kec. Tarogong Kidul",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Rizal Muhammad Yusup",
                "nis" => "192010092",
                "nisn" => "0045656440",
                "email" => "egwvoh@erapor-smk.net",
                "phone_number" => "081320199098",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-03-05",
                "address" => "CIMANUK rt 1 rw 6 Desa MUARASANDING Kec. Garut Kota",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Ropikin",
                "nis" => "192010094",
                "nisn" => "0037872354",
                "email" => "ewlyb1@erapor-smk.net",
                "phone_number" => "081320199099",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-01-27",
                "address" => "Kp. Cikatel rt 1 rw 5 Desa Pananjung Kec. Tarogong Kaler",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Sania Isnaini",
                "nis" => "192010096",
                "nisn" => "0047573414",
                "email" => "wfqs1q@erapor-smk.net",
                "phone_number" => "081320199100",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2004-06-14",
                "address" => "Kp. Balong Suci rt 2 rw 4 Desa Suci Kec. Karangpawitan",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Sendy Nabiel Adiyaksa",
                "nis" => "192010097",
                "nisn" => "0047151480",
                "email" => "41nev0@erapor-smk.net",
                "phone_number" => "081320199101",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-02-02",
                "address" => "Kp. Nagara Tengah rt 0 rw 0 Desa CIMANGANTEN Kec. Tarogong Kaler",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Sherly Mutia Septiani",
                "nis" => "192010485",
                "nisn" => "0046652880",
                "email" => "yr7mzu@erapor-smk.net",
                "phone_number" => "081320199102",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2003-09-02",
                "address" => "CIMANUK rt 1 rw 6 Desa MUARASANDING Kec. Garut Kota",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Siti Indriani",
                "nis" => "192010099",
                "nisn" => "0036852303",
                "email" => "stindri06@gmail.com",
                "phone_number" => "081320199103",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2003-05-06",
                "address" => "Nusa Indah No. 17 rt 2 rw 8 Desa Jayaraga Kec. Tarogong Kidul",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Tiwi Pebrianti",
                "nis" => "192010102",
                "nisn" => "0045087440",
                "email" => "ljhf2t@erapor-smk.net",
                "phone_number" => "081320199104",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-02-10",
                "address" => "Kp.Babakan rt 5 rw 3 Desa Sukakarya Kec. Banyuresmi",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Wava Angeli Salsabila",
                "nis" => "192010106",
                "nisn" => "0044379106",
                "email" => "wavasalsabila86@gmail.com",
                "phone_number" => "081320199105",
                "gender" => "Perempuan",
                "birth_place" => "Bandung",
                "birth_date" => "2004-04-02",
                "address" => "KP. CIKEMBANG RT. 001/005 rt 1 rw 25 Desa KOTA KULON Kec. Garut Kota",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "Yusri Rizqi",
                "nis" => "192010107",
                "nisn" => "0043613356",
                "email" => "yusriappolo@gmail.com",
                "phone_number" => "081320199106",
                "gender" => "Laki-Laki",
                "birth_place" => "Semarang",
                "birth_date" => "2004-08-17",
                "address" => "JL. GUNUNG SATRIA NO. 55 rt 0 rw 0 Desa KOTA KULON Kec. Garut Kota",
                "information" => "XII SIJ 2"
            ],
            [
                "name" => "ADRIAN SEPTI MAULANA",
                "nis" => "192010038",
                "nisn" => "0043715284",
                "email" => "0hoh0o@erapor-smk.net",
                "phone_number" => "081320297521",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-09-26",
                "address" => "Lame Rt 1 Rw 6 Sukakarya Kec. Samarang",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "ALIYA ZAHRATUN NISA RAHMA",
                "nis" => "192010042",
                "nisn" => "0039228461",
                "email" => "sssyg5@erapor-smk.net",
                "phone_number" => "081320297522",
                "gender" => "Perempuan",
                "birth_place" => "GARUT",
                "birth_date" => "2003-09-14",
                "address" => "KP. DESA KOLOT Rt 2 Rw 6 PASANGGRAHAN Kec. Sukawening",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "ARIF ZAENAL MUZACKY",
                "nis" => "192010046",
                "nisn" => "0042468168",
                "email" => "d0tzrp@erapor-smk.net",
                "phone_number" => "081320297523",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-03-19",
                "address" => "Jl. Pataruman Rt 1 Rw 10 Pataruman Kec. Tarogong Kidul",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "ASEP PATUROMAN",
                "nis" => "192010047",
                "nisn" => "0036860583",
                "email" => "hrtitg@erapor-smk.net",
                "phone_number" => "081320297524",
                "gender" => "Laki-Laki",
                "birth_place" => "GARUT",
                "birth_date" => "2003-08-02",
                "address" => "KP NAGROG Rt 2 Rw 6 SARIMUKTI Kec. Pasirwangi",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "AZHAR YAZID MANAN",
                "nis" => "192010048",
                "nisn" => "0037676158",
                "email" => "azharya21dmanan@gmail.com",
                "phone_number" => "081320297526",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-06-16",
                "address" => "Jl. Kh. Hasan Arief Rt 2 Rw 8 Sukasenang Kec. Banyuresmi",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Cahya Lesmana",
                "nis" => "192010049",
                "nisn" => "0031306319",
                "email" => "y9yxl0@erapor-smk.net",
                "phone_number" => "081320297527",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-11-04",
                "address" => "Kp. Cibogo Rt 2 Rw 9 Rancabango Kec. Tarogong Kaler",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Dewi Salma Paujiah",
                "nis" => "192010053",
                "nisn" => "0046400388",
                "email" => "paujiahsalma055@gmail.com",
                "phone_number" => "081320297528",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2004-07-08",
                "address" => "Kp. Lemahsari Rt 1 Rw 3 Situjaya Kec. Karangpawitan",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "DINA MARDIANA",
                "nis" => "192010055",
                "nisn" => "0039273582",
                "email" => "izljoh@erapor-smk.net",
                "phone_number" => "081320297531",
                "gender" => "Perempuan",
                "birth_place" => "GARUT",
                "birth_date" => "2003-09-30",
                "address" => "KP. NEGLA Rt 2 Rw 6 PANANJUNG Kec. Tarogong Kaler",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "DINI NURAHMI",
                "nis" => "192010057",
                "nisn" => "0048397513",
                "email" => "fh5qur@erapor-smk.net",
                "phone_number" => "081320297532",
                "gender" => "Perempuan",
                "birth_place" => "GARUT",
                "birth_date" => "2004-10-05",
                "address" => "JL. IBU NOCH KARTANEGARA Rt 1 Rw 11 SUKAMENTRI Kec. Garut Kota",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "FUJI ILAHAM MUDIN",
                "nis" => "192010060",
                "nisn" => "0023591236",
                "email" => "lnmlbj@erapor-smk.net",
                "phone_number" => "081320297533",
                "gender" => "Laki-Laki",
                "birth_place" => "GARUT",
                "birth_date" => "2002-03-21",
                "address" => "KP. SIRNA SARI Rt 2 Rw 7 SIRNA SARI Kec. Samarang",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Hasan Syauqi Muqtafi",
                "nis" => "192010062",
                "nisn" => "0048794620",
                "email" => "pjanar@erapor-smk.net",
                "phone_number" => "081320297534",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-06-10",
                "address" => "Perum Buana Residence Blok III No 11 A Rt 1 Rw 21 Margawati Kec. Garut Kota",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "HASBIAN NURJAMAN",
                "nis" => "192010063",
                "nisn" => "0036939740",
                "email" => "sitinailaa05@gmail.com",
                "phone_number" => "081320297535",
                "gender" => "Laki-Laki",
                "birth_place" => "GARUT",
                "birth_date" => "2003-02-04",
                "address" => "CIAKAR KOLOT Rt 2 Rw 6 MEKARJAYA Kec. Bayongbong",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "HASBY MOCH DIKA",
                "nis" => "192010064",
                "nisn" => "0045597857",
                "email" => "c4gxiq@erapor-smk.net",
                "phone_number" => "081320297536",
                "gender" => "Laki-Laki",
                "birth_place" => "garut",
                "birth_date" => "2004-09-20",
                "address" => "Jl. Karacak Situ Sari Rt 1 Rw 28 Koat Kulon Kec. Garut Kota",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "IHSAN YULANDANI",
                "nis" => "192010065",
                "nisn" => "0031408085",
                "email" => "o80uuk@erapor-smk.net",
                "phone_number" => "081320297537",
                "gender" => "Laki-Laki",
                "birth_place" => "GARUT",
                "birth_date" => "2003-01-06",
                "address" => "KP. KARANG MULYA RT/RW. 004/001 Rt 0 Rw 0 SUKAJAYA Kec. Tarogong Kidul",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Intan Aulia",
                "nis" => "192010069",
                "nisn" => "0048500275",
                "email" => "mhvepc@erapor-smk.net",
                "phone_number" => "081320297538",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2004-04-11",
                "address" => "Kp Sindangpalay Rt 2 Rw 5 Sindangpalay Kec. Karangpawitan",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "KEYSA SALSABILA",
                "nis" => "192010072",
                "nisn" => "0037928085",
                "email" => "ckda3m@erapor-smk.net",
                "phone_number" => "081320297539",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2003-12-09",
                "address" => "Jl. Perum bumi. Malayusri Blok no. 07 Rt 2 Rw 10 Mekar Wangi Kec. Tarogong Kaler",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "LUTFAN ASZA RAZAN",
                "nis" => "192010074",
                "nisn" => "3057416903",
                "email" => "ei1kd7@erapor-smk.net",
                "phone_number" => "081320297540",
                "gender" => "Laki-Laki",
                "birth_place" => "GARUT",
                "birth_date" => "2005-01-27",
                "address" => "Kp. Leuwidaun Rt 3 Rw 1 JAYAWARAS Kec. Tarogong Kidul",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "M Ramdan",
                "nis" => "192010081",
                "nisn" => "0036267303",
                "email" => "tejx9d@erapor-smk.net",
                "phone_number" => "081320297547",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-11-03",
                "address" => "Jln Pasirpogor Rt 5 Rw 5 Paminggir Kec. Garut Kota",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Mochammad Fadhilah Yanwar Kusumah",
                "nis" => "192010077",
                "nisn" => "0046038084",
                "email" => "fadhilkusumah23@gmail.com",
                "phone_number" => "081320297541",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-01-23",
                "address" => "Jl. Karacak No. 675 Rt 6 Rw 9 Kota Kulon Kec. Garut Kota",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Muhamad Triyan Budiansyah",
                "nis" => "192010078",
                "nisn" => "0042642485",
                "email" => "ea8iit@erapor-smk.net",
                "phone_number" => "081320297543",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-04-27",
                "address" => "Kp. Cileungsing Rt 3 Rw 10 Pasawahan Kec. Tarogong Kaler",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Muhammad Fajri Ghifari Salman",
                "nis" => "192010079",
                "nisn" => "0038793549",
                "email" => "muhammadfajri.gs@gmail.com",
                "phone_number" => "081320297544",
                "gender" => "Laki-Laki",
                "birth_place" => "Bandung",
                "birth_date" => "2003-11-04",
                "address" => "Jl. Adipati Agung Rt 1 Rw 17 Baleendah Kec. Baleendah",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Muhammad Ghesa Ramadhan",
                "nis" => "192010080",
                "nisn" => "0049983926",
                "email" => "oryoym@erapor-smk.net",
                "phone_number" => "081320297546",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-11-06",
                "address" => "Jl. Gunung Satria Rt 1 Rw 10 Kota Kulon Kec. Garut Kota",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Nicola Bagja Wijaya",
                "nis" => "192010084",
                "nisn" => "0049989021",
                "email" => "gmjyio@erapor-smk.net",
                "phone_number" => "081320297548",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-05-17",
                "address" => "Kmp Seni Kidul Rt 3 Rw 3 Jayawaras Kec. Tarogong Kidul",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "RAGIL IBRAHIM SALEH",
                "nis" => "192010086",
                "nisn" => "0035307264",
                "email" => "pzzgpr@erapor-smk.net",
                "phone_number" => "081320297549",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-07-07",
                "address" => "Kp. Kudangsari Rt 3 Rw 11 Rancabango Kec. Tarogong Kaler",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "RENDI HARNAWAN",
                "nis" => "192010088",
                "nisn" => "0042160668",
                "email" => "iykfia@erapor-smk.net",
                "phone_number" => "081320297550",
                "gender" => "Laki-Laki",
                "birth_place" => "GARUT",
                "birth_date" => "2004-03-17",
                "address" => "JL. DAYEUHANDAP Rt 4 Rw 5 KOTA KULON Kec. Garut Kota",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Rizki Muhamad Fizri",
                "nis" => "192010093",
                "nisn" => "0032487786",
                "email" => "rrokayah24@gmail.com",
                "phone_number" => "081320297552",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-05-03",
                "address" => "jL CIPARAY Rt 3 Rw 12 Tanjungsari Kec. Karangpawitan",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Sandi Setiadi",
                "nis" => "192010095",
                "nisn" => "0043363026",
                "email" => "wjf5bo@erapor-smk.net",
                "phone_number" => "081320297553",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-02-10",
                "address" => "Jl. Raya Samarang Rt 1 Rw 5 Cintarakyat Kec. Samarang",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "SHERLI STEFANNY",
                "nis" => "192010098",
                "nisn" => "0036248901",
                "email" => "26lkmo@erapor-smk.net",
                "phone_number" => "081320297554",
                "gender" => "Perempuan",
                "birth_place" => "GARUT",
                "birth_date" => "2003-09-01",
                "address" => "KP. HONJELUHUR Rt 4 Rw 9 SUKAGALIH Kec. Tarogong Kidul",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "Syauqi Izlal Ramadhan",
                "nis" => "192010100",
                "nisn" => "004327137",
                "email" => "ukisauki116@gmail.coom",
                "phone_number" => "081320297556",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2004-11-10",
                "address" => "Pasirpogor No 06 Rt 3 Rw 5 Paminggir Kec. Garut Kota",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "TEGAR SURYA MANDIRI",
                "nis" => "192010101",
                "nisn" => "0035929196",
                "email" => "gsamvf@erapor-smk.net",
                "phone_number" => "081320297557",
                "gender" => "Laki-Laki",
                "birth_place" => "GARUT",
                "birth_date" => "2003-12-25",
                "address" => "Kp. Sukasari Rt 1 Rw 5 Jatisari Kec. Karangpawitan",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "TRIANA NURHIDAYAH",
                "nis" => "192010103",
                "nisn" => "0045310463",
                "email" => "mofbuf@erapor-smk.net",
                "phone_number" => "081320297559",
                "gender" => "Perempuan",
                "birth_place" => "Garut",
                "birth_date" => "2004-03-25",
                "address" => "Kp. Campaka Rt 2 Rw 6 Suci Kec. Karangpawitan",
                "information" => "XII SIJ 1"
            ],
            [
                "name" => "WAHYUDI SAHADI",
                "nis" => "192010104",
                "nisn" => "0037342872",
                "email" => "wahyudisahadi289@gmail.com",
                "phone_number" => "081320297562",
                "gender" => "Laki-Laki",
                "birth_place" => "Garut",
                "birth_date" => "2003-06-12",
                "address" => "Bbk. Tanjung Rt 3 Rw 6 Pasawahan Kec. Tarogong Kidul",
                "information" => "XII SIJ 1"
            ]
        ];
        for ($i = 1; $i <= count($students); $i++) {
            Student::create([
                "name" => $students[$i - 1]["name"],
                "nisn" => $students[$i - 1]["nisn"],
                "nis" => $students[$i - 1]["nis"],
                "gender" => $students[$i - 1]['gender'],
                "birth_place" => $students[$i - 1]['birth_place'],
                "birth_date" => $students[$i - 1]['birth_date'],
                "address" => $students[$i - 1]['address'],
                "information" => $students[$i - 1]['information']
            ]);
            User::create([
                "student_id" => $i,
                "teacher_id" => null,
                "seller_id" => null,
                "teller_id" => null,
                "email" => $students[$i - 1]['email'],
                "phone_number" => $students[$i - 1]['phone_number'],
                "password" => Hash::make($students[$i - 1]['birth_date'])
            ]);
        }
    }
}