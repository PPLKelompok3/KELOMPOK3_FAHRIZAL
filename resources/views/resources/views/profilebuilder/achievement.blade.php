@extends('layouts.profile-setup')

@section('title', 'Step 6')
@section('step-title', 'Step 6: Achievements')
@section('step-desc', 'List your achievements and upload supporting certificates if available.')

@section('content')
<form method="POST" action="{{ route('achievements.store') }}" enctype="multipart/form-data">
  @csrf

  <div id="achievements-container">
    <!-- First achievement block -->
    <div class="achievement-block mb-4" data-index="0">
      <h5>Achievement <span class="entry-number">1</span></h5>

      <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="achievements[0][title]" class="form-control" placeholder="e.g. Winner of Hackathon 2023" required />
      </div>

      <div class="mb-3">
        <label class="form-label">Issuer / Organizer</label>
        <input type="text" name="achievements[0][issuer]" class="form-control" placeholder="e.g. Telkom University, Google" />
      </div>

      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Date Achieved</label>
          <input type="date" name="achievements[0][date_awarded]" class="form-control" required />
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Certificate (optional)</label>
          <input type="file" name="achievements[0][certificate]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" />
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Description (optional)</label>
        <textarea name="achievements[0][description]" class="form-control" rows="3" placeholder="More about this achievement"></textarea>
      </div>

      <button type="button" class="btn btn-danger btn-sm remove-achievement d-none">Remove</button>
      <hr>
    </div>
  </div>

  <button type="button" id="add-achievement" class="btn btn-secondary mb-3">+ Add Another Achievement</button>

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
<script>
let achievementIndex = 1;

function createAchievementBlock(index) {
  const block = document.createElement('div');
  block.classList.add('achievement-block', 'mb-4');
  block.setAttribute('data-index', index);

  block.innerHTML = `
    <h5>Achievement <span class="entry-number">${index + 1}</span></h5>

    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="achievements[${index}][title]" class="form-control" placeholder="e.g. Winner of Hackathon 2023" required />
    </div>

    <div class="mb-3">
      <label class="form-label">Issuer / Organizer</label>
      <input type="text" name="achievements[${index}][issuer]" class="form-control" placeholder="e.g. Telkom University, Google" />
    </div>

    <div class="row">
      <div class="mb-3 col-md-6">
        <label class="form-label">Date Achieved</label>
        <input type="date" name="achievements[${index}][date_awarded]" class="form-control" required />
      </div>
      <div class="mb-3 col-md-6">
        <label class="form-label">Certificate (optional)</label>
        <input type="file" name="achievements[${index}][certificate]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" />
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Description (optional)</label>
      <textarea name="achievements[${index}][description]" class="form-control" rows="3" placeholder="More about this achievement"></textarea>
    </div>

    <button type="button" class="btn btn-danger btn-sm remove-achievement">Remove</button>
    <hr>
  `;

  return block;
}

document.getElementById('add-achievement').addEventListener('click', function () {
  const container = document.getElementById('achievements-container');
  const newBlock = createAchievementBlock(achievementIndex);
  container.appendChild(newBlock);
  achievementIndex++;
  updateAchievementNumbers();
});

document.getElementById('achievements-container').addEventListener('click', function (e) {
  if (e.target && e.target.classList.contains('remove-achievement')) {
    e.target.closest('.achievement-block').remove();
    updateAchievementNumbers();
  }
});

function updateAchievementNumbers() {
  document.querySelectorAll('.achievement-block').forEach((block, idx) => {
    block.setAttribute('data-index', idx);
    block.querySelector('.entry-number').textContent = idx + 1;
  });
}
</script>
@endpush
