<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old typed/board entries that have no scheduled date
        DB::statement("DELETE FROM social_contents WHERE scheduled_date IS NULL");

        // Normalise any 'next' status rows to 'pending'
        DB::statement("UPDATE social_contents SET status = 'pending' WHERE status = 'next'");

        // Change status column from enum to varchar so we can freely add values
        DB::statement("ALTER TABLE social_contents MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending'");

        Schema::table('social_contents', function (Blueprint $table) {
            // content_type and sort_order are no longer needed
            $table->dropColumn(['content_type', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('social_contents', function (Blueprint $table) {
            $table->string('content_type', 60)->nullable()->after('player_id');
            $table->unsignedInteger('sort_order')->default(0)->after('notes');
        });
    }
};
