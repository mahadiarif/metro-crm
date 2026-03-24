<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'icon'];

    public function packages(): HasMany
    {
        return $this->hasMany(ServicePackage::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
