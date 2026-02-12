<?php

namespace App\Console\Commands;

use App\Jobs\SendNewsletterToSubscriber;
use App\Models\Newsletter;
use App\Models\NewsletterEdition;
use Illuminate\Console\Command;

class SendNewsletterEdition extends Command
{
    protected $signature = 'newsletter:send {edition : The newsletter edition ID}';

    protected $description = 'Send a newsletter edition to all active subscribers';

    public function handle(): int
    {
        $edition = NewsletterEdition::find($this->argument('edition'));
        if (! $edition) {
            $this->error('Newsletter edition not found.');
            return self::FAILURE;
        }
        if (! $edition->isSendable()) {
            $this->error('This edition has already been sent.');
            return self::FAILURE;
        }

        $subscribers = Newsletter::where('status', 'active')->pluck('email');
        $count = $subscribers->count();
        if ($count === 0) {
            $this->warn('No active subscribers.');
            return self::SUCCESS;
        }

        $this->info("Queueing {$count} emails for edition: {$edition->subject}");
        foreach ($subscribers as $email) {
            SendNewsletterToSubscriber::dispatch($edition, $email);
        }
        $edition->markAsSent();
        $this->info('Edition marked as sent. Emails are being sent via the queue.');
        return self::SUCCESS;
    }
}
