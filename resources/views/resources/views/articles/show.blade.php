@extends('layouts.landing')

@section('title', $article->title)

@section('content')
<section class="section-hero overlay inner-page bg-image"
         style="background-image: url('{{ $article->header_image ? asset('storage/' . $article->header_image) : asset('assets/images/hero_1.jpg') }}');"
         id="home-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="custom-breadcrumbs mb-0">
          <span class="slash">Posted by</span>
          {{ $article->author->role === 'recruiter' ? $article->author->name ?? 'Unknown Company' : 'Workora' }}
          <span class="mx-2 slash">&bullet;</span>
          <span class="text-white"><strong>{{ $article->created_at->format('F d, Y') }}</strong></span>
        </div>
        <h1 class="text-white">{{ $article->title }}</h1>
        @auth
  <form action="{{ route('articles.toggleFavorite', $article->id) }}" method="POST" style="display:inline-block;">
    @csrf
    <button type="submit" class="btn btn-outline-light btn-sm mt-2">
      @if(auth()->user()->favoriteArticles()->where('article_id', $article->id)->exists())
    <i class="bx bxs-bookmark"></i> Remove from Favorites
@else
    <i class="bx bx-bookmark"></i> Save to Favorites
@endif

    </button>
  </form>
@endauth

      </div>
    </div>
  </div>
</section>

<section class="site-section" id="next-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 blog-content">
        {!! $article->content !!}

        <div class="pt-5">
          <p>
            Skills:
            @foreach ($article->skills as $skill)
              <a href="{{ route('articles.index', ['skill' => $skill->name]) }}">#{{ $skill->name }}</a>{{ !$loop->last ? ',' : '' }}
            @endforeach
          </p>
        </div>

        {{-- Keep this for future use --}}
        <div class="pt-5">
  <h3 class="mb-5">{{ $article->comments->count() }} Comments</h3>
  <ul class="comment-list">
    @foreach ($article->comments as $comment)
    <li class="comment">
      <div class="vcard bio">
  @if ($comment->user->role === 'admin')
    <img src="{{ asset('assets/img/icons/logo/workora.svg') }}" alt="Workora Logo">
  @else
    <img
      src="{{ $comment->user->profile?->profile_picture
        ? asset('storage/' . $comment->user->profile->profile_picture)
        : asset('assets/img/default-avatar.jpg') }}"
      alt="Author Avatar">
  @endif
</div>
      <div class="comment-body">
        <h3>{{ $comment->user->name }}</h3>
        <div class="meta">{{ $comment->created_at->format('F j, Y \a\t g:i A') }}</div>
        <p>{{ $comment->content }}</p>
      </div>
    </li>
    @endforeach
  </ul>

  <div class="comment-form-wrap pt-5">
    @auth
      <h3 class="mb-5">Leave a comment as <strong>{{ auth()->user()->name }}</strong></h3>
      <form method="POST" action="{{ route('article.comment', $article->id) }}">
        @csrf
        <div class="form-group">
          <textarea name="content" class="form-control" rows="3" placeholder="Write your comment..." required></textarea>
        </div>
        <div class="form-group mt-2">
          <button type="submit" class="btn btn-primary">Post Comment</button>
        </div>
      </form>
    @else
      <p class="text-muted">You must <a href="{{ route('login') }}">log in</a> to post a comment.</p>
    @endauth
  </div>
</div>

      </div>

      {{-- Sidebar --}}
      <div class="col-lg-4 sidebar pl-lg-5">

        {{-- Author --}}
        @php
  $author = $article->author;
  $profile = $author->profile;

  // Profile picture fallback
  $authorImage = $author->role === 'admin'
    ? asset('assets/img/icons/logo/workora.svg')
    : ($profile?->profile_picture
        ? asset('storage/' . $profile->profile_picture)
        : asset('assets/img/avatars/1.png'));


  // Name
  $authorName = $author->name;

  // Bio / About fallback
  $aboutText = $profile?->bio
      ?? $author->company->company_description
      ?? 'This article was published by a verified member of the Workora team.';
@endphp

<div class="sidebar-box">
  <img src="{{ $authorImage }}" alt="{{ $authorName }}'s profile picture" class="img-fluid mb-4 w-50 rounded-circle">
  <h3>About {{ $authorName }}</h3>
  <p>{{ $aboutText }}</p>
</div>

        {{-- Skill Categories --}}
        <div class="sidebar-box">
          <div class="categories">
            <h3>Skills</h3>
            @foreach ($allSkillStats as $skill)
              <li>
                <a href="{{ route('articles.index', ['skill' => $skill->name]) }}">
                  {{ $skill->name }} <span>({{ $skill->article_count }})</span>
                </a>
              </li>
            @endforeach
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection
