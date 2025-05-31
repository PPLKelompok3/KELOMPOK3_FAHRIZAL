<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skill;

class SkillController extends Controller
{
    public function search(Request $request)
    {
        $query = strtolower($request->q); // normalize

        $skills = Skill::where('name', 'like', '%' . $query . '%')
            ->limit(10)
            ->pluck('name')
            ->map(function ($name) {
                return ['value' => ucfirst($name)]; // Capitalize for display
            });

        return response()->json($skills);
    }
}