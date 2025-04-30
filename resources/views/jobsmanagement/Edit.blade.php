@extends('layouts.recruiter')

@section('title', 'Edit Job')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h5>Edit Job</h5>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('jobs.update', $job->id) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Job Title</label>
        <input type="text" name="title" class="form-control" value="{{ $job->title }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Employment Type</label>
        <select name="employment_type" class="form-control" required>
          @foreach (['full_time', 'part_time', 'internship', 'freelance'] as $type)
            <option value="{{ $type }}" {{ $job->employment_type === $type ? 'selected' : '' }}>
              {{ ucfirst(str_replace('_', ' ', $type)) }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control" value="{{ $job->location }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Skills Needed</label>
        <input id="job-skills-input" name="skills" class="form-control"
               value='@json(explode(",", $job->skills))'>
      </div>

      <div class="mb-3">
        <label class="form-label">Job Category</label>
        <input id="job-category-input" name="category" class="form-control"
               value='@json([["value" => $job->category]])'>
      </div>

      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Salary Min (IDR)</label>
          <input type="number" name="salary_min" class="form-control" value="{{ $job->salary_min }}">
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Salary Max (IDR)</label>
          <input type="number" name="salary_max" class="form-control" value="{{ $job->salary_max }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Job Description</label>
        <textarea name="description" class="form-control" rows="5" required>{{ $job->description }}</textarea>
      </div>

      <button type="submit" class="btn btn-primary">Update Job</button>
    </form>
  </div>
</div>
<div class="card mt-4">
    <h5 class="card-header">Toggle Job Status</h5>
    <div class="card-body">
      <div class="alert alert-info">
        <h6 class="alert-heading fw-bold mb-1">Current Status: <span class="badge bg-{{ $job->status === 'Active' ? 'success' : 'secondary' }}">{{ $job->status }}</span></h6>
        <p class="mb-0">You can {{ $job->status === 'Active' ? 'deactivate' : 'activate' }} this job anytime.</p>
      </div>
  
      <form method="POST" action="{{ route('jobs.toggle-status', $job->id) }}">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn {{ $job->status === 'Active' ? 'btn-warning' : 'btn-success' }}">
          {{ $job->status === 'Active' ? 'Deactivate' : 'Activate' }} Job
        </button>
      </form>
    </div>
  </div>
  

{{-- Delete Confirmation --}}
<div class="card mt-4">
  <h5 class="card-header">Delete Job</h5>
  <div class="card-body">
    <div class="alert alert-warning">
      <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete this job?</h6>
      <p class="mb-0">Once deleted, this job cannot be recovered.</p>
    </div>
    <form method="POST" action="{{ route('jobs.destroy', $job->id) }}">
      @csrf
      @method('DELETE')
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
        <label class="form-check-label" for="confirmDelete">
          I confirm deletion of this job
        </label>
      </div>
      <button type="submit" class="btn btn-danger">Delete Job</button>
    </form>
  </div>
</div>
@endsection

@push('head')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<script>
  // Skills
  const skillInput = document.getElementById('job-skills-input');
  const tagifySkills = new Tagify(skillInput, {
    whitelist: [],
    dropdown: {
      enabled: 1,
      maxItems: 10,
      closeOnSelect: false
    }
  });

  tagifySkills.on('input', e => {
    fetch(`/api/skills/search?q=${e.detail.value}`)
      .then(res => res.json())
      .then(data => {
        tagifySkills.settings.whitelist = data;
        tagifySkills.dropdown.show.call(tagifySkills, e.detail.value);
      });
  });

  // Category
  const categoryInput = document.getElementById('job-category-input');
  new Tagify(categoryInput, {
    enforceWhitelist: false,
    whitelist: ['Software Development', 'Design', 'Marketing', 'Management', 'DevOps'],
    dropdown: { enabled: 0 }
  });
</script>
@endpush
