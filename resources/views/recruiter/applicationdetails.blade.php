@extends('layouts.recruiter') 
@section('title', 'Applications')
@section('content')
<h5 class="pb-1 mb-4">Application Information</h5>

<div class="row mb-5 d-flex align-items-stretch">
  <!-- Applicant Info Card -->
  <div class="col-md-6 d-flex">
    <div class="card mb-3 w-100 h-100">
      <div class="row g-0">
        <div class="col-md-4">
          <img class="card-img card-img-left" src="{{ asset('assets/img/elements/12.jpg') }}" alt="Applicant Photo" />
        </div>
        <div class="col-md-8">
          <div class="card-body d-flex flex-column justify-content-between h-100">
            <div>
                <h5 class="card-title mb-2">{{ $application->user->name }}</h5>
                <p class="card-text mb-2">
                    <strong>Email:</strong> {{ $application->user->email }}<br>
                    <strong>Phone:</strong> {{ $application->user->phone ?? '-' }}<br>
                    <strong>Location:</strong> {{ $application->user->profile->location ?? '-' }}
                </p>                
            </div>
            <div class="mt-auto">
              <a href="#" class="btn btn-outline-primary btn-sm">View Full Profile</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Job Info Card -->
  <div class="col-md-6 d-flex">
    <div class="card mb-3 w-100 h-100">
      <div class="row g-0">
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title mb-2">{{ $application->job->title }}</h5>
<p class="card-text mb-2">
    <strong>Company:</strong> {{ $application->job->company->company_name ?? '-' }}<br>
    <strong>Location:</strong> {{ $application->job->location ?? '-' }}<br>
    <strong>Employment Type:</strong> {{ ucwords(str_replace('_', ' ', $application->job->employment_type)) }}<br>
    <strong>Experience Level:</strong> {{ $application->job->experience_level ?? '-' }}
</p>
<p class="card-text">
    <small class="text-muted">Posted on: {{ $application->job->created_at->format('M d, Y') }}</small>
</p>

<a href="{{ route('jobs.show', ['id' => $application->job->id, 'slug' => \Str::slug($application->job->title)]) }}" 
    class="btn btn-outline-primary btn-sm mt-2">
   View Job Posting
 </a>
 
          </div>
        </div>
        <div class="col-md-4">
          <img class="card-img card-img-right" src="{{ asset('assets/img/elements/17.jpg') }}" alt="Company Logo" />
        </div>
      </div>
    </div>
  </div>
</div>

<h5 class="pb-1 mb-4">Application Status</h5>
<div class="card mb-5 w-100">
  @if ($application->status === 'Under_assessment')
    <div class="card-body">
      <div class="alert alert-warning mb-0">
        This applicant is currently working on their assessment. Please wait for submission.
      </div>
    </div>
  @else
  <form action="{{ route('applications.updateStatus', $application->id) }}" method="POST" class="card-body">
    @csrf
    @method('PATCH')

      <div class="mb-3">
        <label class="form-label fw-bold">
          Current Status: <span class="text-primary">{{ $application->status }}</span>
        </label>
    </div>

      @if ($application->status === 'Pending')
        <button type="submit" name="status" value="Processed" class="btn btn-outline-primary">Move to Processed</button>
      @elseif ($application->status === 'Processed' || $application->status === 'Waiting_for_review')
      @if ($submission && $submission->submission_text)
  <div class="mb-4">
    <label class="form-label fw-bold">Applicant's Answer</label>
    <div class="border p-3 rounded bg-white" style="white-space: pre-wrap;">
      {!! $submission->submission_text !!}
    </div>
  </div>
@endif
@if ($submission && $submission->submission_file)
  <div class="mb-4">
    <label class="form-label fw-bold">Submitted File</label>
    <p>
      <a href="{{ asset('storage/' . $submission->submission_file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
        <i class="bx bx-download me-1"></i> Download File
      </a>
    </p>
  </div>
@endif

@php
  $nextStatus = match($application->status) {
    'Pending' => 'Processed',
    'Processed', 'Waiting_for_review' => 'Interview',
    'Interview' => null,
    default => null
  };
