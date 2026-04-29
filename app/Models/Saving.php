<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    // This allows these fields to be filled by your forms
    protected $fillable = ['user_id', 'title', 'target_amount', 'current_amount', 'color'];

    // This calculates the percentage for your progress bars automatically
    public function getPercentageAttribute()
    {
        if ($this->target_amount <= 0) return 0;
        return round(($this->current_amount / $this->target_amount) * 100);
    }
}