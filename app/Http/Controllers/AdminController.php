<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of all submitted bug reports.
     */
    public function bugs()
    {
        $bugs = BugReport::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.bugs.index', compact('bugs'));
    }

    /**
     * Mark a specific bug report as resolved.
     */
    public function resolveBug(BugReport $bug)
    {
        $bug->update(['status' => 'resolved']);
        return redirect()->back()->with('success', 'Bug report marked as resolved successfully!');
    }
}
