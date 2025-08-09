<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $newsletters = Newsletter::orderBy('created_at', 'desc')->paginate($perPage);
        return Inertia::render('Admin/Newsletters/Index', [
            'newsletters' => $newsletters,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Newsletters/Create', [
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(Newsletter::rules());

        try {
            Newsletter::create([
                'email' => $request->email,
                'status' => 'active',
                'source' => $request->source ?? 'admin'
            ]);

            return redirect()->route('admin.newsletters.index')
                ->with('success', 'Newsletter subscriber added successfully.');
        } catch (\Exception $e) {
            Log::error('Newsletter creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to add newsletter subscriber.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Newsletter $newsletter)
    {
        return Inertia::render('Admin/Newsletters/Show', [
            'newsletter' => $newsletter,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Newsletter $newsletter)
    {
        return Inertia::render('Admin/Newsletters/Edit', [
            'newsletter' => $newsletter,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Newsletter $newsletter)
    {
        $request->validate(Newsletter::updateRules($newsletter->id));

        try {
            $newsletter->update([
                'email' => $request->email,
                'status' => $request->status,
                'source' => $request->source
            ]);

            return redirect()->route('admin.newsletters.index')
                ->with('success', 'Newsletter subscriber updated successfully.');
        } catch (\Exception $e) {
            Log::error('Newsletter update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update newsletter subscriber.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Newsletter $newsletter)
    {
        try {
            $newsletter->delete();
            return redirect()->route('admin.newsletters.index')
                ->with('success', 'Newsletter subscriber deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Newsletter deletion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete newsletter subscriber.']);
        }
    }

    /**
     * Subscribe a new email to the newsletter.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
            'source' => 'nullable|string|in:website,footer,admin'
        ]);

        try {
            Newsletter::create([
                'email' => $request->email,
                'status' => 'active',
                'source' => $request->source ?? 'website'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to newsletter!'
            ]);
        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe to newsletter. Please try again.'
            ], 500);
        }
    }

    /**
     * Unsubscribe an email from the newsletter.
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $newsletter = Newsletter::where('email', $request->email)->first();
            
            if (!$newsletter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found in our newsletter list.'
                ], 404);
            }

            $newsletter->update(['status' => 'unsubscribed']);

            return response()->json([
                'success' => true,
                'message' => 'Successfully unsubscribed from newsletter.'
            ]);
        } catch (\Exception $e) {
            Log::error('Newsletter unsubscription failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to unsubscribe from newsletter. Please try again.'
            ], 500);
        }
    }

    /**
     * Get newsletter statistics.
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total' => Newsletter::count(),
                'active' => Newsletter::where('status', 'active')->count(),
                'unsubscribed' => Newsletter::where('status', 'unsubscribed')->count(),
                'this_month' => Newsletter::where('created_at', '>=', now()->startOfMonth())->count(),
                'sources' => Newsletter::selectRaw('source, count(*) as count')
                    ->groupBy('source')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Newsletter stats failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch newsletter statistics.'
            ], 500);
        }
    }
}
