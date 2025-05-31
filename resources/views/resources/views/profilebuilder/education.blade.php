@extends('layouts.profile-setup')

@section('title', 'Step 3')
@section('step-title', 'Step 3: Education History')
@section('step-desc', 'Add your educational background. You may add multiple entries.')

@section('content')
<form method="POST" action="{{ route('education.store') }}">
  @csrf

  <div id="education-container">
    <!-- First education block -->
    <div class="education-block mb-4" data-index="0">
      <h5>Education Entry <span class="entry-number">1</span></h5>
      <div class="mb-3">
        <label class="form-label">Institution</label>
        <input type="text" name="education[0][institution]" class="form-control institution-select" placeholder="Type or select institution" />
      </div>

      <div class="mb-3">
        <label class="form-label">Degree</label>
        <input type="text" name="education[0][degree]" class="form-control degree-select" placeholder="Type or select degree" />
      </div>

      <div class="mb-3">
        <label class="form-label">Field of Study</label>
        <input type="text" name="education[0][field]" class="form-control field-select" placeholder="Type or select field of study" />
      </div>

      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Start Date</label>
          <input type="date" class="form-control" name="education[0][start_date]" id="education_0_start_date" required>
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">End Date</label>
          <input type="date" class="form-control" name="education[0][end_date]" id="education_0_end_date">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="education[0][description]" id="education_0_description" rows="3" placeholder="Additional details (optional)"></textarea>
      </div>

      <button type="button" class="btn btn-danger btn-sm remove-education d-none">Remove</button>
      <hr>
    </div>
  </div>

  <button type="button" id="add-education" class="btn btn-secondary mb-3">+ Add Another Education</button>
  
  <div class="d-flex justify-content-between">
    <a href="/profile/setup/stepX" class="btn btn-outline-secondary">
      ← Back
    </a>
  
    <button type="submit" class="btn" style="background-color: #ffc901; border: none; color: #000;">
      Continue →
    </button>
  </div>
  
</form>
@endsection

@push('head')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
let educationIndex = 1;

function createEducationBlock(index) {
  const block = document.createElement('div');
  block.classList.add('education-block', 'mb-4');
  block.setAttribute('data-index', index);

  block.innerHTML = `
    <h5>Education Entry <span class="entry-number">${index + 1}</span></h5>
    <div class="mb-3">
      <label class="form-label">Institution</label>
      <input type="text" name="education[${index}][institution]" class="form-control institution-select" placeholder="Type or select institution" />
    </div>
    <div class="mb-3">
      <label class="form-label">Degree</label>
      <input type="text" name="education[${index}][degree]" class="form-control degree-select" placeholder="Type or select degree" />
    </div>
    <div class="mb-3">
      <label class="form-label">Field of Study</label>
      <input type="text" name="education[${index}][field]" class="form-control field-select" placeholder="Type or select field of study" />
    </div>
    <div class="row">
      <div class="mb-3 col-md-6">
        <label class="form-label">Start Date</label>
        <input type="date" class="form-control" name="education[${index}][start_date]" id="education_${index}_start_date" required>
      </div>
      <div class="mb-3 col-md-6">
        <label class="form-label">End Date</label>
        <input type="date" class="form-control" name="education[${index}][end_date]" id="education_${index}_end_date">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="education[${index}][description]" id="education_${index}_description" rows="3" placeholder="Additional details (optional)"></textarea>
    </div>
    <button type="button" class="btn btn-danger btn-sm remove-education">Remove</button>
    <hr>`;

  return block;
}

function initTomSelectWithin(container, selector, apiUrl) {
  container.querySelectorAll(selector).forEach(input => {
    if (input.tomselect) return;
    new TomSelect(input, {
      create: true,
      maxItems: 1,
      valueField: 'value',
      labelField: 'value',
      searchField: 'value',
      load: function(query, callback) {
        if (!query.length) return callback();
        fetch(`${apiUrl}?q=${encodeURIComponent(query)}`)
          .then(response => response.json())
          .then(callback).catch(() => callback());
      }
    });
  });
}

document.getElementById('add-education').addEventListener('click', function() {
  const container = document.getElementById('education-container');
  const newBlock = createEducationBlock(educationIndex);
  container.appendChild(newBlock);
  educationIndex++;

  initTomSelectWithin(newBlock, '.institution-select', '/api/educations/institutions/search');
  initTomSelectWithin(newBlock, '.degree-select', '/api/educations/degrees/search');
  initTomSelectWithin(newBlock, '.field-select', '/api/educations/fields/search');
});

document.addEventListener("DOMContentLoaded", function () {
  const form = document;
  initTomSelectWithin(form, '.institution-select', '/api/educations/institutions/search');
  initTomSelectWithin(form, '.degree-select', '/api/educations/degrees/search');
  initTomSelectWithin(form, '.field-select', '/api/educations/fields/search');
});

document.getElementById('education-container').addEventListener('click', function(e) {
  if (e.target && e.target.classList.contains('remove-education')) {
    e.target.closest('.education-block').remove();
    updateEntryNumbers();
  }
});

function updateEntryNumbers() {
  document.querySelectorAll('.education-block').forEach((block, idx) => {
    block.setAttribute('data-index', idx);
    block.querySelector('.entry-number').textContent = idx + 1;
  });
}
</script>
@endpush