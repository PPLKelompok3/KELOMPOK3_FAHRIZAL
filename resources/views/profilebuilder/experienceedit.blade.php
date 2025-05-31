@extends('layouts.profile-setup')

@section('title', 'Edit Experience')
@section('step-title', 'Edit Experience')
@section('step-desc', 'Modify your past work or organizational experiences.')

@section('content')
<form method="POST" action="{{ route('experience.update') }}">
  @csrf
  <div id="experience-container">
    @foreach ($experiences as $i => $exp)
      <div class="experience-block mb-4" data-index="{{ $i }}">
        <h5>Experience Entry <span class="entry-number">{{ $i + 1 }}</span></h5>

        <div class="mb-3">
          <label class="form-label">Organization</label>
          <input type="text" name="experience[{{ $i }}][organization]" class="form-control organization-select" value="{{ $exp->company_or_org }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Position / Role</label>
          <input type="text" name="experience[{{ $i }}][position]" class="form-control titles-select" value="{{ $exp->title }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Experience Type</label>
          <select name="experience[{{ $i }}][type]" class="form-control" required>
            @foreach(['internship','full_time','part_time','freelance','organization'] as $type)
              <option value="{{ $type }}" {{ $exp->type == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$type)) }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Location</label>
          <input type="text" name="experience[{{ $i }}][location]" class="form-control" value="{{ $exp->location }}">
        </div>

        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label">Start Date</label>
            <input type="date" name="experience[{{ $i }}][start_date]" class="form-control" value="{{ $exp->start_date }}">
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label">End Date</label>
            <input type="date" name="experience[{{ $i }}][end_date]" class="form-control" value="{{ $exp->end_date }}">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="experience[{{ $i }}][description]" class="form-control">{{ $exp->description }}</textarea>
        </div>

        <button type="button" class="btn btn-danger btn-sm remove-experience {{ $loop->first ? 'd-none' : '' }}">Remove</button>
        <hr>
      </div>
    @endforeach
  </div>

  <button type="button" id="add-experience" class="btn btn-secondary mb-3">+ Add Another Experience</button>

  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary">Save Changes</button>
  </div>
</form>

@endsection

@push('head')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
let experienceIndex = {{ count($experiences) }};

function createExperienceBlock(index) {
  const block = document.createElement('div');
  block.classList.add('experience-block', 'mb-4');
  block.setAttribute('data-index', index);

  block.innerHTML = `
    <h5>Experience Entry <span class="entry-number">${index + 1}</span></h5>
    <div class="mb-3">
      <label class="form-label">Organization</label>
      <input type="text" name="experience[${index}][organization]" class="form-control organization-select" placeholder="Type or select organization" />
    </div>
    <div class="mb-3">
      <label class="form-label">Position / Role</label>
      <input type="text" name="experience[${index}][position]" class="form-control titles-select" placeholder="e.g. Software Developer, Volunteer" />
    </div>
    <div class="mb-3">
  <label class="form-label">Experience Type</label>
  <select name="experience[${index}][type]" class="form-control" required>
    <option value="">Select Type</option>
    <option value="internship">Internship</option>
    <option value="full_time">Full-time</option>
    <option value="part_time">Part-time</option>
    <option value="freelance">Freelance</option>
    <option value="organization">Organization</option>
  </select>
</div>

<div class="mb-3">
        <label class="form-label">Location</label>
        <input type="text" name="experience[${index}][location]" class="form-control" placeholder="e.g. Jakarta, Remote">
      </div>

    <div class="row">
      <div class="mb-3 col-md-6">
        <label class="form-label">Start Date</label>
        <input type="date" class="form-control" name="experience[${index}][start_date]" required>
      </div>
      <div class="mb-3 col-md-6">
        <label class="form-label">End Date</label>
        <input type="date" class="form-control" name="experience[${index}][end_date]">
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="experience[${index}][description]" rows="3" placeholder="Describe your responsibilities or achievements (optional)"></textarea>
    </div>
    <button type="button" class="btn btn-danger btn-sm remove-experience">Remove</button>
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

document.getElementById('add-experience').addEventListener('click', function() {
  const container = document.getElementById('experience-container');
  const newBlock = createExperienceBlock(experienceIndex);
  container.appendChild(newBlock);
  experienceIndex++;

  initTomSelectWithin(newBlock, '.organization-select', '/api/experiences/organizations/search');
  initTomSelectWithin(newBlock, '.titles-select', '/api/experiences/titles/search');
});

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById('experience-container');
  initTomSelectWithin(form, '.organization-select', '/api/experiences/organizations/search');
  initTomSelectWithin(form, '.titles-select', '/api/experiences/titles/search');
});

document.getElementById('experience-container').addEventListener('click', function(e) {
  if (e.target && e.target.classList.contains('remove-experience')) {
    e.target.closest('.experience-block').remove();
    updateExperienceNumbers();
  }
});

function updateExperienceNumbers() {
  document.querySelectorAll('.experience-block').forEach((block, idx) => {
    block.setAttribute('data-index', idx);
    block.querySelector('.entry-number').textContent = idx + 1;
  });
}
</script>
@endpush
