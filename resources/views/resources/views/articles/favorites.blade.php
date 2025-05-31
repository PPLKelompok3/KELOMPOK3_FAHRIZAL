@extends('layouts.landing')

@section('title', 'Favorite Articles')

@section('content')
<section class="section-hero overlay inner-page bg-image" style="background-image: url('{{ asset('assets/images/hero_1.jpg') }}');" id="home-section">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h1 class="text-white font-weight-bold">Favorite Articles</h1>
        <div class="custom-breadcrumbs">
          <a href="{{ url('/') }}">Home</a> <span class="mx-2 slash">/</span>
          <span class="text-white"><strong>Favorites</strong></span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="site-section">
  <div class="container">
    <h2 class="mb-4">Your Saved Articles</h2>
    <div class="row mb-5">
      @forelse ($favoriteArticles as $article)
        <div class="col-md-6 col-lg-4 mb-5">
          <a href="{{ route('articles.show', $article->id) }}">
            <img src="{{ $article->thumbnail ? asset('storage/' . $article->thumbnail) : asset('assets/images/default-article-thumb.png') }}"
                 alt="Thumbnail" class="img-fluid article-thumbnail">
          </a>
          <h3>
            <a href="{{ route('articles.show', $article->id) }}" class="text-black">
              {{ Str::limit($article->title, 60) }}
            </a>
          </h3>
          <div>
            {{ $article->created_at->format('F d, Y') }}
            <span class="mx-2">|</span>
            <span>by {{ $article->author->name ?? 'Unknown' }}</span>
          </div>
        </div>
      @empty
        <div class="col-12 text-center">
          <p>You haven't saved any articles yet.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>
@endsection
