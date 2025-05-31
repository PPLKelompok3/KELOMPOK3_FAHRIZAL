@extends('layouts.profile-setup')

@section('title', 'Edit Achievements')
@section('step-title', 'Edit Achievements')
@section('step-desc', 'Update your list of achievements and upload certificates.')

@section('content')
<form method="POST" action="{{ route('achievements.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div id="achievements-container">
    @foreach ($achievements as $index => $achievement)
      <div class="achievement-block mb-4" data-index="{{ $index }}">
        <h5>Achievement <span class="entry-number">{{ $index + 1 }}</span></h5>

        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" name="achievements[{{ $index }}][title]" class="form-control"
            value="{{ $achievement->title }}" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Issuer / Organizer</label>
          <input type="text" name="achievements[{{ $index }}][issuer]" class="form-control"
            value="{{ $achievement->issuer }}" />
        </div>

        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label">Date Achieved</label>
            <input type="date" name="achievements[{{ $index }}][date_awarded]" class="form-control"
              value="{{ $achievement->date_awarded }}" />
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label">Certificate (optional)</label>
            <input type="file" name="achievements[{{ $index }}][certificate]" class="form-control"
              accept=".pdf,.jpg,.jpeg,.png" />
            @if ($achievement->certificate)
              <a href="{{ asset('storage/' . $achievement->certificate) }}" target="_blank" class="mt-1 d-block">
                View current certificate
              </a>
            @endif
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Description (optional)</label>
          <textarea name="achievements[{{ $index }}][description]" class="form-control" rows="3">{{ $achievement->description }}</textarea>
        </div>

        <button type="button" class="btn btn-danger btn-sm remove-achievement">Remove</button>
        <hr>
      </div>
    @endforeach
  </div>

  <button type="button" id="add-achievement" class="btn btn-secondary mb-3">+ Add Another Achievement</button>

  <div class="d-flex justify-content-between">
    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">← Cancel</a>
    <button type="submit" class="btn" style="background-color: #ffc901; border: none; color: #000;">
      Save Changes
    </button>
  </div>
</form>
@endsection

@push('scripts')
<script>
let achievementIndex = {{ count($achievements) }};

function createAchievementBlock(index) {
  return `
    <div class="achievement-block mb-4" data-index="${index}">
      <h5>Achievement <span class="entry-number">${index + 1}</span></h5>
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="achievements[${index}][title]" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Issuer / Organizer</label>
        <input type="text" name="achievements[${index}][issuer]" class="form-control" />
      </div>
      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Date Achieved</label>
          <input type="date" name="achievements[${index}][date_awarded]" class="form-control" />
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Certificate (optional)</label>
          <input type="file" name="achievements[${index}][certificate]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" />
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Description (optional)</label>
        <textarea name="achievements[${index}][description]" class="form-control" rows="3"></textarea>
      </div>
      <button type="button" class="btn btn-danger btn-sm remove-achievement">Remove</button>
      <hr>
    </div>
  `;
}

document.getElementById('add-achievement').addEventListener('click', function () {
  const container = document.getElementById('achievements-container');
  const newBlock = createAchievementBlock(achievementIndex);
  container.insertAdjacentHTML('beforeend', newBlock);
  achievementIndex++;
  updateAchievementNumbers();
});

document.getElementById('achievements-container').addEventListener('click', function (e) {
  if (e.target.classList.contains('remove-achievement')) {
    e.target.closest('.achievement-block').remove();
    updateAchievementNumbers();
  }
});

function updateAchievementNumbers() {
  document.querySelectorAll('.achievement-block').forEach((block, idx) => {
    block.dataset.index = idx;
    block.querySelector('.entry-number').textContent = idx + 1;
  });
}
</script>
@endpush
