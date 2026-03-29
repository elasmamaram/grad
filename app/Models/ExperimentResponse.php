<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperimentResponse extends Model
{
    protected $fillable = [
        'participant_id',
        'video_key',
        'step_index',
        'condition',
        'seen_label',
        'real_or_fake',
        'ai_likelihood',
        'confidence_probability',
        'believability',
        'trust_label',
        'trust_platform',
        'clarity',
        'informed_engagement',
        'engagement_intent',
        'notes',
        'page_view_duration_ms',
        'decision_time_ms',
        'time_to_first_play_ms',
        'time_to_first_response_ms',
        'video_watch_time_ms',
        'video_watch_ratio_percent',
        'video_completion_percent',
        'play_count',
        'pause_count',
        'ended_count',
        'seek_count',
        'seek_forward_count',
        'seek_backward_count',
        'rewatch_count',
        'total_seek_distance_ms',
        'focus_loss_count',
        'focus_loss_duration_ms',
        'fullscreen_count',
        'volume_change_count',
        'playback_rate_change_count',
        'form_interaction_count',
        'answer_change_count',
        'submit_attempt_count',
    ];

    protected function casts(): array
    {
        return [
            'seen_label' => 'boolean',
            'video_watch_ratio_percent' => 'decimal:2',
            'video_completion_percent' => 'decimal:2',
        ];
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }
}
