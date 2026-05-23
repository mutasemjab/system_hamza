<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_contents', function (Blueprint $table) {
            // Drop columns that were added in earlier migrations but are no longer used
            if (Schema::hasColumn('social_contents', 'scheduled_date')) {
                $table->dropIndex(['scheduled_date']);
                $table->dropColumn('scheduled_date');
            }

            // Normalise custom_description to NOT NULL if still nullable
            if (Schema::hasColumn('social_contents', 'custom_description')) {
                $table->string('custom_description', 255)->nullable(false)->change();
            }

            // content_type and sort_order were already dropped in a previous migration;
            // this guard handles any environment where that migration did not run.
            $drops = array_filter(
                ['content_type', 'sort_order'],
                fn($col) => Schema::hasColumn('social_contents', $col)
            );
            if ($drops) {
                $table->dropColumn(array_values($drops));
            }
        });
    }

    public function down(): void
    {
        Schema::table('social_contents', function (Blueprint $table) {
            $table->date('scheduled_date')->nullable()->after('custom_description');
            $table->index('scheduled_date');
        });
    }
};
