<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class team_member extends Model
{
    /** @use HasFactory<\Database\Factories\TeamMemberFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'teamId',
        'name',
        'captain'
    ];

    protected function casts(): array
    {
        return [
            'captain' => 'boolean',
        ];
    }
}
