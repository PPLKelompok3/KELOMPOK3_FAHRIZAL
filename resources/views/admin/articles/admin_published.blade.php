@extends('layouts.admin')
@section('title', 'Admin Published Articles')

@section('content')
<h4 class="mb-4">Articles Published by Admins</h4>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Title</th>
          <th>Published By</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($articles as $article)
        <tr>
          <td>{{ $article->title }}</td>
          <td>{{ $article->user->name }}</td>
          <td>{{ $article->created_at->format('M d, Y') }}</td>
          <td>
            <span class="badge bg-label-success">Published</span>
          </td>
          <td>
            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-info" target="_blank">
              View
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center">No articles found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
