@extends('layouts.profile-setup')

@section('title', 'Select Company')
@section('step-title', 'Step 1: Choose or Add Company')
@section('step-desc', 'Search for your company or create a new one.')

@section('content')
<form method="POST" action="{{ route('recruiter.company.check') }}">
  @csrf

  <div class="mb-3">
    <label for="company_name" class="form-label">Company Name</label>
    <input type="text" id="company_name_input" name="company_name" placeholder="Type or select company..." class="form-control" required>
  </div>

  <button type="submit" class="btn d-grid w-100" style="background-color: #ffc901; border: none; color: #000;">
    Continue →
  </button>
</form>
@endsection

@push('head')
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

  <script>
    new TomSelect('#company_name_input', {
      create: true,
      maxItems: 1,
      valueField: 'company_name',
      labelField: 'company_name',
      searchField: 'company_name',
      placeholder: 'Search for your company or type a new one...',
      load: function(query, callback) {
        if (!query.length) return callback();

        fetch(`/api/companies/search?q=${encodeURIComponent(query)}`)
          .then(res => res.json())
          .then(callback)
          .catch(() => callback());
      }
    });
  </script>
@endpush
