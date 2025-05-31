@extends('layouts.recruiter')

@section('title', 'Edit Job')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Edit Job</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('jobs.update', $job->id) }} "enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Job Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $job->title }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Employment Type</label>
                    <select name="employment_type" class="form-control" required>
                        @foreach (['full_time', 'part_time', 'internship', 'freelance'] as $type)
                            <option value="{{ $type }}" {{ $job->employment_type === $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ $job->location }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Skills Needed</label>
                    <input id="job-skills-input" name="skills" class="form-control" value='@json(explode(',', $job->skills))'>
                </div>

                <div class="mb-3">
                    <label class="form-label">Job Category</label>
                    <input id="job-category-input" name="category" class="form-control"
                        value='@json([['value' => $job->category]])'>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Salary Min (IDR)</label>
                        <input type="number" name="salary_min" class="form-control" value="{{ $job->salary_min }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Salary Max (IDR)</label>
                        <input type="number" name="salary_max" class="form-control" value="{{ $job->salary_max }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Job Description</label>
                    <textarea name="description" class="form-control" rows="5" required>{{ $job->description }}</textarea>
                </div>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Optional Assessment</h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable-assessment"
                                {{ $assessment ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable-assessment">Enable</label>
                        </div>
                    </div>

                    <div class="card-body" id="assessment-section" style="{{ $assessment ? '' : 'display: none;' }}">
                        <div class="mb-3">
                            <label class="form-label">Assessment Type</label>
                            <select class="form-control" name="assessment_type" id="assessment-type">
                                <option value="">Select Type</option>
                                <option value="essay" {{ $assessment?->type === 'essay' ? 'selected' : '' }}>Essay
                                </option>
                                <option value="file_upload" {{ $assessment?->type === 'file_upload' ? 'selected' : '' }}>
                                    File Upload</option>
                            </select>
                        </div>

                        {{-- Essay --}}
                        <div class="mb-3 d-none" id="essay-editor-wrapper">
                            <label class="form-label">Essay Questions</label>
                            <textarea name="essay_questions" id="essay_editor" class="form-control">{!! old('essay_questions', $assessment?->type === 'essay' ? $assessment->instruction : '') !!}</textarea>
                        </div>

                        {{-- File Upload --}}
                        <div class="mb-3 d-none" id="file-upload-wrapper">
                            <label class="form-label">Instructions</label>
                            <textarea name="file_instruction" class="form-control" rows="3">{{ old('file_instruction', $assessment?->type === 'file_upload' ? $assessment->instruction : '') }}</textarea>

                            <label class="form-label mt-3">Optional Guide File (PDF/DOCX)</label>
                            <input type="file" name="file_guide" class="form-control" accept=".pdf,.docx">
                            @if ($assessment?->attachment)
                                <small class="text-muted">Current: <a
                                        href="{{ asset('storage/' . $assessment->attachment) }}" target="_blank">View
                                        file</a></small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Assessment Deadline</label>
                            <input type="number" name="assessment_due_in_days" class="form-control" min="1"
                                placeholder="e.g. 3 (means 3 days after applicant applies)"
                                value="{{ old('assessment_due_in_days', $assessment?->due_in_days) }}">
                        </div>

                    </div>
                </div>


                <button type="submit" class="btn btn-primary">Update Job</button>
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
            </form>
        </div>
    </div>
    <div class="card mt-4">
  <h5 class="card-header">Referral Code</h5>
  <div class="card-body">
    @if (session('referral_success'))
      <div class="alert alert-success">
        {{ session('referral_success') }}
      </div>
    @endif

    {{-- Generate Code --}}
    <form method="POST" action="{{ route('referral.generate', $job->id) }}">
      @csrf
      <button type="submit" class="btn btn-primary mb-3">Generate New Referral Code</button>
    </form>

    {{-- Code Table --}}
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Job Title</th>
            <th>Code</th>
            <th>Used By</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($referralCodes as $code)
            <tr>
              <td>{{ $job->title }}</td>
              <td><code>{{ $code->code }}</code></td>
              <td>
                @if ($code->usedBy)
                  {{ $code->usedBy->name }} ({{ $code->usedBy->email }})
                @else
                  <span class="text-muted">Unused</span>
                @endif
              </td>
              <td>{{ $code->created_at->format('Y-m-d H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">No referral codes generated yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>


    <div class="card mt-4">
        <h5 class="card-header">Toggle Job Status</h5>
        <div class="card-body">
            <div class="alert alert-info">
                <h6 class="alert-heading fw-bold mb-1">Current Status: <span
                        class="badge bg-{{ $job->status === 'Active' ? 'success' : 'secondary' }}">{{ $job->status }}</span>
                </h6>
                <p class="mb-0">You can {{ $job->status === 'Active' ? 'deactivate' : 'activate' }} this job anytime.
                </p>
            </div>

            <form method="POST" action="{{ route('jobs.toggle-status', $job->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $job->status === 'Active' ? 'btn-warning' : 'btn-success' }}">
                    {{ $job->status === 'Active' ? 'Deactivate' : 'Activate' }} Job
                </button>
            </form>
        </div>
    </div>


    {{-- Delete Confirmation --}}
    <div class="card mt-4">
        <h5 class="card-header">Delete Job</h5>
        <div class="card-body">
            <div class="alert alert-warning">
                <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete this job?</h6>
                <p class="mb-0">Once deleted, this job cannot be recovered.</p>
            </div>
            <form method="POST" action="{{ route('jobs.destroy', $job->id) }}">
                @csrf
                @method('DELETE')
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                    <label class="form-check-label" for="confirmDelete">
                        I confirm deletion of this job
                    </label>
                </div>
                <button type="submit" class="btn btn-danger">Delete Job</button>
            </form>
        </div>
    </div>
@endsection

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

    <script>
        // Skills
        const skillInput = document.getElementById('job-skills-input');
        const tagifySkills = new Tagify(skillInput, {
            whitelist: [],
            dropdown: {
                enabled: 1,
                maxItems: 10,
                closeOnSelect: false
            }
        });

        tagifySkills.on('input', e => {
            fetch(`/api/skills/search?q=${e.detail.value}`)
                .then(res => res.json())
                .then(data => {
                    tagifySkills.settings.whitelist = data;
                    tagifySkills.dropdown.show.call(tagifySkills, e.detail.value);
                });
        });

        // Category
        const categoryInput = document.getElementById('job-category-input');
        new Tagify(categoryInput, {
            enforceWhitelist: false,
            whitelist: ['Software Development', 'Design', 'Marketing', 'Management', 'DevOps'],
            dropdown: {
                enabled: 0
            }
        });
    </script>
    <script src="{{ asset('assets/js/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#essay_editor',
            height: 300,
            menubar: false,
            plugins: 'lists link code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
            branding: false
        });

        document.getElementById('enable-assessment').addEventListener('change', function() {
            document.getElementById('assessment-section').style.display = this.checked ? 'block' : 'none';
        });

        document.getElementById('assessment-type').addEventListener('change', function() {
            const type = this.value;
            document.getElementById('essay-editor-wrapper').classList.toggle('d-none', type !== 'essay');
            document.getElementById('file-upload-wrapper').classList.toggle('d-none', type !== 'file_upload');
        });

        // Trigger type check on load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('assessment-type').dispatchEvent(new Event('change'));
        });
    </script>
@endpush
