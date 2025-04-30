<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnologyController
{
    public function autocomplete(Request $request)
    {
        $query = strtolower($request->query('q', ''));

        // Step 1: Fetch all technologies_used values from projects
        $all = DB::table('projects')
            ->whereNotNull('technologies_used')
            ->pluck('technologies_used');

        // Step 2: Flatten and split all tags
        $technologies = collect($all)
            ->flatMap(function ($item) {
                return explode(',', strtolower($item)); // normalize case
            })
            ->map(fn($tech) => trim($tech))
            ->unique()
            ->filter()
            ->filter(fn($tech) => str_contains($tech, $query))
            ->take(10)
            ->map(fn($tech) => ['value' => ucwords($tech)]) // Format nicely
            ->values();

        return response()->json($technologies);
    }
}