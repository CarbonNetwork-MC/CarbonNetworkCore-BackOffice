<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table(
    key: 'player_uuid',
    keyType: 'string',
    incrementing: false,
)]
#[Fillable([
    'player_uuid',
    'gamemode',
    'server_name',
    'world',
    'x',
    'y',
    'z',
    'yaw',
    'pitch',
])]
class PlayerLocation extends Model
{
    /**
     * Scope a query to the model's composite database identity.
     */
    protected function setKeysForSelectQuery($query)
    {
        return $query
            ->where('player_uuid', $this->getAttribute('player_uuid'))
            ->where('gamemode', $this->getAttribute('gamemode'));
    }

    /**
     * Apply both primary-key columns when updating an existing location.
     */
    protected function setKeysForSaveQuery($query)
    {
        return $this->setKeysForSelectQuery($query);
    }
}
