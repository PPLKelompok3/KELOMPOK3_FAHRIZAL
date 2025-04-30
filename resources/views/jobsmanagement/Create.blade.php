@extends('layouts.recruiter')

@section('title', 'Create Job')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h5>Create New Job</h5>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('jobs.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Job Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Employment Type</label>
        <select name="employment_type" class="form-control" required>
          <option value="full_time">Full-time</option>
          <option value="part_time">Part-time</option>
          <option value="internship">Internship</option>
          <option value="freelance">Freelance</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Skills Needed</label>
        <input id="job-skills-input" name="skills" class="form-control" placeholder="e.g. Laravel, SQL, AWS">
      </div>
      
      <div class="mb-3">
        <label class="form-label">Job Category</label>
        <input id="job-category-input" name="category" class="form-control" placeholder="e.g. Backend, Design, DevOps">
      </div>
      
      <div class="mb-3">
        <label class="form-label">Experience Required</label>
        <input type="text" name="experience_level" class="form-control" list="experience-options" placeholder="e.g. Fresh Graduate, 2+ years, Entry Level">
        <datalist id="experience-options">
          <option value="Fresh Graduate">
          <option value="1+ years">
          <option value="2+ years">
          <option value="3+ years">
          <option value="5+ years">
          <option value="Entry Level">
          <option value="Mid Level">
          <option value="Senior Level">
        </datalist>
      </div>
      

      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Salary Min (IDR)</label>
          <input type="number" name="salary_min" class="form-control" placeholder="e.g. 5000000">
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Salary Max (IDR)</label>
          <input type="number" name="salary_max" class="form-control" placeholder="e.g. 10000000">
        </div>
      </div>
      
      <div class="mb-3">
        <label class="form-label">Job Description</label>
        <textarea name="description" class="form-control" rows="5" required></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Post Job</button>
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
  // Initialize Tagify for Skills
  const jobSkillsInput = document.getElementById('job-skills-input');
  const tagifySkills = new Tagify(jobSkillsInput, {
    whitelist: [],
    dropdown: {
      enabled: 1,
      maxItems: 10,
      closeOnSelect: false
    }
  });

  tagifySkills.on('input', function(e) {
    let value = e.detail.value;
    fetch(`/api/skills/search?q=${value}`)
      .then(res => res.json())
      .then(data => {
        tagifySkills.settings.whitelist = data;
        tagifySkills.dropdown.show.call(tagifySkills, value);
      });
  });

  // Initialize Tagify for Category (basic set or new input)
  const categoryInput = document.getElementById('job-category-input');
  new Tagify(categoryInput, {
    enforceWhitelist: false,
    whitelist: ['Software Development', 'Design', 'Marketing', 'Management', 'DevOps'],
    dropdown: {
      enabled: 0 // show only on input
    }
  });
</script>
@endpush

