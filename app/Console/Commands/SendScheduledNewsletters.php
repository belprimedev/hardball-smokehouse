<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendScheduledNewsletters extends Command
{
    protected $signature = 'newsletter:send-scheduled';

    protected $description = 'Send newsletter editions that are scheduled for now or earlier';

    public function handle(): int
    {
        $editions = \App\Models\NewsletterEdition::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at')
            ->get();

        if ($editions->isEmpty()) {
            $this->info('No scheduled editions due for sending.');
            return self::SUCCESS;
        }

        foreach ($editions as $edition) {
            $this->info("Sending scheduled edition ID {$edition->id}: {$edition->subject}");
            $this->call('newsletter:send', ['edition' => $edition->id]);
        }
        return self::SUCCESS;
    }
}
