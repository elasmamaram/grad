<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('experiment_responses', function (Blueprint $table) {
            $table->string('actual_source', 8)->nullable()->after('video_key');
            $table->boolean('answer_is_correct')->nullable()->after('real_or_fake');
        });

        $map = [
            'video_1' => 'fake',
            'video_2' => 'fake',
            'video_3' => 'real',
            'video_4' => 'real',
        ];

        foreach ($map as $videoKey => $actualSource) {
            DB::table('experiment_responses')
                ->where('video_key', $videoKey)
                ->update([
                    'actual_source' => $actualSource,
                    'answer_is_correct' => DB::raw("CASE WHEN real_or_fake = '{$actualSource}' THEN 1 ELSE 0 END"),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiment_responses', function (Blueprint $table) {
            $table->dropColumn([
                'actual_source',
                'answer_is_correct',
            ]);
        });
    }
};
