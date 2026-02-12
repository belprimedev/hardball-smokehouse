<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendNewsletterToSubscriber;
use App\Mail\WeeklyNewsletter;
use App\Models\Newsletter;
use App\Models\NewsletterEdition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class NewsletterEditionController extends Controller
{
    public function index(): Response
    {
        $perPage = request()->get('per_page', 10);
        $editions = NewsletterEdition::orderBy('created_at', 'desc')->paginate($perPage);

        return Inertia::render('Admin/NewsletterEditions/Index', [
            'editions' => $editions,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/NewsletterEditions/Create', [
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'status' => 'required|in:draft,scheduled',
        ]);

        try {
            $edition = NewsletterEdition::create([
                'subject' => $request->subject,
                'body' => $request->body,
                'scheduled_at' => $request->scheduled_at ? $request->date('scheduled_at') : null,
                'status' => $request->status,
            ]);
            $message = $request->status === 'scheduled'
                ? 'Newsletter edition scheduled successfully.'
                : 'Newsletter edition created as draft.';
            return redirect()->route('admin.newsletter-editions.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Newsletter edition creation failed: '.$e->getMessage());
            return back()->withErrors(['error' => 'Failed to create newsletter edition.']);
        }
    }

    public function edit(NewsletterEdition $newsletter_edition): Response
    {
        return Inertia::render('Admin/NewsletterEditions/Edit', [
            'edition' => $newsletter_edition,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function update(Request $request, NewsletterEdition $newsletter_edition): RedirectResponse
    {
        if ($newsletter_edition->status === 'sent') {
            return back()->withErrors(['error' => 'Sent editions cannot be edited.']);
        }
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'status' => 'required|in:draft,scheduled',
        ]);

        try {
            $newsletter_edition->update([
                'subject' => $request->subject,
                'body' => $request->body,
                'scheduled_at' => $request->scheduled_at ? $request->date('scheduled_at') : null,
                'status' => $request->status,
            ]);
            return redirect()->route('admin.newsletter-editions.index')->with('success', 'Newsletter edition updated.');
        } catch (\Exception $e) {
            Log::error('Newsletter edition update failed: '.$e->getMessage());
            return back()->withErrors(['error' => 'Failed to update newsletter edition.']);
        }
    }

    public function destroy(NewsletterEdition $newsletter_edition): RedirectResponse
    {
        try {
            $newsletter_edition->delete();
            return redirect()->route('admin.newsletter-editions.index')->with('success', 'Newsletter edition deleted.');
        } catch (\Exception $e) {
            Log::error('Newsletter edition deletion failed: '.$e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete newsletter edition.']);
        }
    }

    public function sendTest(Request $request, NewsletterEdition $newsletter_edition): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        try {
            Mail::to($request->email)->send(new WeeklyNewsletter($newsletter_edition, $request->email));
            return back()->with('success', 'Test email sent to '.$request->email);
        } catch (\Exception $e) {
            Log::error('Newsletter test send failed: '.$e->getMessage());
            return back()->withErrors(['error' => 'Failed to send test email.']);
        }
    }

    public function send(NewsletterEdition $newsletter_edition): RedirectResponse
    {
        if (! $newsletter_edition->isSendable()) {
            return back()->withErrors(['error' => 'This edition has already been sent.']);
        }
        $subscribers = Newsletter::where('status', 'active')->pluck('email');
        foreach ($subscribers as $email) {
            SendNewsletterToSubscriber::dispatch($newsletter_edition, $email);
        }
        $newsletter_edition->markAsSent();
        return redirect()->route('admin.newsletter-editions.index')
            ->with('success', 'Newsletter is being sent to '.$subscribers->count().' subscribers.');
    }
}
