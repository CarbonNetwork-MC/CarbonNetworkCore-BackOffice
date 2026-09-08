<?php

namespace App\Http\Controllers;

use App\Models\PlayerLocation;
use Illuminate\Http\Request;

class PlayerLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['message' => 'Not supported'], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'player_uuid' => ['required', 'uuid', 'exists:players,uuid'],
            'gamemode' => ['required', 'string', 'max:64', 'regex:/^[a-z0-9][a-z0-9_-]*$/'],
            'server_name' => ['required', 'string', 'max:255'],
            'world' => ['required', 'string', 'max:255'],
            'x' => ['required', 'numeric'],
            'y' => ['required', 'numeric'],
            'z' => ['required', 'numeric'],
            'yaw' => ['required', 'numeric'],
            'pitch' => ['required', 'numeric', 'between:-90,90'],
        ]);

        $playerLocation = PlayerLocation::query()->updateOrCreate(
            [
                'player_uuid' => $validated['player_uuid'],
                'gamemode' => $validated['gamemode'],
            ],
            [
                'server_name' => $validated['server_name'],
                'world' => $validated['world'],
                'x' => $validated['x'],
                'y' => $validated['y'],
                'z' => $validated['z'],
                'yaw' => $validated['yaw'],
                'pitch' => $validated['pitch'],
            ],
        );

        return response()->json(
            ['message' => 'Player location saved'],
            $playerLocation->wasRecentlyCreated ? 201 : 200,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $gamemode, string $uuid)
    {
        $playerLocation = PlayerLocation::where('player_uuid', $uuid)->where('gamemode', $gamemode)->first(['server_name']);

        if (! $playerLocation) {
            return response()->json(['message' => 'Player location not found'], 404);
        }

        return response()->json($playerLocation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        return response()->json(['message' => 'Not supported'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        return response()->json(['message' => 'Not supported'], 405);
    }
}
