<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = [
            [
                'name' => 'player',
                'display_name' => 'Player',
                'name_color' => '<gray>',
                'priority' => 0,
            ],
            [
                'name' => 'vip',
                'display_name' => 'VIP',
                'name_color' => '<light_purple>',
                'priority' => 5,
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Moderator',
                'name_color' => '<green>',
                'priority' => 50,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'name_color' => '<red>',
                'priority' => 90,
            ],
            [
                'name' => 'owner',
                'display_name' => 'Owner',
                'name_color' => '<red>',
                'priority' => 100,
            ]
        ];

        DB::table('ranks')->upsert(
            array_map(fn ($rank) => [
                ...$rank,
                'created_at' => now(),
                'updated_at' => now(),
            ], $ranks),
            ['name'],
            ['display_name', 'name_color', 'priority', 'updated_at']
        );
    }
}
