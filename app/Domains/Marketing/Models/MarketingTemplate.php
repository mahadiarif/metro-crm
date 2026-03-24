<?php

namespace App\Domains\Marketing\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MarketingTemplate extends Model
{
    protected $fillable = [
        'name',
        'type',
        'content',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
