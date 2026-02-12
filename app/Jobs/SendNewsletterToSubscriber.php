<?php

namespace App\Jobs;

use App\Mail\WeeklyNewsletter;
use App\Models\Newsletter;
use App\Models\NewsletterEdition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterToSubscriber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public NewsletterEdition $edition,
        public string $subscriberEmail
    ) {}

    public function handle(): void
    {
        $subscriber = Newsletter::where('email', $this->subscriberEmail)->where('status', 'active')->first();
        if (! $subscriber) {
            return;
        }

        Mail::to($this->subscriberEmail)->send(new WeeklyNewsletter($this->edition, $this->subscriberEmail));
    }
}
