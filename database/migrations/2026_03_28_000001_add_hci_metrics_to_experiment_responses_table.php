<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('experiment_responses', function (Blueprint $table) {
            $table->unsignedBigInteger('page_view_duration_ms')->nullable()->after('notes');
            $table->unsignedBigInteger('decision_time_ms')->nullable()->after('page_view_duration_ms');
            $table->unsignedBigInteger('time_to_first_play_ms')->nullable()->after('decision_time_ms');
            $table->unsignedBigInteger('time_to_first_response_ms')->nullable()->after('time_to_first_play_ms');
            $table->unsignedBigInteger('video_watch_time_ms')->nullable()->after('time_to_first_response_ms');
            $table->decimal('video_watch_ratio_percent', 7, 2)->nullable()->after('video_watch_time_ms');
            $table->decimal('video_completion_percent', 7, 2)->nullable()->after('video_watch_ratio_percent');
            $table->unsignedInteger('play_count')->nullable()->after('video_completion_percent');
            $table->unsignedInteger('pause_count')->nullable()->after('play_count');
            $table->unsignedInteger('ended_count')->nullable()->after('pause_count');
            $table->unsignedInteger('seek_count')->nullable()->after('ended_count');
            $table->unsignedInteger('seek_forward_count')->nullable()->after('seek_count');
            $table->unsignedInteger('seek_backward_count')->nullable()->after('seek_forward_count');
            $table->unsignedInteger('rewatch_count')->nullable()->after('seek_backward_count');
            $table->unsignedBigInteger('total_seek_distance_ms')->nullable()->after('rewatch_count');
            $table->unsignedInteger('focus_loss_count')->nullable()->after('total_seek_distance_ms');
            $table->unsignedBigInteger('focus_loss_duration_ms')->nullable()->after('focus_loss_count');
            $table->unsignedInteger('fullscreen_count')->nullable()->after('focus_loss_duration_ms');
            $table->unsignedInteger('volume_change_count')->nullable()->after('fullscreen_count');
            $table->unsignedInteger('playback_rate_change_count')->nullable()->after('volume_change_count');
            $table->unsignedInteger('form_interaction_count')->nullable()->after('playback_rate_change_count');
            $table->unsignedInteger('answer_change_count')->nullable()->after('form_interaction_count');
            $table->unsignedInteger('submit_attempt_count')->nullable()->after('answer_change_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiment_responses', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
