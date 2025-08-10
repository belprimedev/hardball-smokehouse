<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Newsletter;

class NewsletterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleEmails = [
            'john.doe@example.com',
            'jane.smith@example.com',
            'mike.johnson@example.com',
            'sarah.wilson@example.com',
            'david.brown@example.com',
            'emma.davis@example.com',
            'james.miller@example.com',
            'lisa.garcia@example.com',
            'robert.rodriguez@example.com',
            'amanda.martinez@example.com',
        ];

        foreach ($sampleEmails as $email) {
            Newsletter::create([
                'email' => $email,
                'status' => 'active',
                'source' => rand(0, 1) ? 'website' : 'footer'
            ]);
        }

        // Add a few unsubscribed emails
        Newsletter::create([
            'email' => 'unsubscribed@example.com',
            'status' => 'unsubscribed',
            'source' => 'website'
        ]);

        Newsletter::create([
            'email' => 'another.unsub@example.com',
            'status' => 'unsubscribed',
            'source' => 'footer'
        ]);
    }
}
