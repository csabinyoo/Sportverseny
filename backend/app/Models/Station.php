<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    /** @use HasFactory<\Database\Factories\StationFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'location',
        'weighting',
        'moreIsBetter',
        'typeId',
        'userId',
        'competitionId'
    ];
}
