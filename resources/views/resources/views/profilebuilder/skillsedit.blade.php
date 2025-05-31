@extends('layouts.profile-setup')

@section('title', 'Edit Skills')
@section('step-title', 'Edit Your Skills')
@section('step-desc', 'Update your skills. Add or remove as needed.')

@section('content')
<form method="POST" action="{{ route('skills.update') }}">
  @csrf

  <div class="mb-3">
    <label for="skills-input" class="form-label">Your Skills</label>
    <input id="skills-input" name="skills" class="form-control" placeholder="Type a skill and press Enter..." />
  </div>

  <button type="submit" class="btn btn-warning">Save Changes</button>
</form>
@endsection

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
  const input = document.querySelector('#skills-input');
  const tagify = new Tagify(input, {
    enforceWhitelist: false,
    dropdown: { enabled: 1, maxItems: 10, closeOnSelect: false }
  });

  // Preload user skills
  const existingSkills = @json($skillNames);
  tagify.addTags(existingSkills);

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

  document.querySelector('form').addEventListener('submit', function(e) {
    document.querySelectorAll('input[name="skills[]"]').forEach(el => el.remove());

    const tagValues = tagify.value.map(tag => tag.value);
    tagValues.forEach(skill => {
      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'skills[]';
      hidden.value = skill;
      this.appendChild(hidden);
    });
  });
</script>
@endpush
