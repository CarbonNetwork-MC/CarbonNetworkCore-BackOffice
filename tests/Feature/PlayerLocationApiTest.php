<?php

use App\Models\PlayerLocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

it('rejects unauthenticated player location requests', function () {
    $this->getJson('/api/player-location/cities/'.Str::uuid())
        ->assertUnauthorized();
});

it('rejects tokens without the player location read ability', function () {
    $user = User::factory()->create(['uuid' => Str::uuid()]);
    $token = $user->createToken('test-server', ['other:read'])->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/player-location/cities/'.Str::uuid())
        ->assertForbidden();
});

it('returns a player location to an authorized server', function () {
    $uuid = (string) Str::uuid();
    $user = User::factory()->create(['uuid' => Str::uuid()]);
    $token = $user->createToken('test-server', ['player-location:read'])->plainTextToken;

    $rankId = DB::table('ranks')->insertGetId([
        'name' => 'default',
        'display_name' => 'Default',
        'name_color' => '#ffffff',
        'priority' => 0,
    ]);

    DB::table('players')->insert([
        'uuid' => $uuid,
        'username' => 'TestPlayer',
        'rank_id' => $rankId,
    ]);

    PlayerLocation::query()->create([
        'player_uuid' => $uuid,
        'gamemode' => 'cities',
        'server_name' => 'cities-1',
        'world' => 'world',
        'x' => 1,
        'y' => 64,
        'z' => 1,
        'yaw' => 0,
        'pitch' => 0,
    ]);

    $this->withToken($token)
        ->getJson('/api/player-location/cities/'.$uuid)
        ->assertOk()
        ->assertExactJson(['server_name' => 'cities-1']);
});

it('returns the location for the requested gamemode', function () {
    $uuid = (string) Str::uuid();
    $user = User::factory()->create(['uuid' => Str::uuid()]);
    $token = $user->createToken('test-server', ['player-location:read'])->plainTextToken;
    $rankId = DB::table('ranks')->insertGetId([
        'name' => 'default',
        'display_name' => 'Default',
        'name_color' => '#ffffff',
        'priority' => 0,
    ]);

    DB::table('players')->insert([
        'uuid' => $uuid,
        'username' => 'TestPlayer',
        'rank_id' => $rankId,
    ]);

    foreach (['cities' => 'cities-1', 'survival' => 'survival-1'] as $gamemode => $serverName) {
        PlayerLocation::query()->create([
            'player_uuid' => $uuid,
            'gamemode' => $gamemode,
            'server_name' => $serverName,
            'world' => 'world',
            'x' => 1,
            'y' => 64,
            'z' => 1,
            'yaw' => 0,
            'pitch' => 0,
        ]);
    }

    $this->withToken($token)
        ->getJson('/api/player-location/cities/'.$uuid)
        ->assertOk()
        ->assertExactJson(['server_name' => 'cities-1']);

    $this->withToken($token)
        ->getJson('/api/player-location/survival/'.$uuid)
        ->assertOk()
        ->assertExactJson(['server_name' => 'survival-1']);
});

it('updates and deletes locations using the composite identity', function () {
    $uuid = (string) Str::uuid();
    $rankId = DB::table('ranks')->insertGetId([
        'name' => 'default',
        'display_name' => 'Default',
        'name_color' => '#ffffff',
        'priority' => 0,
    ]);

    DB::table('players')->insert([
        'uuid' => $uuid,
        'username' => 'TestPlayer',
        'rank_id' => $rankId,
    ]);

    foreach (['cities' => 'cities-1', 'survival' => 'survival-1'] as $gamemode => $serverName) {
        PlayerLocation::query()->create([
            'player_uuid' => $uuid,
            'gamemode' => $gamemode,
            'server_name' => $serverName,
            'world' => 'world',
            'x' => 1,
            'y' => 64,
            'z' => 1,
            'yaw' => 0,
            'pitch' => 0,
        ]);
    }

    $survivalLocation = PlayerLocation::query()
        ->where('player_uuid', $uuid)
        ->where('gamemode', 'survival')
        ->firstOrFail();

    $survivalLocation->server_name = 'survival-2';
    $survivalLocation->save();

    expect(PlayerLocation::query()
        ->where('player_uuid', $uuid)
        ->where('gamemode', 'cities')
        ->value('server_name'))->toBe('cities-1');

    $survivalLocation->delete();

    expect(PlayerLocation::query()->where('player_uuid', $uuid)->count())->toBe(1)
        ->and(PlayerLocation::query()
            ->where('player_uuid', $uuid)
            ->where('gamemode', 'cities')
            ->exists())->toBeTrue();
});

it('returns not found when an authorized server requests an unknown player', function () {
    $user = User::factory()->create(['uuid' => Str::uuid()]);
    $token = $user->createToken('test-server', ['player-location:read'])->plainTextToken;

    $this->withToken($token)
        ->getJson('/api/player-location/cities/'.Str::uuid())
        ->assertNotFound()
        ->assertExactJson(['message' => 'Player location not found']);
});
