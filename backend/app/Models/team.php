<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'competitionId',
        'name',
        'school',
        'userId'
    ];
}
