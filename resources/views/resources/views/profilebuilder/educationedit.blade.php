@extends('layouts.profile-setup')

@section('title', 'Edit Education')
@section('step-title', 'Edit: Education History')
@section('step-desc', 'Modify your education background.')

@section('content')
<form method="POST" action="{{ route('education.update') }}">
  @csrf

  <div id="education-container">
    @foreach($educations as $index => $edu)
    <div class="education-block mb-4" data-index="{{ $index }}">
      <h5>Education Entry <span class="entry-number">{{ $index + 1 }}</span></h5>

      <div class="mb-3">
        <label class="form-label">Institution</label>
        <input type="text" name="education[{{ $index }}][institution]" class="form-control institution-select" value="{{ $edu->institution_name }}" />
      </div>

      <div class="mb-3">
        <label class="form-label">Degree</label>
        <input type="text" name="education[{{ $index }}][degree]" class="form-control degree-select" value="{{ $edu->degree }}" />
      </div>

      <div class="mb-3">
        <label class="form-label">Field of Study</label>
        <input type="text" name="education[{{ $index }}][field]" class="form-control field-select" value="{{ $edu->field_of_study }}" />
      </div>

      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Start Date</label>
          <input type="date" class="form-control" name="education[{{ $index }}][start_date]" value="{{ $edu->start_date }}">
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">End Date</label>
          <input type="date" class="form-control" name="education[{{ $index }}][end_date]" value="{{ $edu->end_date }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="education[{{ $index }}][description]" rows="3">{{ $edu->description }}</textarea>
      </div>

      <button type="button" class="btn btn-danger btn-sm remove-education {{ $loop->first ? 'd-none' : '' }}">Remove</button>
      <hr>
    </div>
    @endforeach
  </div>

  <button type="button" id="add-education" class="btn btn-secondary mb-3">+ Add Another Education</button>

  <div class="d-flex justify-content-between">
    <a href="{{ url('/profile') }}" class="btn btn-outline-secondary">← Back</a>
    <button type="submit" class="btn" style="background-color: #ffc901; border: none; color: #000;">Save Changes</button>
  </div>
</form>
@endsection

@push('head')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
let educationIndex = {{ $educations->count() }};

function createEducationBlock(index) {
  const block = document.createElement('div');
  block.classList.add('education-block', 'mb-4');
  block.setAttribute('data-index', index);

  block.innerHTML = `
    <h5>Education Entry <span class="entry-number">${index + 1}</span></h5>
    <div class="mb-3">
      <label class="form-label">Institution</label>
      <input type="text" name="education[${index}][institution]" class="form-control institution-select" />
    </div>
    <div class="mb-3">
      <label class="form-label">Degree</label>
      <input type="text" name="education[${index}][degree]" class="form-control degree-select" />
    </div>
    <div class="mb-3">
      <label class="form-label">Field of Study</label>
      <input type="text" name="education[${index}][field]" class="form-control field-select" />
    </div>
    <div class="row">
      <div class="mb-3 col-md-6">
        <label class="form-label">Start Date</label>
        <input type="date" class="form-control" name="education[${index}][start_date]">
      </div>
      <div class="mb-3 col-md-6">
        <label class="form-label">End Date</label>
        <input type="date" class="form-control" name="education[${index}][end_date]">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="education[${index}][description]" rows="3"></textarea>
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

document.getElementById('add-education').addEventListener('click', () => {
  const container = document.getElementById('education-container');
  const newBlock = createEducationBlock(educationIndex);
  container.appendChild(newBlock);
  educationIndex++;
  initTomSelectWithin(newBlock, '.institution-select', '/api/educations/institutions/search');
  initTomSelectWithin(newBlock, '.degree-select', '/api/educations/degrees/search');
  initTomSelectWithin(newBlock, '.field-select', '/api/educations/fields/search');
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

document.addEventListener("DOMContentLoaded", function () {
  initTomSelectWithin(document, '.institution-select', '/api/educations/institutions/search');
  initTomSelectWithin(document, '.degree-select', '/api/educations/degrees/search');
  initTomSelectWithin(document, '.field-select', '/api/educations/fields/search');
});
</script>
@endpush
