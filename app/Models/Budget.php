<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'limit_amount',
        'color',
        'used',
        'user_id', // Added user_id to allow mass assignment
    ];

    /**
     * Get the user that owns the budget.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Optional: Helper to calculate remaining balance
     */
    public function getRemainingAttribute()
    {
        return $this->limit_amount - $this->used;
    }

    /**
     * Optional: Helper to calculate percentage used
     */
    public function getPercentAttribute()
    {
        if ($this->limit_amount <= 0) return 0;
        return ($this->used / $this->limit_amount) * 100;
    }
}