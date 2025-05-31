@extends('layouts.recruiter') 
@section('title', 'Applications')
@section('content')
<div class="d-flex justify-content-start mb-3 gap-2">
  <form method="GET" action="{{ route('applications.index') }}" class="d-flex flex-wrap gap-2 mb-3 align-items-center">
    <!-- Skill Dropdown -->
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
    <!-- Location Dropdown -->
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
  
    <!-- Apply Button -->
    <button type="submit" class="btn btn-outline-primary">Apply Filters</button>
  
    <!-- Reset Button -->
    <a href="{{ route('applications.index') }}" class="btn btn-outline-dark">Reset</a>
  </form>
  
</div>
<div class="card">
  <h5 class="card-header">Job Applications</h5>
  <div class="table-responsive text-nowrap">
    
    
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Applicant Name</th>
          <th>Applied For</th>
          <th>Status</th>
          <th>Applied On</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($applications as $application)
        <tr>
          <td>{{ $application->user->name ?? '-' }}</td>
          <td>{{ $application->job->title ?? '-' }}</td>
          <td>
            <span class="badge bg-label-info">{{ $application->status }}</span>
          </td>
          <td>{{ $application->created_at->format('M d, Y') }}</td>
          <td>
            <a href="{{ route('applications.show', $application->id) }}" dusk="view-application-{{ $application->id }}" class="btn btn-sm btn-primary">
              View Details
            </a>            
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">No applications yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
