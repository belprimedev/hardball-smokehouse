<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterUnsubscribeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = Newsletter::where('email', $request->email)->first();
        if ($subscriber) {
            $subscriber->update(['status' => 'unsubscribed']);
        }

        return view('newsletter.unsubscribed');
    }
}
