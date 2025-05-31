@extends('layouts.profile-setup')

@section('title', 'Edit Projects')
@section('step-title', 'Edit Projects')
@section('step-desc', 'Update your listed projects and technologies.')

@section('content')
<form method="POST" action="{{ route('projects.update') }}">
  @csrf


  <div id="projects-container">
    @foreach ($projects as $index => $proj)
    <div class="project-block mb-4" data-index="{{ $index }}">
      <h5>Project <span class="entry-number">{{ $index + 1 }}</span></h5>

      <div class="mb-3">
        <label class="form-label">Project Name</label>
        <input type="text" name="projects[{{ $index }}][name]" class="form-control" value="{{ $proj->title }}" required />
      </div>

      <div class="mb-3">
        <label class="form-label">Project Description</label>
        <textarea name="projects[{{ $index }}][description]" class="form-control" rows="3">{{ $proj->description }}</textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Technologies Used</label>
        <input name="projects[{{ $index }}][technologies_used]" class="form-control tagify-technologies" value='@json(collect(explode(",", $proj->technologies_used))->map(fn($tech) => ["value" => trim($tech)]))' />
      </div>

      <div class="mb-3">
        <label class="form-label">Project URL (optional)</label>
        <input type="url" name="projects[{{ $index }}][url]" class="form-control" value="{{ $proj->link }}" />
      </div>

      <button type="button" class="btn btn-danger btn-sm remove-project {{ $index === 0 ? 'd-none' : '' }}">Remove</button>
      <hr>
    </div>
    @endforeach
  </div>

  <button type="button" id="add-project" class="btn btn-secondary mb-3">+ Add Another Project</button>

  <div class="d-flex justify-content-between">
    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">← Back</a>
    <button type="submit" class="btn" style="background-color: #ffc901; border: none; color: #000;">Save Changes</button>
  </div>
</form>
@endsection

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
let projectIndex = {{ count($projects) }};

function createProjectBlock(index) {
  const block = document.createElement('div');
  block.classList.add('project-block', 'mb-4');
  block.setAttribute('data-index', index);

  block.innerHTML = `
    <h5>Project <span class="entry-number">${index + 1}</span></h5>

    <div class="mb-3">
      <label class="form-label">Project Name</label>
      <input type="text" name="projects[${index}][name]" class="form-control" required />
    </div>

    <div class="mb-3">
      <label class="form-label">Project Description</label>
      <textarea name="projects[${index}][description]" class="form-control" rows="3"></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Technologies Used</label>
      <input name="projects[${index}][technologies_used]" class="form-control tagify-technologies" />
    </div>

    <div class="mb-3">
      <label class="form-label">Project URL (optional)</label>
      <input type="url" name="projects[${index}][url]" class="form-control" />
    </div>

    <button type="button" class="btn btn-danger btn-sm remove-project">Remove</button>
    <hr>
  `;
  return block;
}

function initTagifyOn(container) {
  container.querySelectorAll('.tagify-technologies').forEach(input => {
    if (input.tagify) return;
    const tagify = new Tagify(input, {
      enforceWhitelist: false,
      dropdown: {
        enabled: 1,
        maxItems: 10,
        position: "text",
        closeOnSelect: false
      }
    });

    tagify.on('input', function(e) {
      const value = e.detail.value;
      fetch(`/api/projects/technologies/search?q=${encodeURIComponent(value)}`)
        .then(res => res.json())
        .then(suggestions => {
          tagify.settings.whitelist = suggestions;
          tagify.dropdown.show.call(tagify, value);
        });
    });
  });
}

document.addEventListener("DOMContentLoaded", function () {
  initTagifyOn(document);
});

document.getElementById('add-project').addEventListener('click', function () {
  const container = document.getElementById('projects-container');
  const newBlock = createProjectBlock(projectIndex);
  container.appendChild(newBlock);
  projectIndex++;
  initTagifyOn(newBlock);
});

document.getElementById('projects-container').addEventListener('click', function (e) {
  if (e.target && e.target.classList.contains('remove-project')) {
    e.target.closest('.project-block').remove();
    updateProjectNumbers();
  }
});

function updateProjectNumbers() {
  document.querySelectorAll('.project-block').forEach((block, idx) => {
    block.setAttribute('data-index', idx);
    block.querySelector('.entry-number').textContent = idx + 1;
  });
}
</script>
@endpush
