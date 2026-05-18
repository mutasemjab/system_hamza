<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature   = 'subscriptions:expire';
    protected $description = 'Mark subscriptions whose end_date has passed as expired';

    public function handle(): int
    {
        $today = Carbon::today();

        $updated = Subscription::query()
            ->where('is_frozen', false)
            ->whereNotIn('status', ['expired'])
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', $today)
            ->update(['status' => 'expired']);

        $this->info("Expired {$updated} subscription(s) as of {$today->toDateString()}.");

        return Command::SUCCESS;
    }
}
