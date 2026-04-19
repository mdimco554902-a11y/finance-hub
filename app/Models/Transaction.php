<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // This "fillable" property tells Laravel these fields are safe to save
    protected $fillable = [
        'title',
        'amount',
        'type',
    ];
}
