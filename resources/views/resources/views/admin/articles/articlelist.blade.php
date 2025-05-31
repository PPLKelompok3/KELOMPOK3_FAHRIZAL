@extends('layouts.admin')
@section('title', 'Verify Articles')

@section('content')
<h4 class="mb-4">Verified articles</h4>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Title</th>
          <th>Submitted By</th>
          <th>Submitted On</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($Articles as $article)
        <tr>
          <td>{{ $article->title }}</td>
          <td>{{ $article->user->name }}</td>
          <td>{{ $article->created_at->format('M d, Y') }}</td>
          <td>
  @if ($article->status === 'Published')
    <span class="badge bg-label-success">Published</span>
  @elseif ($article->status === 'Rejected')
    <span class="badge bg-label-danger">Rejected</span>
  @elseif ($article->status === 'pending')
    <span class="badge bg-label-warning">Pending</span>
  @else
    <span class="badge bg-label-secondary">{{ ucfirst($article->status) }}</span>
  @endif
</td>

          <td class="d-flex gap-2">
            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-info" target="_blank">Preview</a>
          </td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center">No articles uploaded.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
