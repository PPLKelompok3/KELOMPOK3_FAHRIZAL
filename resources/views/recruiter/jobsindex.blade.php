@extends('layouts.recruiter')
@section('title', 'My Job Posts')

@section('content')
<div class="card">
  <h5 class="card-header">My Job Posts</h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Title</th>
          <th>Type</th>
          <th>Location</th>
          <th>Category</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($jobs as $job)
        <tr>
          <td>{{ $job->title }}</td>
          <td>{{ $job->employment_type }}</td>
          <td>{{ $job->location }}</td>
          <td>{{ $job->category }}</td>
          <td class="d-flex gap-2">
  <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-sm btn-primary">
    Edit
  </a>
  <a href="{{ route('recruiter.applications.byJob', $job->id) }}" class="btn btn-sm btn-outline-secondary">
    Applications
  </a>
</td>


        </tr>
        @empty
        <tr><td colspan="5" class="text-center">You haven't posted any jobs yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
