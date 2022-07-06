<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\Setting;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'name' => 'Open Transaction',
                'values' => '0',
                'details' => 'Boolean value.'
            ],
            [
                'name' => 'Admin Fee Percentage',
                'values' => '5',
                'details' => 'In percent.'
            ],
            [
                'name' => 'Tax Fee Percentage',
                'values' => '10',
                'details' => 'In percent.'
            ],
            [
                'name' => 'Take Time Limit',
                'values' => '2',
                'details' => 'In minute.'
            ],
            [
                'name' => 'Prepare Time Limit',
                'values' => '60',
                'details' => 'In minute.'
            ],
            [
                'name' => 'Pay Time Limit',
                'values' => '30',
                'details' => 'In minute.'
            ],
            [
                'name' => 'Give Time Limit',
                'values' => '30',
                'details' => 'In minute.'
            ],
            [
                'name' => 'Payment QR Code Content',
                'values' => '/payment/$/pay',
                'details' => 'Content for QR code in payment group.'
            ]
        ];
        for ($i = 1; $i <= count($settings); $i++) {
            Setting::create([
                "name" => $settings[$i - 1]["name"],
                "values" => $settings[$i - 1]["values"],
                "details" => $settings[$i - 1]["details"],
            ]);
        }
    }
}
