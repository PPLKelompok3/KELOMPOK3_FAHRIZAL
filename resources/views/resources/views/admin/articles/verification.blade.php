@extends('layouts.admin')
@section('title', 'Verify Articles')

@section('content')
<h4 class="mb-4">Pending Articles for Verification</h4>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Title</th>
          <th>Submitted By</th>
          <th>Submitted On</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($pendingArticles as $article)
        <tr>
          <td>{{ $article->title }}</td>
          <td>{{ $article->user->name }}</td>
          <td>{{ $article->created_at->format('M d, Y') }}</td>
          <td class="d-flex gap-2">
            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-info" target="_blank">Preview</a>
            <form method="POST" action="{{ route('admin.articles.approve', $article->id) }}">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn btn-sm btn-success">Approve</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center">No articles pending approval.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
