<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'principal_email',
                'value' => 'prasathragul75@gmail.com',
                'description' => 'The email address that receives booking requests for initial approval.'
            ],
            [
                'key' => 'mail_password',
                'value' => 'wnzt bweh qwvk gtbu',
                'description' => 'The Gmail App Password used for sending system emails.'
            ],
            [
                'key' => 'sender_email',
                'value' => 'prasathragul75@gmail.com',
                'description' => 'The system email used for sending notifications.'
            ],
            [
                'key' => 'hod_email',
                'value' => 'unfortunately2909@gmail.com',
                'description' => 'The HOD email address for student non-residence booking approvals.'
            ],
            [
                'key' => 'hall_warden_email',
                'value' => 'praveenrock2609@gmail.com',
                'description' => 'The Hall Warden email address for student residence booking approvals.'
            ]
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
