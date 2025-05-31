@extends('layouts.profile-setup')

@section('title', 'Edit Profile Picture')
@section('step-title', 'Update Profile Photo')
@section('step-desc', 'Upload and crop your profile picture.')

@section('content')
@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('userprofile.update-picture') }}">
  @csrf
  <input type="hidden" name="profile_picture_cropped" id="profile_picture_cropped">

  <div class="mb-3">
    <label for="profile_picture_input" class="form-label">Select a new photo</label>
    <input type="file" class="form-control" id="profile_picture_input" accept="image/*" required>
  </div>

  <div class="mt-3">
    <img id="profile-picture-preview"
         class="rounded-circle border"
         style="max-width: 150px;"
         src="{{ $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : asset('assets/img/default-avatar.jpg') }}"
         alt="Profile Preview">
    <p class="text-muted small mt-2">Please make sure your face is centered. A square crop will be used for circular display.</p>
  </div>

  <div class="d-flex justify-content-between mt-4">
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">← Back</a>
    <button type="submit" class="btn btn-primary">Save Photo</button>
  </div>
</form>
@endsection

@push('head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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

    cropBtn.addEventListener('click', function () {
      const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
        imageSmoothingQuality: 'high'
      });

      canvas.toBlob(blob => {
        const reader = new FileReader();
        reader.onloadend = () => {
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
