<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vacancy;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacancies = [
            [
                'title' => 'Chef de Partie',
                'description' => 'We are looking for an experienced Chef de Partie to join our kitchen team. The ideal candidate will have experience in Caribbean cuisine and a passion for creating authentic dishes.',
                'requirements' => 'Minimum 2 years experience in a professional kitchen, knowledge of Caribbean cuisine, food safety certification, ability to work in a fast-paced environment.',
                'responsibilities' => 'Prepare and cook dishes according to recipes, maintain kitchen hygiene standards, assist with menu development, train junior kitchen staff.',
                'location' => 'Ipswich, UK',
                'type' => 'Full-time',
                'department' => 'Kitchen',
                'salary_min' => 12.50,
                'salary_max' => 15.00,
                'salary_type' => 'hourly',
                'application_deadline' => '2025-09-30',
                'positions_available' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Front of House Staff',
                'description' => 'Join our front of house team as a server or host. We\'re looking for friendly, enthusiastic individuals who are passionate about providing excellent customer service.',
                'requirements' => 'Previous experience in hospitality preferred, excellent communication skills, ability to work weekends and evenings, positive attitude.',
                'responsibilities' => 'Greet and seat customers, take orders and serve food, handle customer inquiries, maintain dining area cleanliness.',
                'location' => 'Ipswich, UK',
                'type' => 'Part-time',
                'department' => 'Front of House',
                'salary_min' => 10.50,
                'salary_max' => 12.00,
                'salary_type' => 'hourly',
                'application_deadline' => '2025-09-15',
                'positions_available' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Kitchen Porter',
                'description' => 'We\'re seeking a reliable and hardworking kitchen porter to support our kitchen team. This is an excellent opportunity to start your career in the culinary industry.',
                'requirements' => 'No experience required, willingness to learn, physical stamina, attention to detail, reliable attendance.',
                'responsibilities' => 'Wash dishes and kitchen equipment, maintain kitchen cleanliness, assist with food preparation, support kitchen staff.',
                'location' => 'Ipswich, UK',
                'type' => 'Full-time',
                'department' => 'Kitchen',
                'salary_min' => 9.50,
                'salary_max' => 10.50,
                'salary_type' => 'hourly',
                'application_deadline' => '2025-08-31',
                'positions_available' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Bartender',
                'description' => 'Join our bar team and help create amazing Caribbean-inspired cocktails. We\'re looking for someone with a passion for mixology and customer service.',
                'requirements' => 'Previous bartending experience, knowledge of cocktails and spirits, excellent customer service skills, ability to work evenings.',
                'responsibilities' => 'Prepare and serve cocktails, maintain bar cleanliness, handle cash transactions, create new drink recipes.',
                'location' => 'Ipswich, UK',
                'type' => 'Part-time',
                'department' => 'Bar',
                'salary_min' => 11.00,
                'salary_max' => 13.00,
                'salary_type' => 'hourly',
                'application_deadline' => '2025-09-20',
                'positions_available' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($vacancies as $vacancy) {
            Vacancy::create($vacancy);
        }
    }
}
