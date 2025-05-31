@extends('layouts.recruiter') 
@section('title', 'Applicants for ' . $job->title)

@section('content')
<h5 class="pb-3 mb-4">Applications for <strong>{{ $job->title }}</strong></h5>

{{-- Filter bar --}}
<div class="d-flex justify-content-start mb-3 gap-2">
  <form method="GET" action="{{ route('recruiter.applications.byJob', $job->id) }}" class="d-flex flex-wrap gap-2 align-items-center">

    {{-- Skill Filter --}}
    <div class="btn-group">
      <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
        {{ request('skill') ?? 'Filter by Skill' }}
      </button>
      <ul class="dropdown-menu">
        @foreach ($availableSkills as $skill)
        <li>
          <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('skill-input').value = '{{ $skill }}'; this.closest('form').submit();">
            {{ $skill }}
          </a>
        </li>
        @endforeach
      </ul>
    </div>
    <input type="hidden" name="skill" id="skill-input" value="{{ request('skill') }}">

    {{-- Location Filter --}}
    <div class="btn-group">
      <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
        {{ request('location') ?? 'Filter by Location' }}
      </button>
      <ul class="dropdown-menu">
        @foreach ($availableLocations as $loc)
        <li>
          <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('location-input').value = '{{ $loc }}'; this.closest('form').submit();">
            {{ $loc }}
          </a>
        </li>
        @endforeach
      </ul>
    </div>
    <input type="hidden" name="location" id="location-input" value="{{ request('location') }}">

    {{-- Min Score Filter --}}
    <div>
      <input type="number" name="min_score" class="form-control" placeholder="Min Score" value="{{ request('min_score') }}" min="1" max="100" style="width: 120px;">
    </div>

    <button type="submit" class="btn btn-outline-primary">Apply Filters</button>
    <a href="{{ route('recruiter.applications.byJob', $job->id) }}" class="btn btn-outline-dark">Reset</a>
  </form>
</div>

{{-- Applications Table --}}
<div class="card">
  <h5 class="card-header">Applicants for {{ $job->title }}</h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Applicant</th>
          <th>Status</th>
          <th>Score</th>
          <th>Feedback</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($applications as $application)
        <tr>
          <td>{{ $application->user->name ?? '-' }}</td>
          <td><span class="badge bg-label-info">{{ $application->status }}</span></td>
          <td>{{ $application->score ?? '-' }}</td>
          <td style="max-width: 300px;">
            {{ \Illuminate\Support\Str::limit($application->feedback, 100) ?? '-' }}
          </td>
          <td>
            <a href="{{ route('applications.show', $application->id) }}" class="btn btn-sm btn-primary">
              View
            </a>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No applications found for this job post.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
