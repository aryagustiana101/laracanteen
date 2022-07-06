<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'name' => 'Pesanan Dibuat Sistem',
                'position' => 'first',
                'result' => 'success-user'
            ],
            [
                'name' => 'Pesanan Dibatalkan Pengguna',
                'position' => 'second',
                'result' => 'failed-user'
            ],
            [
                'name' => 'Pesanan Ditolak Toko',
                'position' => 'second',
                'result' => 'failed-seller'
            ],
            [
                'name' => 'Pesanan Diterima Toko',
                'position' => 'second',
                'result' => 'success-seller'
            ],
            [
                'name' => 'Pesanan Sedang Disiapkan',
                'position' => 'third',
                'result' => 'success-seller'
            ],
            [
                'name' => 'Pesanan Gagal Disiapkan',
                'position' => 'fourth',
                'result' => 'failed-seller'
            ],
            [
                'name' => 'Pesanan Berhasil Disiapkan',
                'position' => 'fourth',
                'result' => 'success-seller'
            ],
            [
                'name' => 'Pesanan Siap Dibayar',
                'position' => 'fifth',
                'result' => 'success-seller'
            ],
            [
                'name' => 'Pesanan Hangus Tidak Dibayar',
                'position' => 'sixth',
                'result' => 'failed-user'
            ],
            [
                'name' => 'Pesanan Sudah Dibayar',
                'position' => 'sixth',
                'result' => 'success-user'
            ],
            [
                'name' => 'Pesanan Siap Diambil',
                'position' => 'seventh',
                'result' => 'success-seller'
            ],
            [
                'name' => 'Pesanan Diasumsikan Sudah Diambil',
                'position' => 'eighth',
                'result' => 'success-seller'
            ],
            [
                'name' => 'Pesanan Sudah Diambil',
                'position' => 'eighth',
                'result' => 'success-seller'
            ],
            [
                'name' => 'Pesanan Batal',
                'position' => 'end',
                'result' => 'failed-user'
            ],
            [
                'name' => 'Pesanan Batal',
                'position' => 'end',
                'result' => 'failed-seller'
            ],
            [
                'name' => 'Pesanan Selesai',
                'position' => 'end',
                'result' => 'success-seller'
            ]
        ];
        for ($i = 1; $i <= count($statuses); $i++) {
            Status::create([
                'name' => $statuses[$i - 1]['name'],
                'position' => $statuses[$i - 1]['position'],
                'result' => $statuses[$i - 1]['result']
            ]);
        }
    }
}
