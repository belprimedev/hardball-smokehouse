<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        $this->admin = User::factory()->create(['email' => 'admin-events@test.com']);
        $this->admin->assignRole('admin');
    }

    public function test_event_can_be_created_with_image_upload_and_schedule(): void
    {
        $image = UploadedFile::fake()->image('event-test.jpg', 400, 300);

        $response = $this->actingAs($this->admin)->post(route('admin.events.store'), [
            'title_primary' => 'Test',
            'title_secondary' => 'Event',
            'title_suffix' => 'at Hardball!',
            'description' => 'Test event description',
            'image' => $image,
            'features' => [
                ['title' => 'Feature 1', 'description' => 'Desc 1'],
            ],
            'cta_text' => 'Reserve',
            'cta_link' => null,
            'status' => 'published',
            'show_on_homepage' => true,
            'sort_order' => 0,
            'starts_at' => now()->subHour()->format('Y-m-d\TH:i'),
            'ends_at' => now()->addDays(5)->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success');

        $event = Event::latest()->first();
        $this->assertNotNull($event);
        $this->assertSame('Test', $event->title_primary);
        $this->assertSame('Event', $event->title_secondary);
        $this->assertNotNull($event->starts_at);
        $this->assertNotNull($event->ends_at);
        $this->assertStringStartsWith('events/', $event->image_path);
        Storage::disk('public')->assertExists($event->image_path);
    }

    public function test_featured_event_only_returned_when_within_schedule(): void
    {
        Event::create([
            'title_primary' => 'A',
            'title_secondary' => 'Event',
            'description' => 'Desc',
            'image_path' => '/img/event/placeholder.png',
            'cta_text' => 'Reserve',
            'status' => 'published',
            'show_on_homepage' => true,
            'sort_order' => 0,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->subHour(),
        ]);

        $this->assertNull(Event::getFeaturedForHomepage());

        $event = Event::first();
        $event->ends_at = now()->addDay();
        $event->save();

        $this->assertNotNull(Event::getFeaturedForHomepage());
        $this->assertSame($event->id, Event::getFeaturedForHomepage()->id);
    }
}
