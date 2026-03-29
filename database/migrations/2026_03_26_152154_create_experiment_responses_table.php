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
        Schema::create('experiment_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->string('video_key');
            $table->unsignedTinyInteger('step_index');
            $table->string('condition', 32);
            $table->boolean('seen_label')->default(false);
            $table->unsignedTinyInteger('believability');
            $table->unsignedTinyInteger('trust_label')->nullable();
            $table->unsignedTinyInteger('clarity')->nullable();
            $table->unsignedTinyInteger('engagement_intent');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['participant_id', 'step_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiment_responses');
    }
};
