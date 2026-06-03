<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BugReportController extends Controller
{
    /**
     * Show the form for creating a new bug report.
     */
    public function create()
    {
        return view('bugs.report');
    }

    /**
     * Store a newly created bug report in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'steps' => 'nullable|string',
            'severity' => 'required|in:low,medium,high,critical',
        ]);

        BugReport::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'steps' => $validated['steps'] ?? null,
            'severity' => $validated['severity'],
            'status' => 'open',
        ]);

        return redirect()->route('home')->with('success', 'Bug report submitted successfully! Thank you for helping us improve.');
    }
}
