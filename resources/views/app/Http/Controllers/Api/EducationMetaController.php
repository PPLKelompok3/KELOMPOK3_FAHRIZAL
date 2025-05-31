<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Education;

class EducationMetaController extends Controller
{
    public function searchInstitutions(Request $request)
    {
        return $this->searchField($request->q, 'institution_name');
    }

    public function searchDegrees(Request $request)
    {
        return $this->searchField($request->q, 'degree');
    }

    public function searchFields(Request $request)
    {
        return $this->searchField($request->q, 'field_of_study');
    }

    private function searchField($query, $column)
    {
        $results = Education::where($column, 'like', '%' . strtolower($query) . '%')
            ->distinct()
            ->limit(10)
            ->pluck($column)
            ->map(fn($item) => ['value' => ucfirst($item)]); // Capitalize for display

        return response()->json($results);
    }
}