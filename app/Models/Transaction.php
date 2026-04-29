<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Added 'user_id' to the fillable array to allow saving the transaction owner
    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'type',
    ];
}