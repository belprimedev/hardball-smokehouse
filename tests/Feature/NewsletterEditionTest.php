<?php

namespace Tests\Feature;

use App\Jobs\SendNewsletterToSubscriber;
use App\Mail\WeeklyNewsletter;
use App\Models\Newsletter;
use App\Models\NewsletterEdition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class NewsletterEditionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        $this->admin = User::factory()->create(['email' => 'admin-newsletter@test.com']);
        $this->admin->assignRole('admin');
    }

    public function test_edition_can_be_created_and_listed(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.newsletter-editions.store'), [
            'subject' => 'Weekly Update',
            'body' => '<p>Hello from Hardball!</p>',
            'scheduled_at' => null,
            'status' => 'draft',
        ]);
        $response->assertRedirect(route('admin.newsletter-editions.index'));
        $response->assertSessionHas('success');

        $edition = NewsletterEdition::first();
        $this->assertSame('Weekly Update', $edition->subject);
        $this->assertSame('draft', $edition->status);

        $index = $this->actingAs($this->admin)->get(route('admin.newsletter-editions.index'));
        $index->assertOk();
        $index->assertInertia(fn ($page) => $page->component('Admin/NewsletterEditions/Index')->has('editions'));
    }

    public function test_send_queues_jobs_and_marks_edition_sent(): void
    {
        Newsletter::create(['email' => 'a@test.com', 'status' => 'active', 'source' => 'website']);
        Newsletter::create(['email' => 'b@test.com', 'status' => 'active', 'source' => 'website']);
        Newsletter::create(['email' => 'c@test.com', 'status' => 'unsubscribed', 'source' => 'website']);

        $edition = NewsletterEdition::create([
            'subject' => 'Test Send',
            'body' => '<p>Body</p>',
            'status' => 'draft',
        ]);

        Queue::fake();
        $response = $this->actingAs($this->admin)->post(route('admin.newsletter-editions.send', $edition));
        $response->assertRedirect(route('admin.newsletter-editions.index'));
        $response->assertSessionHas('success');

        Queue::assertPushed(SendNewsletterToSubscriber::class, 2); // only active
        $edition->refresh();
        $this->assertSame('sent', $edition->status);
        $this->assertNotNull($edition->sent_at);
    }

    public function test_send_test_queues_one_email(): void
    {
        Mail::fake();
        $edition = NewsletterEdition::create([
            'subject' => 'Test',
            'body' => '<p>Body</p>',
            'status' => 'draft',
        ]);
        $this->actingAs($this->admin)->post(route('admin.newsletter-editions.send-test', $edition), [
            'email' => 'test@example.com',
        ])->assertSessionHas('success');
        // WeeklyNewsletter implements ShouldQueue so it is queued
        Mail::assertQueued(WeeklyNewsletter::class, fn ($m) => $m->hasTo('test@example.com'));
    }

    public function test_unsubscribe_signed_url_unsubscribes_and_shows_page(): void
    {
        $sub = Newsletter::create(['email' => 'unsub@test.com', 'status' => 'active', 'source' => 'website']);
        $url = URL::signedRoute('newsletter.unsubscribe', ['email' => 'unsub@test.com']);

        $response = $this->get($url);
        $response->assertOk();
        $response->assertSee('You\'re unsubscribed', false);

        $sub->refresh();
        $this->assertSame('unsubscribed', $sub->status);
    }

    public function test_unsubscribe_invalid_signature_returns_403(): void
    {
        $response = $this->get(route('newsletter.unsubscribe', [
            'email' => 'any@test.com',
            'signature' => 'invalid',
        ]));
        $response->assertStatus(403);
    }
}
