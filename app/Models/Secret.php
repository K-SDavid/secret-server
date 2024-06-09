<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'hash';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'hash',
        'secretText',
        'createdAt',
        'expiresAt',
        'remainingViews',
    ];

    protected $casts = [
        'createdAt' => 'datetime:Y-m-d\TH:i:s.v\Z',
        'expiresAt' => 'datetime:Y-m-d\TH:i:s.v\Z',
    ];
}
