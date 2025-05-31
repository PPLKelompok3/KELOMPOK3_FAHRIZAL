@extends('layouts.admin') {{-- or layouts.app/admin if you prefer --}}

@section('title', 'Create Article')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h5>Create New Article</h5>
  </div>
  <div class="card-body">
    @if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

    <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
  @csrf


      <div class="mb-3">
        <label class="form-label">Article Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Skills Related</label>
        <input id="article-skills-input" name="skills" class="form-control" placeholder="e.g. Laravel, SQL, UI/UX">
      </div>

      <div class="mb-3">
        <label class="form-label">Thumbnail Image (Optional)</label>
        <input type="file" name="thumbnail" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Header Image (Optional)</label>
        <input type="file" name="header_image" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Article Content</label>
        <textarea name="content" id="article-content" class="form-control" rows="10"></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Publish Article</button>
    </form>
  </div>
</div>
@endsection

@push('head')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
<script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<script>
  // Tagify for Skills
  const skillsInput = document.getElementById('article-skills-input');
  const tagifySkills = new Tagify(skillsInput, {
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

  // TinyMCE
  tinymce.init({
    selector: '#article-content',
    menubar: false,
    height: 400,
    plugins: 'link image code lists preview',
    toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | link image | code preview',
    content_css: false
  });
</script>
@endpush
