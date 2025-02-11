<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class result_type extends Model
{
    /** @use HasFactory<\Database\Factories\ResultTypeFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'type'
    ];
}
