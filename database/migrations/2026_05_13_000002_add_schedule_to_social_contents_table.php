<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Change content_type from NOT NULL enum to nullable varchar
        DB::statement("ALTER TABLE social_contents MODIFY COLUMN content_type VARCHAR(60) NULL DEFAULT NULL");

        Schema::table('social_contents', function (Blueprint $table) {
            $table->string('custom_description', 255)->nullable()->after('content_type');
            $table->date('scheduled_date')->nullable()->after('custom_description');
            $table->index('scheduled_date');
        });
    }

    public function down(): void
    {
        Schema::table('social_contents', function (Blueprint $table) {
            $table->dropIndex(['scheduled_date']);
            $table->dropColumn(['custom_description', 'scheduled_date']);
        });

        DB::statement("ALTER TABLE social_contents MODIFY COLUMN content_type ENUM('story','player_feature','anime_version','exercise_champion','carousel') NOT NULL");
    }
};
