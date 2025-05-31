<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedFilter;
use Illuminate\Support\Facades\Auth;

class SavedFilterController extends Controller
{
    // Store a new saved filter
    public function store(Request $request, $jobId)
{
    $request->validate([
        'label' => 'required|string|max:255',
    ]);
    SavedFilter::create([
        'user_id' => auth::id(),
        'job_id' => $jobId,
        'label' => $request->label,
        'skill' => $request->skill,
        'location' => $request->location,
        'min_score' => $request->min_score,
    ]);
    
    return back()->with('success', 'Filter saved successfully.');
}


    // Load and apply a saved filter
    public function apply(Request $request, $id)
    {
        $filter = SavedFilter::where('user_id', Auth::id())->findOrFail($id);

        return redirect()->route('recruiter.applications.byJob', [
            'id'        => $filter->job_id,
            'skill'     => $filter->skill,
            'location'  => $filter->location,
            'min_score' => $filter->min_score,
        ]);
    }

    // Show list of saved filters for dropdown
    public function index(Request $request, $jobId)
    {
        $filters = SavedFilter::where('user_id', Auth::id())
                    ->where('job_id', $jobId)
                    ->get();

        return response()->json($filters);
    }

    // Optionally delete a filter
    public function destroy($id)
    {
        $filter = SavedFilter::where('user_id', Auth::id())->findOrFail($id);
        $filter->delete();

        return back()->with('success', 'Filter deleted.');
    }
}