<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PipelineStage extends Model
{
    protected $fillable = ['name', 'slug', 'order_column', 'color', 'win_probability', 'is_active'];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'stage_id');
    }
}
