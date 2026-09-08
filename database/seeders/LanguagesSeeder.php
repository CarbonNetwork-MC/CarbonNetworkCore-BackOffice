<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'name' => 'English',
                'shortcode' => 'en',
                'code' => 'en_US',
                'headdb_id' => 890,
            ],
            [
                'name' => 'Dutch',
                'shortcode' => 'nl',
                'code' => 'nl_NL',
                'headdb_id' => 17422,
            ]
        ];

        DB::table('languages')->upsert(
            array_map(fn ($language) => [
                ...$language,
                'created_at' => now(),
                'updated_at' => now(),
            ], $languages),
            ['shortcode'],
            ['name', 'code', 'headdb_id', 'updated_at']
        );
    }
}
