<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vacancies = Vacancy::active()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($vacancy) {
                return [
                    'id' => $vacancy->id,
                    'title' => $vacancy->title,
                    'description' => $vacancy->description,
                    'requirements' => $vacancy->requirements,
                    'responsibilities' => $vacancy->responsibilities,
                    'location' => $vacancy->location,
                    'type' => $vacancy->type,
                    'department' => $vacancy->department,
                    'salary' => $vacancy->formatted_salary,
                    'application_deadline' => $vacancy->application_deadline?->format('Y-m-d'),
                    'positions_available' => $vacancy->positions_available,
                    'is_expired' => $vacancy->is_expired,
                    'is_active' => $vacancy->is_active,
                    'created_at' => $vacancy->created_at->format('Y-m-d'),
                ];
            });

        // Check if this is an admin route
        if (request()->route()->getName() === 'admin.vacancies.index') {
            return Inertia::render('Admin/Vacancies/Index', [
                'vacancies' => $vacancies,
            ]);
        }

        return Inertia::render('Vacancy', [
            'vacancies' => $vacancies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Vacancy/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'required|string|max:255',
            'type' => 'required|string|in:Full-time,Part-time,Casual',
            'department' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_type' => 'required|string|in:hourly,monthly,yearly',
            'application_deadline' => 'nullable|date|after:today',
            'positions_available' => 'required|integer|min:1',
        ]);

        Vacancy::create($validated);

        return redirect()->route('vacancy.index')
            ->with('success', 'Vacancy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacancy $vacancy)
    {
        return Inertia::render('Vacancy/Show', [
            'vacancy' => [
                'id' => $vacancy->id,
                'title' => $vacancy->title,
                'description' => $vacancy->description,
                'requirements' => $vacancy->requirements,
                'responsibilities' => $vacancy->responsibilities,
                'location' => $vacancy->location,
                'type' => $vacancy->type,
                'department' => $vacancy->department,
                'salary' => $vacancy->formatted_salary,
                'application_deadline' => $vacancy->application_deadline?->format('Y-m-d'),
                'positions_available' => $vacancy->positions_available,
                'is_expired' => $vacancy->is_expired,
                'created_at' => $vacancy->created_at->format('Y-m-d'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacancy $vacancy)
    {
        return Inertia::render('Vacancy/Edit', [
            'vacancy' => [
                'id' => $vacancy->id,
                'title' => $vacancy->title,
                'description' => $vacancy->description,
                'requirements' => $vacancy->requirements,
                'responsibilities' => $vacancy->responsibilities,
                'location' => $vacancy->location,
                'type' => $vacancy->type,
                'department' => $vacancy->department,
                'salary_min' => $vacancy->salary_min,
                'salary_max' => $vacancy->salary_max,
                'salary_type' => $vacancy->salary_type,
                'application_deadline' => $vacancy->application_deadline?->format('Y-m-d'),
                'positions_available' => $vacancy->positions_available,
                'is_active' => $vacancy->is_active,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacancy $vacancy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'required|string|max:255',
            'type' => 'required|string|in:Full-time,Part-time,Casual',
            'department' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_type' => 'required|string|in:hourly,monthly,yearly',
            'application_deadline' => 'nullable|date',
            'positions_available' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $vacancy->update($validated);

        return redirect()->route('vacancy.index')
            ->with('success', 'Vacancy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();

        return redirect()->route('vacancy.index')
            ->with('success', 'Vacancy deleted successfully.');
    }
}
