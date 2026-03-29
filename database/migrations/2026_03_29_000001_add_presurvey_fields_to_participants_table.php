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
        Schema::table('participants', function (Blueprint $table) {
            $table->string('consent_answer', 8)->nullable()->after('preferred_language');
            $table->string('age_18', 8)->nullable()->after('consent_answer');
            $table->string('reside_libya', 8)->nullable()->after('age_18');
            $table->string('internet_regular', 8)->nullable()->after('reside_libya');
            $table->string('heard_deepfake', 8)->nullable()->after('internet_regular');
            $table->string('age_group', 16)->nullable()->after('heard_deepfake');
        });

        DB::table('participants')->update([
            'consent_answer' => 'yes',
            'age_18' => 'yes',
            'reside_libya' => 'yes',
            'internet_regular' => 'yes',
            'heard_deepfake' => 'yes',
            'age_group' => '18-24',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn([
                'consent_answer',
                'age_18',
                'reside_libya',
                'internet_regular',
                'heard_deepfake',
                'age_group',
            ]);
        });
    }
};
