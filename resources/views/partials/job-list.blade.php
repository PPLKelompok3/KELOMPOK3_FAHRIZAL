{{-- debug --}}
<ul class="job-listings mb-5">
    @foreach ($jobs as $job)
      <li class="job-listing d-block d-sm-flex pb-3 pb-sm-0 align-items-center">
        <a href="{{ route('jobs.show', ['id' => $job->id, 'slug' => Str::slug($job->title . '-' . $job->company->company_name)]) }}">
          </a>
        <div class="job-listing-logo">
          <img src="{{ asset('storage/' . ($job->company->logo_url ?? 'default_logo.png')) }}" class="img-fluid" alt="{{ $job->company->company_name ?? 'Company Logo' }}">
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
  {{-- Pagination --}}
  <div class="row pagination-wrap">
    <div class="col-md-6 text-center text-md-left mb-4 mb-md-0">
      <span>Showing {{ $jobs->firstItem() }}–{{ $jobs->lastItem() }} of {{ $jobs->total() }} Jobs</span>
    </div>
    <div class="col-md-6 text-center text-md-right">
        <div class="custom-pagination pagination ml-auto">
            {{ $jobs->onEachSide(1)->links('vendor.pagination.custom') }}
          </div>          
    </div>
  </div>