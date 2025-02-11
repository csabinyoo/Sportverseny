<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class team_at_station extends Model
{
    /** @use HasFactory<\Database\Factories\TeamAtStationFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'teamId',
        'stationId'
    ];

}
