@extends('layouts.profile-setup')

@section('title', 'Step 2')
@section('step-title', 'Step 2: Skills')
@section('step-desc', 'Add all the skills you have. You can type and hit Enter.')

@section('content')
<form method="POST" action="{{ route('skills.store') }}">
  @csrf

  <div class="mb-3">
    <label for="skills-input" class="form-label">Your Skills</label>
    <input id="skills-input" name="skills" class="form-control" placeholder="Type a skill and press Enter..." />
  </div>

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
<!-- Tagify CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" />
@endpush

@push('scripts')
<!-- Tagify JS -->
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
  const input = document.querySelector('#skills-input');

  const tagify = new Tagify(input, {
    enforceWhitelist: false,
    dropdown: {
      enabled: 1,
      maxItems: 10,
      position: 'text',
      closeOnSelect: false
    }
  });

  // Autocomplete from DB
  tagify.on('input', function(e) {
    const value = e.detail.value;

    fetch(`/api/skills/search?q=${value}`)
      .then(res => res.json())
      .then(data => {
        tagify.settings.whitelist = data;
        tagify.dropdown.show.call(tagify, value);
      });
  });

  const form = document.querySelector('form');
  form.addEventListener('submit', function(e) {
    document.querySelectorAll('input[name="skills[]"]').forEach(el => el.remove());

    const tagValues = tagify.value.map(tag => tag.value);

    tagValues.forEach(skill => {
      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'skills[]';
      hidden.value = skill;
      form.appendChild(hidden);
    });
  });
</script>


@endpush



<style>
  .tag {
    display: inline-block;
    background-color: #ffc901;
    color: #000;
    padding: 5px 10px;
    border-radius: 20px;
    margin: 5px 5px 0 0;
    font-size: 14px;
  }
  .tag .remove-tag {
    margin-left: 8px;
    cursor: pointer;
    font-weight: bold;
  }
</style>

