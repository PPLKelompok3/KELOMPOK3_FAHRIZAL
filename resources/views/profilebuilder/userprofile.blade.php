@extends('layouts.profile-setup')

@section('title', 'Step 1')
@section('step-title', 'Step 1: Basic Info')
@section('step-desc', 'Let us know your general information.')

@section('content')
<form method="POST" action="{{ route('userprofile.store') }}" enctype="multipart/form-data">
  @csrf
  @if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif


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
  <!-- Profile Picture Upload with Crop -->
  <div class="mb-3">
    <label class="form-label">Profile Picture</label>
    <input type="file" id="profile_picture_input" class="form-control" accept="image/*" required>
    <input type="hidden" name="profile_picture_cropped" id="profile_picture_cropped">

    <div class="mt-3">
      <img id="profile-picture-preview" class="rounded-circle border" style="max-width: 150px;" alt="Preview">
      <p class="text-muted small mt-2">Your face should be visible and centered. A square crop will be used for a circular display.</p>
    </div>
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
<!-- Cropper Modal -->


@endsection
@push('head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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
<script>
 document.addEventListener('DOMContentLoaded', function () {
  let cropper;

  const input = document.getElementById('profile_picture_input');
  const preview = document.getElementById('profile-picture-preview');
  const modalEl = document.getElementById('cropModal');
  const cropBtn = document.getElementById('crop-confirm');

  input.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (event) {
      const image = document.getElementById('cropper-image');
      image.src = event.target.result;

      const modal = new bootstrap.Modal(modalEl);
      modal.show();

      modalEl.addEventListener('shown.bs.modal', () => {
        cropper = new Cropper(image, {
          aspectRatio: 1,
          viewMode: 1,
          dragMode: 'move',
          cropBoxResizable: false,
          cropBoxMovable: false,
          background: false
        });
      }, { once: true });
    };
    reader.readAsDataURL(file);
  });
  console.log('Crop modal setup script running');


  cropBtn.addEventListener('click', function () {
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({
      width: 300,
      height: 300,
      imageSmoothingQuality: 'high'
    });

    canvas.toBlob(function (blob) {
      const reader = new FileReader();
      reader.onloadend = function () {
        document.getElementById('profile_picture_cropped').value = reader.result;
        preview.src = reader.result;
      };
      reader.readAsDataURL(blob);
    });

    bootstrap.Modal.getInstance(modalEl).hide();
    cropper.destroy();
    cropper = null;

  });
});

</script>
@endpush
