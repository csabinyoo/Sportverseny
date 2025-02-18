<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'date',
        'location',
        'registerFrom',
        'registerTo'
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'registerFrom' => 'date:Y-m-d',
            'registerTo' => 'date:Y-m-d'
        ];
    }
}
