@if($relatedJobs->isEmpty())
  <p>No related jobs found.</p>
@else
  {{-- job listing and pagination --}}

<ul class="job-listings mb-5">
    @foreach ($relatedJobs as $job)
      <li class="job-listing d-block d-sm-flex pb-3 pb-sm-0 align-items-center">
        <a href="{{ route('jobs.show', ['id' => $job->id, 'slug' => \Str::slug($job->title)]) }}"></a>
        <div class="job-listing-logo">
          <img src="{{ asset('storage/' . ($job->company->logo_url ?? 'default_logo.png')) }}" alt="Logo" class="img-fluid">
        </div>
        <div class="job-listing-about d-sm-flex custom-width w-100 justify-content-between mx-4">
          <div class="job-listing-position custom-width w-50 mb-3 mb-sm-0">
            <h2>{{ $job->title }}</h2>
            <strong>{{ $job->company->company_name ?? 'Unknown Company' }}</strong>
          </div>
          <div class="job-listing-location mb-3 mb-sm-0 custom-width w-25">
            <span class="icon-room"></span> {{ $job->location }}
          </div>
          <div class="job-listing-meta">
            <span class="badge badge-{{ $job->employment_type === 'part_time' ? 'danger' : 'success' }}">
              {{ ucwords(str_replace('_', ' ', $job->employment_type)) }}
            </span>
          </div>
        </div>
      </li>
    @endforeach
  </ul>
  <div class="row pagination-wrap">
    <div class="col-md-6 text-center text-md-left mb-4 mb-md-0">
      <span>
        Showing {{ $relatedJobs->firstItem() }}–{{ $relatedJobs->lastItem() }} of {{ $relatedJobs->total() }} Related Jobs
      </span>
    </div>
    <div class="col-md-6 text-center text-md-right">
      <div class="custom-pagination pagination ml-auto">
        {{ $relatedJobs->onEachSide(1)->links('vendor.pagination.custom') }}
      </div>
    </div>
  </div>
  @endif
  