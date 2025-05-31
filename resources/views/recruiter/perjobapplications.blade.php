@extends('layouts.recruiter') 
@section('title', 'Applicants for ' . $job->title)

@section('content')
<h5 class="pb-3 mb-4">Applications for <strong>{{ $job->title }}</strong></h5>

{{-- Filter bar --}}
<div class="card mb-4">
  <div class="card-body">
    <form method="GET" action="{{ route('recruiter.applications.byJob', $job->id) }}" class="row gx-3 gy-2 align-items-end">

      {{-- Saved Filters Dropdown --}}
<div class="col-auto">
  <label class="form-label fw-bold small mb-1">Load Saved Filter:</label>
  <select class="form-select" onchange="applySavedFilter(this)" id="saved-filter-select">
    <option disabled selected hidden>-- Select Saved Filter --</option>
    @foreach ($savedFilters as $filter)
      <option value="{{ json_encode([
        'skill' => $filter->skill,
        'location' => $filter->location,
        'min_score' => $filter->min_score
      ]) }}">
        {{ $filter->label }}
      </option>
    @endforeach
  </select>
</div>

      {{-- Skill Filter --}}
      <div class="col-auto">
        <label class="form-label fw-bold small mb-1">Skill:</label>
        <select name="skill" class="form-select">
          <option value="">All</option>
          @foreach ($availableSkills as $skill)
            <option value="{{ $skill }}" {{ request('skill') === $skill ? 'selected' : '' }}>{{ $skill }}</option>
          @endforeach
        </select>
      </div>

      {{-- Location Filter --}}
      <div class="col-auto">
        <label class="form-label fw-bold small mb-1">Location:</label>
        <select name="location" class="form-select">
          <option value="">All</option>
          @foreach ($availableLocations as $loc)
            <option value="{{ $loc }}" {{ request('location') === $loc ? 'selected' : '' }}>{{ $loc }}</option>
          @endforeach
        </select>
      </div>

      {{-- Min Score --}}
      <div class="col-auto">
        <label class="form-label fw-bold small mb-1">Min Score:</label>
        <input type="number" name="min_score" class="form-control" placeholder="e.g. 75"
               value="{{ request('min_score') }}" min="1" max="100">
      </div>

      {{-- Buttons --}}
      <div class="col-auto">
        <label class="d-block invisible">.</label> {{-- For alignment --}}
        <button type="submit" class="btn btn-outline-primary">Apply Filters</button>
      </div>
      <div class="col-auto">
        <label class="d-block invisible">.</label>
        <a href="{{ route('recruiter.applications.byJob', $job->id) }}" class="btn btn-outline-dark">Reset</a>
      </div>
    </form>

    {{-- Save Current Filter --}}
    <form action="{{ route('filters.save', $job->id) }}" method="POST" class="row gx-2 align-items-end mt-3">
      @csrf
      <input type="hidden" name="skill" value="{{ request('skill') }}">
<input type="hidden" name="location" value="{{ request('location') }}">
<input type="hidden" name="min_score" value="{{ request('min_score') }}">


      <div class="col-auto">
        <label class="form-label fw-bold small mb-1">Save Filter As:</label>
        <input type="text" name="label" class="form-control" placeholder="Filter name..." required>
      </div>
      <div class="col-auto">
        <label class="d-block invisible">.</label>
        <button type="submit" class="btn btn-success">Save Filter</button>
      </div>
    </form>
  </div>
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

@push('scripts')
<script>
  function applySavedFilter(selectElement) {
    const selected = selectElement.value;
    if (!selected) return;

    const filters = JSON.parse(selected);

    const url = new URL(window.location.href);
    url.searchParams.set('skill', filters.skill || '');
    url.searchParams.set('location', filters.location || '');
    url.searchParams.set('min_score', filters.min_score || '');

    window.location.href = url.toString();
  }
</script>

@endpush