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

<h5 class="pb-1 mb-4">Status and Interview Schedule</h5>
<div class="row mb-5 d-flex align-items-stretch">
    <!-- Status and Action Card -->
    <div class="col-md-6 d-flex">
      <div class="card mb-3 w-100 h-100">
        <div class="card-body text-center">
          <h5 class="card-title mb-4">Current Status</h5>
  
          <h2 id="current-status" class="text-primary mb-4" style="font-weight: bold;">
            {{ $application->status }}
          </h2>
          
  
          <div class="mb-3">
            <form action="{{ route('applications.updateStatus', $application->id) }}" method="POST">
                @csrf
                @method('PATCH')
              
                <select name="status" class="form-select mb-3" id="status-dropdown">
                  <option value="Pending" {{ $application->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                  <option value="Processed" {{ $application->status == 'Processed' ? 'selected' : '' }}>Processed</option>
                  <option value="Interview" {{ $application->status == 'Interview' ? 'selected' : '' }}>Interview</option>
                  <option value="Accepted" {{ $application->status == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                  <option value="Rejected" {{ $application->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
              
                <button type="submit" class="btn btn-primary mt-2">
                  Apply Status
                </button>
              </form> 
          </div>
        </div>
      </div>
    </div>
  
    <!-- Interview Date Picker Card -->
    <div class="col-md-6 d-flex">
        <div class="card mb-3 w-100 h-100">
          <div class="card-body text-center" id="calendar-card">
          <h5 class="card-title mb-4">Interview Schedule</h5>
          <input type="date" id="interview-date" class="form-control mb-3"
       value="{{ $application->interview_date }}" 
       {{ $application->status == 'Interview' ? '' : 'disabled' }}>

  
          <p class="card-text">
            <small class="text-muted">Pick an interview date once status is set to Interview.</small>
          </p>
        </div>
      </div>
    </div>
  </div>
  
@endsection

@push('scripts')
<script>
    document.getElementById('apply-status-btn').addEventListener('click', function() {
      const selectedStatus = document.getElementById('status-dropdown').value;
      const statusElement = document.getElementById('current-status');
      const calendarCard = document.getElementById('calendar-card');
      const interviewDateInput = document.getElementById('interview-date');
    
      // Update text
      statusElement.textContent = selectedStatus;
    
      // Color change for "Accepted" or "Rejected"
      if (selectedStatus === "Accepted") {
        statusElement.className = 'text-success';
      } else if (selectedStatus === "Rejected") {
        statusElement.className = 'text-danger';
      } else {
        statusElement.className = 'text-primary';
      }
    
      // Enable calendar only if Interview
      if (selectedStatus === "Interview") {
        calendarCard.style.opacity = "1";
        interviewDateInput.disabled = false;
      } else {
        calendarCard.style.opacity = "0.5";
        interviewDateInput.disabled = true;
      }
    });
    </script>
@endpush