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
            $table->string('real_or_fake', 8)->nullable()->after('seen_label');
            $table->unsignedTinyInteger('ai_likelihood')->nullable()->after('real_or_fake');
            $table->unsignedTinyInteger('confidence_probability')->nullable()->after('ai_likelihood');
            $table->unsignedTinyInteger('information_credibility')->nullable()->after('confidence_probability');
            $table->unsignedTinyInteger('trust_platform')->nullable()->after('trust_label');
            $table->unsignedTinyInteger('informed_engagement')->nullable()->after('clarity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiment_responses', function (Blueprint $table) {
            $table->dropColumn([
                'real_or_fake',
                'ai_likelihood',
                'confidence_probability',
                'information_credibility',
                'trust_platform',
                'informed_engagement',
            ]);
        });
    }
};
