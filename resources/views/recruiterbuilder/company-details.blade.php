@extends('layouts.profile-setup')

@section('title', 'Company Profile')
@section('step-title', 'Setup Company Profile')
@section('step-desc', 'Search for an existing company or register a new one.')

@section('content')
<form method="POST" action="{{ route('recruiter.profile.store') }}" enctype="multipart/form-data">
    @csrf
  
    <input type="hidden" name="company_name" value="{{ $company_name }}">
  
    <div class="mb-3">
      <label class="form-label">Industry</label>
      <input type="text" name="industry" class="form-control" />
    </div>
  
    <div class="mb-3">
      <label class="form-label">Website</label>
      <input type="url" name="website" class="form-control" />
    </div>
  
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="company_description" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Company Logo (optional)</label>
        <input type="file" name="logo" class="form-control" accept="image/*" />
      </div>
      
  
    <button class="btn btn-primary w-100">Create Company & Go to Dashboard</button>
  </form>
  

@endsection

@push('head')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
  // Company Name Select
  new TomSelect('#company_name_input', {
    create: true,
    maxItems: 1,
    valueField: 'company_name',
    labelField: 'company_name',
    searchField: 'company_name',
    placeholder: 'Search or add a company...',
    load: function(query, callback) {
      if (!query.length) return callback();
      fetch(`/api/companies/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(callback)
        .catch(() => callback());
    },
    onItemAdd(value, item) {
      // Optional: could auto-hide the fields if company is known
      console.log('Selected or created company:', value);
    }
  });

  // Industry Tags
  new TomSelect('#industry_input', {
    create: true,
    maxItems: 1,
    placeholder: 'Select or enter an industry',
    options: [
      { value: 'Technology', text: 'Technology' },
      { value: 'Finance', text: 'Finance' },
      { value: 'Healthcare', text: 'Healthcare' },
      { value: 'Education', text: 'Education' },
      { value: 'Retail', text: 'Retail' },
    ]
  });
</script>
@endpush
