<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $events = Event::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate($perPage);

        return Inertia::render('Admin/Events/Index', [
            'events' => $events,
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
        return Inertia::render('Admin/Events/Create', [
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
        $titleSegments = $request->title_segments;
        if (is_string($titleSegments)) {
            $titleSegments = json_decode($titleSegments, true) ?: [];
        }
        $contentBlocks = $request->content_blocks;
        if (is_string($contentBlocks)) {
            $contentBlocks = json_decode($contentBlocks, true) ?: [];
        }
        $request->merge([
            'title_segments' => is_array($titleSegments) && count($titleSegments) > 0 ? $titleSegments : [],
            'content_blocks' => is_array($contentBlocks) && count($contentBlocks) > 0 ? $contentBlocks : [],
        ]);
        $request->validate(Event::rules());

        try {
            $imagePath = $request->image_path;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('events', 'public');
            }
            if (empty($imagePath)) {
                return back()->withErrors(['image' => 'Either upload an image or provide an image path.']);
            }

            $showOnHomepage = $request->boolean('show_on_homepage');
            if ($showOnHomepage) {
                Event::where('show_on_homepage', true)->update(['show_on_homepage' => false]);
            }

            Event::create([
                'title_primary' => $request->title_primary ?? '',
                'title_secondary' => $request->title_secondary ?? '',
                'title_suffix' => $request->title_suffix,
                'title_segments' => $request->title_segments,
                'description' => $request->description,
                'image_path' => $imagePath,
                'features' => $request->features ?? [],
                'content_blocks' => $request->content_blocks,
                'cta_text' => $request->cta_text ?? 'Reserve Your Spot',
                'cta_link' => $request->cta_link,
                'status' => $request->status ?? 'draft',
                'show_on_homepage' => $showOnHomepage,
                'sort_order' => (int) ($request->sort_order ?? 0),
                'starts_at' => $request->starts_at ? $request->date('starts_at') : null,
                'ends_at' => $request->ends_at ? $request->date('ends_at') : null,
            ]);

            return redirect()->route('admin.events.index')
                ->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            Log::error('Event creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create event.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return Inertia::render('Admin/Events/Show', [
            'event' => $event,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return Inertia::render('Admin/Events/Edit', [
            'event' => $event,
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // Decode JSON when sent as string (FormData can't send arrays; client may send JSON strings)
        $titleSegments = $request->title_segments;
        if (is_string($titleSegments)) {
            $titleSegments = json_decode($titleSegments, true) ?: [];
        }
        $contentBlocks = $request->content_blocks;
        if (is_string($contentBlocks)) {
            $contentBlocks = json_decode($contentBlocks, true) ?: [];
        }

        $request->merge([
            'title_segments' => is_array($titleSegments) && count($titleSegments) > 0 ? $titleSegments : [],
            'content_blocks' => is_array($contentBlocks) && count($contentBlocks) > 0 ? $contentBlocks : [],
        ]);
        $request->validate(Event::updateRules($event));

        try {
            $imagePath = $request->image_path ?? $event->image_path;
            if ($request->hasFile('image')) {
                if ($event->image_path && ! str_starts_with($event->image_path, '/')) {
                    Storage::disk('public')->delete($event->image_path);
                }
                $imagePath = $request->file('image')->store('events', 'public');
            }
            if ($request->boolean('remove_image') && $event->image_path && ! str_starts_with($event->image_path, '/')) {
                Storage::disk('public')->delete($event->image_path);
                $imagePath = $request->image_path ?: null;
            }
            if (empty($imagePath)) {
                return back()->withErrors(['image' => 'Event must have an image (upload or path).']);
            }

            $showOnHomepage = $request->boolean('show_on_homepage');
            if ($showOnHomepage) {
                Event::where('show_on_homepage', true)->where('id', '!=', $event->id)->update(['show_on_homepage' => false]);
            }

            $event->update([
                'title_primary' => $request->title_primary,
                'title_secondary' => $request->title_secondary,
                'title_suffix' => $request->title_suffix,
                'title_segments' => $request->title_segments,
                'description' => $request->description,
                'image_path' => $imagePath,
                'features' => $request->features ?? [],
                'content_blocks' => $request->content_blocks,
                'cta_text' => $request->cta_text ?? 'Reserve Your Spot',
                'cta_link' => $request->cta_link,
                'status' => $request->status,
                'show_on_homepage' => $showOnHomepage,
                'sort_order' => (int) ($request->sort_order ?? 0),
                'starts_at' => $request->starts_at ? $request->date('starts_at') : null,
                'ends_at' => $request->ends_at ? $request->date('ends_at') : null,
            ]);

            return redirect()->route('admin.events.index')
                ->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            Log::error('Event update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update event.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        try {
            $event->delete();
            return redirect()->route('admin.events.index')
                ->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Event deletion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete event.']);
        }
    }
}