@endphp
        @if ($application->status === 'Waiting_for_review')
          <div class="mb-3">
      <label for="feedback" class="form-label fw-bold">Feedback</label>
            <textarea class="form-control w-75" id="feedback" name="feedback" rows="3" required>{{ old('feedback', $application->feedback) }}</textarea>
            <small class="text-muted">Please write a review before moving forward.</small>
    <!-- Feedback and Score -->
    <div id="feedback-section" class="mb-4" style="display: none;">
      <label for="score" class="form-label fw-bold">Score (1–100)</label>
      <input type="number" class="form-control w-50 mb-3" id="score" name="score" min="1" max="100" value="{{ $application->score }}">

      <label for="feedback" class="form-label fw-bold">Feedback</label>
      <textarea class="form-control w-75" id="feedback" name="feedback" rows="3">{{ old('feedback', $application->feedback) }}</textarea>
    </div>
          <!-- Interview Date (conditionally shown only when next status is Interview) -->
@if ($nextStatus === 'Interview')
  <div class="mb-4">
    <label for="interview-date" class="form-label fw-bold">Interview Date</label>
    <input type="date" name="interview_date" class="form-control w-50"
           value="{{ old('interview_date', $application->interview_date ? $application->interview_date->format('Y-m-d') : '') }}"
           required>
    <small class="text-muted">Choose the date for the applicant’s interview.</small>
  </div>
@endif
        @endif
        <button type="submit" name="status" value="Interview" class="btn btn-outline-success">Move to Interview</button>
      @elseif ($application->status === 'Interview')
        <div class="mb-3">
          <label class="form-label fw-bold">Interview Date</label>
          <input type="date" name="interview_date" class="form-control w-50" value="{{ $application->interview_date }}" required>
        </div>
        <div class="mt-3">
          <label class="form-label">Score</label>
          <input type="number" name="score" class="form-control w-50" value="{{ $application->score }}" min="1" max="100" required>
        </div>
        <div class="mt-3">
          <label class="form-label">Feedback</label>
          <textarea name="feedback" class="form-control w-75" rows="3" required>{{ old('feedback', $application->feedback) }}</textarea>
        </div>
        <div class="d-flex gap-2  mt-4">
          <button type="submit" name="status" value="Accepted" class="btn btn-success">Accept</button>
          <button type="submit" name="status" value="Rejected" class="btn btn-danger">Reject</button>
    </div>
      @endif
  </form>
  @endif
</div>



  

  
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.getElementById('status-dropdown');
    const interviewSection = document.getElementById('interview-date-section');
    const feedbackSection = document.getElementById('feedback-section');
    const scoreInputAlt = document.getElementById('score-alt');
    const feedbackInputAlt = document.getElementById('feedback-alt');
    const form = dropdown?.closest('form');

    function toggleSections() {
      const status = dropdown?.value;

      interviewSection.style.display = (status === 'Interview') ? 'block' : 'none';
      feedbackSection.style.display = (status === 'Accepted' || status === 'Rejected') ? 'block' : 'none';
    }

    function clearUnusedFields() {
      const status = dropdown?.value;

      if (status !== 'Interview') {
        document.getElementById('interview-date').value = '';
      }
      if (status !== 'Accepted' && status !== 'Rejected') {
        if (scoreInputAlt) scoreInputAlt.value = '';
        if (feedbackInputAlt) feedbackInputAlt.value = '';
      }
    }

    if (dropdown && form) {
    dropdown.addEventListener('change', toggleSections);
    toggleSections();
    clearUnusedFields();

    form.addEventListener('submit', function (e) {
  const status = dropdown.value;

  // Validate feedback and score for Accepted/Rejected
        if ((status === 'Accepted' || status === 'Rejected') && scoreInputAlt && feedbackInputAlt) {
          if (!scoreInputAlt.value.trim() || !feedbackInputAlt.value.trim()) {
      e.preventDefault();
      alert('Please fill out both score and feedback before applying status.');
      return;
    }
  }

        // Validate for Waiting_for_review status → require same fields
        if ('{{ $application->status }}' === 'Waiting_for_review') {
  const feedback = document.getElementById('feedback')?.value.trim();
  if (!feedback) {
    e.preventDefault();
    alert('Feedback is required to proceed from Waiting for Review.');
    return;
  }
}


  if (status === 'Interview') {
          const date = document.getElementById('interview-date')?.value.trim();
          if (!date) {
      e.preventDefault();
            alert('Please select an interview date.');
      return;
    }
  }
});
    }
  });
</script>
@endpush


