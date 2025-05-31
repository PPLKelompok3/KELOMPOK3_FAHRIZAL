@extends('layouts.landing')

@section('title', 'Job Assessments - Essay Submission')

@section('content')
    <!-- HERO SECTION -->
    <section class="section-hero overlay inner-page bg-image"
        style="background-image: url('{{ asset('assets/images/hero_1.jpg') }}');" id="home-section">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1 class="text-white font-weight-bold">Assessments</h1>
                    <div class="custom-breadcrumbs">
                        <a href="{{ url('/') }}">Home</a> <span class="mx-2 slash">/</span>
                        <a href="#">{!! $job->title !!}</a> <span class="mx-2 slash">/</span>
                        <span class="text-white"><strong>Essay Submission</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="site-section">
        <div class="container">

            <!-- Heading + Submit Button -->
            <div class="row align-items-center mb-4">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <h2>Answer Essay Questions</h2>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-12 text-end">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Due Date -->
            <div class="alert alert-warning d-flex justify-content-between">
                <div>
                    <strong>Due Date:</strong> {{ $dueDate->format('l, F j, Y \a\t H:i') }} WIB
                </div>
                <span class="badge bg-warning text-dark">{{ $remainingDays }} days remaining</span>
            </div>
@if ($errors->any())
  <div class="alert alert-danger">
    <strong>There were some errors with your submission:</strong>
    <ul class="mb-0 mt-1">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
            <!-- Instruction + Essay Form -->
            <form class="p-4 p-md-5 border rounded bg-light" method="POST" action="{{ route('assessment.submit', $job->id) }}">
    @csrf
                {{-- Instructions --}}
                <div class="mb-4">
                    <h4 class="mb-3">Instructions</h4>
                    <div class="small" style="font-size: 0.95rem;">
                        <p>{!! $assessment->instruction !!}</p>
                    </div>
                </div>

                {{-- Essay Area --}}
                <div class="mb-4">
                    <label for="essay_answer" class="form-label">Your Answer</label>
                    <textarea name="submission_text" id="essay_answer" class="form-control" rows="10"></textarea>
                    <small class="text-muted d-block mt-1">Format your response as needed. Your writing will be evaluated
                        for clarity and depth.</small>
                </div>
                

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Submit Essay</button>

            </form>

        </div>
    </section>
@endsection

@push('scripts')
    <!-- TinyMCE -->
    <script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
<script>
  tinymce.init({
    selector: '#essay_answer',
    height: 300,
    menubar: false,
    plugins: 'lists link code',
    toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
    branding: false
  });

  // Ensure content is saved on submit
  document.querySelector('form').addEventListener('submit', function (e) {
    tinymce.triggerSave(); // 🔁 Force sync to <textarea>
  });
</script>
@endpush
