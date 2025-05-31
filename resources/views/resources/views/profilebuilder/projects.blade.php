@extends('layouts.profile-setup')

@section('title', 'Step 5')
@section('step-title', 'Step 5: Projects')
@section('step-desc', 'Showcase the projects you’ve worked on and technologies you’ve used.')

@section('content')
<form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data">

  @csrf

  <div id="projects-container">
    <!-- First project block -->
    <div class="project-block mb-4" data-index="0">
      <h5>Project <span class="entry-number">1</span></h5>

      <div class="mb-3">
        <label class="form-label">Project Name</label>
        <input type="text" name="projects[0][name]" class="form-control" placeholder="e.g. Portfolio Website, Inventory App" required />
      </div>

      <div class="mb-3">
        <label class="form-label">Project Description</label>
        <textarea name="projects[0][description]" class="form-control" rows="3" placeholder="Describe your project (optional)"></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Technologies Used</label>
        <input name="projects[0][technologies_used]" class="form-control tagify-technologies" placeholder="e.g. Laravel, React, MySQL" />
      </div>

      <div class="mb-3">
        <label class="form-label">Project URL (optional)</label>
        <input type="url" name="projects[0][url]" class="form-control" placeholder="e.g. https://github.com/username/project" />
      </div>

      <button type="button" class="btn btn-danger btn-sm remove-project d-none">Remove</button>
      <hr>
    </div>
  </div>

  <button type="button" id="add-project" class="btn btn-secondary mb-3">+ Add Another Project</button>

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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
let projectIndex = 1;

function createProjectBlock(index) {
  const block = document.createElement('div');
  block.classList.add('project-block', 'mb-4');
  block.setAttribute('data-index', index);

  block.innerHTML = `
    <h5>Project <span class="entry-number">${index + 1}</span></h5>

    <div class="mb-3">
      <label class="form-label">Project Name</label>
      <input type="text" name="projects[${index}][name]" class="form-control" placeholder="e.g. Portfolio Website, Inventory App" required />
    </div>

    <div class="mb-3">
      <label class="form-label">Project Description</label>
      <textarea name="projects[${index}][description]" class="form-control" rows="3" placeholder="Describe your project (optional)"></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Technologies Used</label>
      <input name="projects[${index}][technologies_used]" class="form-control tagify-technologies" placeholder="e.g. Laravel, React, MySQL" />
    </div>

    <div class="mb-3">
      <label class="form-label">Project URL (optional)</label>
      <input type="url" name="projects[${index}][url]" class="form-control" placeholder="e.g. https://github.com/username/project" />
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
      whitelist: [],
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
  const form = document;
  initTagifyOn(form);
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
