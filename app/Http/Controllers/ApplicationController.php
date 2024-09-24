<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    // Show form to create application
    public function create()
    {
        return view('applications.create');
    }

    // Store application data
    public function store(Request $request)
    {
        $validated =  $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $validated['date'] = now();
        $validated['to'] = 'John Doe';
        $validated['subject'] = 'Application for Approval';

        $application = Application::create($validated);

        return redirect()->route('bills.create', $application->id)->with('success', 'Application created successfully.');
    }

    // Additional methods like index, show, etc., can be added as needed
}
