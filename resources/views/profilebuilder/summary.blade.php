@extends('layouts.profile-setup')

@section('title', 'Summary')
@section('step-title', 'Profile Summary')
@section('step-desc', 'Review your details below before submitting your profile.')



@section('content')
<div class="accordion" id="summaryAccordion">
  <div class="alert alert-info mb-4">
    <strong>Note:</strong> You can update your profile details at any time after submission from your dashboard.
  </div>
  <!-- Profile -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingProfile">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProfile" aria-expanded="true">
        👤 Profile
      </button>
    </h2>
    <div id="collapseProfile" class="accordion-collapse collapse show" data-bs-parent="#summaryAccordion">
      <div class="accordion-body">
        <p><strong>Name:</strong> {{ $user->name }}</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Location:</strong> {{ $user->profile->location ?? '-' }}</p>
<p><strong>Phone:</strong> {{ $user->profile->phone ?? '-' }}</p>
<p><strong>Bio:</strong> {{ $user->profile->bio ?? '-' }}</p>
<p><strong>CV:</strong>
  @if ($user->profile->cv_url)
    <a href="{{ Storage::url($user->profile->cv_url) }}" target="_blank">📄 View Uploaded CV</a>
  @else
    <em>Not uploaded</em>
  @endif
</p>

        
      </div>
    </div>
  </div>
  <!-- Skills -->
<div class="accordion-item">
  <h2 class="accordion-header" id="headingSkills">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSkills">
      🛠️ Skills
    </button>
  </h2>
  <div id="collapseSkills" class="accordion-collapse collapse" data-bs-parent="#summaryAccordion">
    <div class="accordion-body">
      @forelse ($user->skills as $skill)
        <span class="badge bg-primary me-1">{{ $skill->name }}</span>
      @empty
        <p>No skills added yet.</p>
      @endforelse

      
    </div>
  </div>
</div>


  <!-- Education -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingEducation">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEducation">
        🎓 Education
      </button>
    </h2>
    <div id="collapseEducation" class="accordion-collapse collapse" data-bs-parent="#summaryAccordion">
      <div class="accordion-body">
        @forelse ($user->educations as $edu)
  <div class="border rounded p-2 mb-2">
    <strong>{{ $edu->degree }} - {{ $edu->field_of_study }}</strong><br>
    {{ $edu->institution_name }}<br>
    {{ $edu->start_date }} – {{ $edu->end_date }}<br>
    <em>{{ $edu->description }}</em>
  </div>
@empty
  <p>No education records added.</p>
@endforelse

        
      </div>
    </div>
  </div>

  <!-- Experience -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingExperience">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExperience">
        💼 Experience
      </button>
    </h2>
    <div id="collapseExperience" class="accordion-collapse collapse" data-bs-parent="#summaryAccordion">
      <div class="accordion-body">
        @forelse ($user->experiences as $exp)
  <div class="border rounded p-2 mb-2">
    <strong>{{ $exp->title }} ({{ ucfirst(str_replace('_', ' ', $exp->type)) }})</strong><br>
    {{ $exp->company_or_org }}<br>
    {{ $exp->start_date }} – {{ $exp->end_date }}<br>
    <em>{{ $exp->description }}</em>
  </div>
@empty
  <p>No experience records added.</p>
@endforelse

        
      </div>
    </div>
  </div>

  <!-- Projects -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingProjects">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProjects">
        🧪 Projects
      </button>
    </h2>
    <div id="collapseProjects" class="accordion-collapse collapse" data-bs-parent="#summaryAccordion">
      <div class="accordion-body">
        @forelse ($user->projects as $project)
  <div class="border rounded p-2 mb-2">
    <strong>{{ $project->title }}</strong><br>
    {{ $project->description }}<br>
    <strong>Technologies:</strong> {{ $project->technologies_used }}<br>
    @if ($project->link)
      🔗 <a href="{{ $project->link }}" target="_blank">View Project</a>
    @endif
  </div>
@empty
  <p>No projects added yet.</p>
@endforelse

        
      </div>
    </div>
  </div>

  <!-- Achievements -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingAchievements">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAchievements">
        🏅 Achievements
      </button>
    </h2>
    <div id="collapseAchievements" class="accordion-collapse collapse" data-bs-parent="#summaryAccordion">
      <div class="accordion-body">
        @forelse ($user->achievements as $ach)
  <div class="border rounded p-2 mb-2">
    <strong>{{ $ach->title }}</strong><br>
    Issuer: {{ $ach->issuer }} · {{ $ach->date_awarded }}<br>
    @if ($ach->certificate)
      📎 <a href="{{ Storage::url($ach->certificate) }}" target="_blank">View Certificate</a><br>
    @endif
    <em>{{ $ach->description }}</em>
  </div>
@empty
  <p>No achievements listed yet.</p>
@endforelse

        
      </div>
    </div>
  </div>

</div>


<a href="/" class="btn d-grid w-100 mt-4" style="background-color: #ffc901; border: none; color: #000;">
  Continue →
</a>


@endsection
