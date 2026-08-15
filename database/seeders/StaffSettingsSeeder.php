<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'setting' => 'all_connections',
                'min_priority' => 90,
            ],
            [
                'setting' => 'staff_connections',
                'min_priority' => 50,
            ],
            [
                'setting' => 'all_server_switches',
                'min_priority' => 90,
            ],
            [
                'setting' => 'staff_server_switches',
                'min_priority' => 50,
            ],
            [
                'setting' => 'keep_inventory_on_join',
                'min_priority' => 50,
            ]
        ];

        DB::table('staff_settings')->upsert(
            array_map(fn ($setting) => [
                ...$setting,
                'created_at' => now(),
                'updated_at' => now(),
            ], $settings),
            ['setting'],
            ['min_priority', 'updated_at']
        );
    }
}
