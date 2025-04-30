@extends('layouts.applicant')
@section('title', 'My Applications')

@section('content')
<div class="card">
  <h5 class="card-header">My Job Applications</h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Job</th>
          <th>Company</th>
          <th>Status</th>
          <th>Applied On</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($applications as $application)
        <tr onclick="window.location='{{ route('jobs.show', ['id' => $application->job->id, 'slug' => \Str::slug($application->job->title)]) }}'" style="cursor:pointer;">
          <td>
            <a>
              {{ $application->job->title }}
            </a>
          </td>
          <td>{{ $application->job->company->company_name ?? '-' }}</td>
          <td><span class="badge bg-label-info">{{ $application->status }}</span></td>
          <td>{{ $application->created_at->format('M d, Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="text-center">You haven't applied for any jobs yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection