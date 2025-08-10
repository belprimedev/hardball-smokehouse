<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\SystemAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Store a new contact form submission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            $contact = Contact::create($validated);

            // Create notification
            Notification::create([
                'type' => 'contact',
                'title' => 'New Contact Form Submission',
                'message' => "New contact form submission from {$contact->name}",
                'data' => [
                    'contact_id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'subject' => $contact->subject,
                    'message_preview' => $contact->message_preview,
                ],
            ]);

            // Send system alert to admin users
            $adminUsers = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

            foreach ($adminUsers as $admin) {
                $admin->notify(new SystemAlert(
                    'New Contact Form Submission',
                    "New contact form submission from {$contact->name} ({$contact->email})",
                    'info',
                    [
                        'contact_id' => $contact->id,
                        'name' => $contact->name,
                        'email' => $contact->email,
                        'subject' => $contact->subject,
                        'message_preview' => $contact->message_preview,
                    ]
                ));
            }

            // For Inertia requests, redirect back with success message
            if ($request->header('X-Inertia')) {
                return redirect()->back()->with('success', 'Thank you for your message! We\'ll get back to you as soon as possible.');
            }

            // For API requests, return JSON
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message! We\'ll get back to you as soon as possible.'
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage());
            
            // For Inertia requests, redirect back with error
            if ($request->header('X-Inertia')) {
                return redirect()->back()->withErrors(['message' => 'Sorry, there was an error sending your message. Please try again.']);
            }
            
            // For API requests, return JSON error
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again.'
            ], 500);
        }
    }

    /**
     * Display a listing of contacts (admin)
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $contacts = $query->orderBy('created_at', 'desc')
                         ->paginate(15)
                         ->withQueryString();

        $stats = [
            'total' => Contact::count(),
            'new' => Contact::where('status', 'new')->count(),
            'read' => Contact::where('status', 'read')->count(),
            'replied' => Contact::where('status', 'replied')->count(),
            'closed' => Contact::where('status', 'closed')->count(),
        ];

        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => $contacts,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Display the specified contact (admin)
     */
    public function show(Contact $contact)
    {
        // Mark as read if it's new
        if ($contact->status === 'new') {
            $contact->markAsRead();
        }

        return Inertia::render('Admin/Contacts/Show', [
            'contact' => $contact,
        ]);
    }

    /**
     * Update the specified contact (admin)
     */
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $contact->update($validated);

        return redirect()->route('admin.contacts.show', $contact)
                        ->with('success', 'Contact updated successfully!');
    }

    /**
     * Mark contact as replied
     */
    public function markAsReplied(Contact $contact)
    {
        $contact->markAsReplied();

        return response()->json([
            'success' => true,
            'message' => 'Contact marked as replied'
        ]);
    }

    /**
     * Get contact statistics for dashboard
     */
    public function stats()
    {
        return response()->json([
            'total_contacts' => Contact::count(),
            'new_contacts' => Contact::where('status', 'new')->count(),
            'recent_contacts' => Contact::where('created_at', '>=', now()->subDays(7))->count(),
        ]);
    }
}
