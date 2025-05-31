<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExperienceOptionController
{
    public function searchOrganizations(Request $request)
    {
        $q = $request->query('q');
        $results = DB::table('experiences')
            ->select('company_or_org as value')
            ->where('company_or_org', 'like', "%$q%")
            ->distinct()
            ->limit(10)
            ->get();

        return response()->json($results);
    }

    public function searchTitles(Request $request)
    {
        $q = $request->query('q');
        $results = DB::table('experiences')
            ->select('title as value')
            ->where('title', 'like', "%$q%")
            ->distinct()
            ->limit(10)
            ->get();

        return response()->json($results);
    }
}