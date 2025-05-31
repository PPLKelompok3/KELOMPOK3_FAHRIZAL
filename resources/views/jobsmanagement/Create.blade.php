@extends('layouts.recruiter')

@section('title', 'Create Job')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h5>Create New Job</h5>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('jobs.store') }}" enctype="multipart/form-data">
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
      <div class="card mt-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0">Optional Assessment</h6>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="enable-assessment">
      <label class="form-check-label" for="enable-assessment">Enable</label>
    </div>
  </div>
  <div class="card-body" id="assessment-section" style="display: none;">
    <div class="mb-3">
      <label class="form-label">Assessment Type</label>
      <select class="form-control" name="assessment_type" id="assessment-type">
        <option value="">Select Type</option>
        <option value="essay">Essay</option>
        <option value="file_upload">File Upload</option>
      </select>
    </div>

    <div class="mb-3 d-none" id="essay-editor-wrapper">
      <label class="form-label">Essay Question</label>
      <textarea name="essay_questions" id="essay_editor" class="form-control"></textarea>
    </div>

    <div class="mb-3 d-none" id="file-upload-wrapper">
      <label class="form-label">Instructions</label>
      <textarea name="file_instruction" class="form-control" rows="3"></textarea>
      <label class="form-label mt-3">Optional Guide File (PDF or DOCX)</label>
      <input type="file" name="file_guide" class="form-control" accept=".pdf,.docx">
    </div>

    <div class="mb-3">
  <label class="form-label">Assessment Deadline</label>
  <input type="number" name="assessment_due_in_days" class="form-control" min="1"
         placeholder="e.g. 3 (means 3 days after applicant applies)"
         value="{{ old('assessment_due_in_days', $assessment?->due_in_days) }}">
</div>

  </div>
</div>


      <button type="submit" class="btn btn-primary mt-4">Post Job</button>
      @if ($errors->any())
  <div class="alert alert-danger">
    <strong>There were some errors with your submission:</strong>
    <ul class="mb-0 mt-1">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

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
<script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
<script>
  let essayEditorInitialized = false;

  tinymce.init({
    selector: '#essay_editor',
    height: 300,
    menubar: false,
    plugins: 'lists link code',
    toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
    branding: false,
    setup: editor => {
      essayEditorInitialized = true;
    }
  });

  document.getElementById('enable-assessment').addEventListener('change', function () {
    const section = document.getElementById('assessment-section');
    section.style.display = this.checked ? 'block' : 'none';
  });

  document.getElementById('assessment-type').addEventListener('change', function () {
    const type = this.value;
    document.getElementById('essay-editor-wrapper').classList.toggle('d-none', type !== 'essay');
    document.getElementById('file-upload-wrapper').classList.toggle('d-none', type !== 'file_upload');

    if (type === 'essay' && !essayEditorInitialized) {
      tinymce.init({
        selector: '#essay_editor',
        height: 300,
        menubar: false,
        plugins: 'lists link code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
        branding: false
      });
    }
  });
</script>

@endpush

