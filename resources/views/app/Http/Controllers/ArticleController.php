<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Skill;
use App\Models\ArticleComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        'header_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        'skills' => 'nullable|string' // will parse manually
    ]);

    $article = new Article();
    $article->user_id = Auth::id();
    $article->title = $request->title;
    $article->content = $request->content;

    // Handle file uploads
    if ($request->hasFile('thumbnail')) {
        $article->thumbnail = $request->file('thumbnail')->store('articles/thumbnails', 'public');
    }

    if ($request->hasFile('header_image')) {
        $article->header_image = $request->file('header_image')->store('articles/headers', 'public');
    }

    $article->status = Auth::user()->role === 'admin' ? 'published' : 'pending';
    $article->save();


    // Handle skill tag association
    if ($request->filled('skills')) {
        $skillNames = json_decode($request->skills, true);
        $skillIds = [];

        foreach ($skillNames as $item) {
            $skillName = is_array($item) ? $item['value'] : $item;
            $skill = Skill::firstOrCreate(['name' => $skillName]);
            $skillIds[] = $skill->id;
        }

        $article->skills()->sync($skillIds);
    }

    return redirect()->route('articles.index')->with('success', 'Article created successfully!');
}
public function create()
{
    $skillSuggestions = Skill::pluck('name');
    return view('articles.create', compact('skillSuggestions'));
}
public function index(Request $request)
{
    $user = auth::user();

    $baseQuery = Article::with('user', 'skills')->where('status', 'published');

    $recommended = collect();
    $nonRecommended = collect();

    // Step 1: Get skill filter from URL (e.g. ?skill=Laravel)
    $filterSkill = $request->query('skill');

    // Step 2: Recommendation logic for logged-in user
    if ($user) {
        /** @var \App\Models\User $user */
        $userSkillIds = $user->skills()->pluck('skills.id');

        $recommended = (clone $baseQuery)
            ->whereHas('skills', fn($q) => $q->whereIn('skills.id', $userSkillIds))
            ->latest()
            ->take(6)
            ->get();

        $nonRecommended = (clone $baseQuery)
            ->whereDoesntHave('skills', fn($q) => $q->whereIn('skills.id', $userSkillIds));
    } else {
        $nonRecommended = clone $baseQuery;
    }

    // Step 3: Apply skill filter to nonRecommended list
    if ($filterSkill) {
        $nonRecommended->whereHas('skills', function ($q) use ($filterSkill) {
            $q->where('name', $filterSkill);
        });
    }

    $nonRecommended = $nonRecommended->latest()->paginate(6);

    $availableSkills = \App\Models\Skill::pluck('name');
    return view('articles.index', compact('recommended', 'nonRecommended', 'availableSkills'));

}





public function show(Article $article)
{
    $article->load('author.company', 'skills');

    $allSkillStats = Skill::whereHas('articles')
        ->withCount('articles')
        ->get()
        ->map(function ($skill) {
            return (object)[
                'name' => $skill->name,
                'article_count' => $skill->articles_count
            ];
        });

    return view('articles.show', compact('article', 'allSkillStats'));
}
public function storeComment(Request $request, $id)
{
    $request->validate([
        'content' => 'required|string|max:1000'
    ]);

    ArticleComment::create([
        'article_id' => $id,
        'user_id' => Auth::id(),
        'content' => $request->content,
    ]);

    return back()->with('success', 'Comment added!');
}
public function verifyArticles()
{
    $pendingArticles = Article::where('status', 'pending')->with('user')->latest()->get();
    return view('admin.articles.verification', compact('pendingArticles'));
}

public function approveArticle($id)
{
    $article = Article::findOrFail($id);
    $article->status = 'published';
    $article->save();

    return back()->with('success', 'Article approved and published.');
}
public function listArticles()
{
    $Articles = Article::whereIn('status', ['published', 'rejected'])
        ->with('user')
        ->latest()
        ->get();

    return view('admin.articles.articlelist', compact('Articles'));
}
public function adminPublishedArticles()
{
    $articles = Article::with('user')
        ->where('status', 'published')
        ->whereHas('user', function ($q) {
            $q->where('role', 'admin');
        })
        ->latest()
        ->get();

    return view('admin.articles.admin_published', compact('articles'));
}





}