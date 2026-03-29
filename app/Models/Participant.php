<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    protected $fillable = [
        'public_token',
        'condition',
        'preferred_language',
        'consent_answer',
        'age_18',
        'reside_libya',
        'internet_regular',
        'heard_deepfake',
        'age_group',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ExperimentResponse::class);
    }
}
