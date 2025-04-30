@extends('layouts.profile-setup')

@section('title', 'Step 1')
@section('step-title', 'Step 1: Basic Info')
@section('step-desc', 'Let us know your general information.')

@section('content')
<form method="POST" action="{{ route('userprofile.store') }}" enctype="multipart/form-data">
  @csrf

  <!-- Location -->
  <div class="mb-3">
    <label for="location" class="form-label">Location</label>
    <input type="text" class="form-control" id="location" name="location" placeholder="Start typing your city..." required>
  </div>

  <!-- Birth Date -->
  <div class="mb-3">
    <label for="birth_date" class="form-label">Birth Date</label>
    <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
  </div>

  <!-- Phone -->
  <div class="mb-3">
    <label for="phone" class="form-label">Phone Number</label>
    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
  </div>

  <!-- CV -->
  <div class="mb-3">
    <label for="cv" class="form-label">Upload CV</label>
    <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,.doc,.docx">
  </div>

  <!-- Bio -->
  <div class="mb-3">
    <label for="bio" class="form-label">Short Bio</label>
    <textarea class="form-control" id="bio" name="bio" rows="3">{{ old('bio') }}</textarea>
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

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsksri4g-zvxWtM_sbvjGjpRU8rPlPy5I&libraries=places&callback=initAutocomplete" async defer></script>
<script>
  function initAutocomplete() {
    const input = document.getElementById('location');
    const options = {
      types: ['(cities)'],
      componentRestrictions: { country: 'id' }
    };
    new google.maps.places.Autocomplete(input, options);
  }
</script>
@endpush
