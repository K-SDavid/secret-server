<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'hash',
        'secretText',
        'createdAt',
        'expiresAt',
        'remainingViews',
    ];

    // Get the datetime in the 'Y-m-d\TH:i:s.v\Z' format
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i:s.v\Z');
    }

    // Mutator to store the datetime in the correct format
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['createdAt'] = Carbon::parse($value)->format('Y-m-d H:i:s.v');
    }

    // Get the datetime in the 'Y-m-d\TH:i:s.v\Z' format
    public function getExpiresAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i:s.v\Z');
    }

    // Mutator to store the datetime in the correct format
    public function setExpiresAtAttribute($value)
    {
        $this->attributes['expiresAt'] = Carbon::parse($value)->format('Y-m-d H:i:s.v');
    }
}
