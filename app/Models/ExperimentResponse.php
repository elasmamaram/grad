<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperimentResponse extends Model
{
    protected $fillable = [
        'participant_id',
        'video_key',
        'actual_source',
        'step_index',
        'condition',
        'seen_label',
        'real_or_fake',
        'answer_is_correct',
        'ai_likelihood',
        'confidence_probability',
        'believability',
        'information_credibility',
        'trust_label',
        'trust_platform',
        'clarity',
        'informed_engagement',
        'engagement_intent',
        'notes',
        'decision_time_ms',
        'video_watch_ratio_percent',
        'pause_count',
        'rewatch_count',
    ];

    protected function casts(): array
    {
        return [
            'seen_label' => 'boolean',
            'answer_is_correct' => 'boolean',
            'video_watch_ratio_percent' => 'decimal:2',
        ];
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }
}
