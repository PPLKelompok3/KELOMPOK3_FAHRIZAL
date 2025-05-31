@extends('layouts.landing') {{-- or whatever your main layout is --}}
@section('title', $user->name . ' — Profile')

@section('content')
<section class="section-hero overlay inner-page bg-image" style="background-image: url('{{ asset('assets/images/hero_1.jpg') }}');" id="home-section">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h1 class="text-white font-weight-bold">Profile Page</h1>
        <div class="custom-breadcrumbs">
          <a href="{{ url('/') }}">Home</a> <span class="mx-2 slash">/</span>
          <span class="text-white"><strong>Profile Page</strong></span>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="container my-5">
  <div class="row">
    {{-- Profile Header --}}
    <div class="col-md-4 text-center mb-4">
      <img
        src="{{ $profile->profile_picture
                 ? asset('storage/'.$profile->profile_picture)
                 : asset('assets/img/default-avatar.png') }}"
        alt="{{ $user->name }}"
        class="rounded-circle mb-3"
        style="width:150px; height:150px; object-fit:cover;"
      >
      <h2>{{ $user->name }}</h2>
      <p class="text-muted">{{ $profile->location }}</p>
    </div>

    {{-- About Card --}}
    <div class="col-md-8 mb-4">
  <div class="card position-relative">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>About</span>
      <a href="{{ route('userprofile.edit') }}">
  <i class='bx  bx-edit-alt'style="font-size: 1.5rem; color: #6c757d;"></i> 
</a>

    </div>
    <div class="card-body">
      <p>{{ $profile->bio ?? 'No bio provided.' }}</p>
      @if($profile->cv_url)
        <a href="{{ asset('storage/'.$profile->cv_url) }}" target="_blank">
          Download CV
        </a>
      @endif
    </div>
  </div>
</div>

  </div>

  {{-- Experience --}}
  <div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>Experience</span>
    <a href="{{ route('experience.edit') }}">
      <i class='bx  bx-edit-alt'style="font-size: 1.5rem; color: #6c757d;"></i>
    </a>
  </div>
  <div class="card-body">
    @forelse($experiences as $exp)
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="mb-1">{{ $exp->title }}
            <small class="text-muted">({{ ucfirst($exp->type) }})</small>
          </h5>
          <p class="mb-1">
            <strong>{{ $exp->company_or_org }}</strong> — {{ $exp->location }}
          </p>
          <p class="mb-2">
            {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }}
            –
            {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
          </p>
          <p>{{ Str::limit($exp->description, 150) }}</p>
        </div>
      </div>
    @empty
      <p class="text-muted">No experiences added.</p>
    @endforelse
  </div>
</div>


  {{-- Skills --}}
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Skills</h5>
    <a href="{{ route('skills.edit') }}">
      <i class='bx bx-edit-alt' style="font-size: 1.5rem; color: #6c757d;" title="Edit Skills"></i> 
    </a>
  </div>
  
  <div class="card-body">
    <ul class="list-unstyled mb-2">
      @foreach ($skills->take(3) as $skill)
        <li class="mb-1">• {{ $skill->name }}</li>
      @endforeach
    </ul>

    @if ($skills->count() > 3)
      <div id="extra-skills" class="collapse">
        <ul class="list-unstyled mt-2">
          @foreach ($skills->skip(3) as $skill)
            <li class="mb-1">• {{ $skill->name }}</li>
          @endforeach
        </ul>
      </div>
      <button class="btn btn-sm btn-outline-secondary mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#extra-skills" aria-expanded="false" aria-controls="extra-skills">
        Show More
      </button>
    @endif
  </div>
</div>


  {{-- Projects --}}
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
    <span>Projects</span>
    <a href="{{ route('projects.edit') }}">
      <i class='bx  bx-edit-alt'style="font-size: 1.5rem; color: #6c757d;"></i>
    </a>
  </div>
    <div class="card-body">
      @forelse($projects as $proj)
        <div class="card mb-3">
          <div class="card-body">
            <h5>
              @if($proj->link)
                <a href="{{ $proj->link }}" target="_blank">{{ $proj->title }}</a>
              @else
                {{ $proj->title }}
              @endif
            </h5>
            <p>{{ Str::limit($proj->description, 150) }}</p>
            @if($proj->technologies_used)
              <p><small class="text-muted">Tech: {{ $proj->technologies_used }}</small></p>
            @endif
          </div>
        </div>
      @empty
        <p class="text-muted">No projects added.</p>
      @endforelse
    </div>
  </div>

  {{-- Education --}}
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
    <span>Education</span>
    <a href="{{ route('education.edit') }}">
      <i class='bx  bx-edit-alt'style="font-size: 1.5rem; color: #6c757d;"></i>
    </a>
  </div>
    <div class="card-body">
      @forelse($educations as $edu)
        <div class="card mb-3">
          <div class="card-body">
            <h5>{{ $edu->degree }} in {{ $edu->field_of_study }}</h5>
            <p class="mb-1">
              <strong>{{ $edu->institution_name }}</strong>
            </p>
            <p class="mb-2">
              {{ \Carbon\Carbon::parse($edu->start_date)->format('Y') }}
              –
              {{ \Carbon\Carbon::parse($edu->end_date)->format('Y') }}
            </p>
            <p>{{ Str::limit($edu->description, 150) }}</p>
          </div>
        </div>
      @empty
        <p class="text-muted">No education entries added.</p>
      @endforelse
    </div>
  </div>
  {{-- Achievements --}}
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>Achievements</span>
    <a href="{{ route('achievements.edit') }}">
      <i class='bx  bx-edit-alt'style="font-size: 1.5rem; color: #6c757d;"></i>
    </a>
  </div>
  <div class="card-body">
    @forelse ($achievements as $achievement)
      <div class="card mb-3">
        <div class="card-body">
          <h5>{{ $achievement->title }}</h5>
          <p class="mb-1"><strong>Issuer:</strong> {{ $achievement->issuer }}</p>
          <p class="mb-1"><strong>Date Awarded:</strong> {{ \Carbon\Carbon::parse($achievement->date_awarded)->format('M Y') }}</p>
          @if ($achievement->description)
            <p>{{ Str::limit($achievement->description, 150) }}</p>
          @endif
          @if ($achievement->certificate)
            <a href="{{ asset('storage/' . $achievement->certificate) }}" target="_blank" class="btn btn-sm btn-outline-primary">
              View Certificate
            </a>
          @endif
        </div>
      </div>
    @empty
      <p class="text-muted">No achievements added yet.</p>
    @endforelse
  </div>
</div>

</div>
@endsection
