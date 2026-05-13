<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->boolean('is_frozen')->default(false)->after('status');
            $table->date('frozen_at')->nullable()->after('is_frozen');
            $table->unsignedInteger('frozen_days_remaining')->nullable()->after('frozen_at');
            $table->text('freeze_note')->nullable()->after('frozen_days_remaining');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['is_frozen', 'frozen_at', 'frozen_days_remaining', 'freeze_note']);
        });
    }
};
