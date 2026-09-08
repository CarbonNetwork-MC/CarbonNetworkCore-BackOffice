<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'server_cluster_id',
    'name',
    'ip_address',
])]
class Server extends Model
{
    //
}
