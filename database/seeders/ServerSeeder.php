<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServerSeeder extends Seeder
{
    private const array SERVERS = [
        [
            'name' => 'Cities Gamemode',
            'gamemode' => 'cities',
            'servers' => [
                [
                    'name' => 'dev3',
                    'ip_address' => '127.0.0.1:11002',
                ],
                [
                    'name' => 'dev4',
                    'ip_address' => '127.0.0.1:11003',
                ],
                [
                    'name' => 'cities-onboarding',
                    'ip_address' => '127.0.0.1:12201',
                ]
            ]
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->error('ServerSeeder should not be run in production environment.');
            return;
        }

        foreach (self::SERVERS as $clusterData) {
            $cluster = \App\Models\ServerCluster::create([
                'name' => $clusterData['name'],
                'gamemode' => $clusterData['gamemode'] ?? null,
            ]);

            foreach ($clusterData['servers'] as $serverData) {
                $cluster->servers()->create($serverData);
            }
        }
    }
}
