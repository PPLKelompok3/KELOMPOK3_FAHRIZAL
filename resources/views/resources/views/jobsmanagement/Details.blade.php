@extends('layouts.landing')

@section('title', 'Job Details - Laravel Developer')

@section('content')
    <!-- HOME -->
    <section class="section-hero overlay inner-page bg-image"
        style="background-image: url('{{ asset('assets/images/hero_1.jpg') }}');" id="home-section">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1 class="text-white font-weight-bold">{{ $job->title }}</h1>
                    <div class="custom-breadcrumbs">
                        <a href="#">Home</a> <span class="mx-2 slash">/</span>
                        <a href="#">{{ $job->company->company_name ?? 'Unknown Company' }}</a> <span
                            class="mx-2 slash">/</span>
                        <span class="text-white"><strong>{{ $job->title }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="site-section">
        <div class="container">
            @if ($isApplied)
                <div class="alert alert-info mt-3">
                    ✅ You have already applied for this job.
                </div>
            @endif
            @if ($errors->has('comment'))
                <div class="alert alert-danger">
                    {{ $errors->first('comment') }}
                </div>
            @endif

            <div class="row align-items-center mb-5">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="border p-2 d-inline-block mr-3 rounded">
                            <img src="{{ asset('storage/' . ($job->company->logo_url ?? 'default_logo.png')) }}"
                                alt="Company Logo" style="max-height: 80px;">
                        </div>
                        <div>
                            <h2>{{ $job->title }}</h2>
                            <div>
                                <span class="ml-0 mr-2 mb-2">
                                    <span
                                        class="icon-briefcase mr-2"></span>{{ $job->company->company_name ?? 'Unknown Company' }}
                                </span>
                                <span class="m-2">
                                    <span class="icon-room mr-2"></span>{{ $job->location }}
                                </span>
                                <span class="m-2">
                                    <span class="icon-clock-o mr-2"></span>
                                    <span
                                        class="text-primary">{{ ucwords(str_replace('_', ' ', $job->employment_type)) }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-6">
                            @if (auth()->check() && auth()->user()->role === 'applicant')
                                <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                    @csrf
                                    {{-- Optional: Cover Letter Input --}}
                                    <!-- <textarea name="cover_letter" class="form-control mb-2" rows="3" placeholder="Optional cover letter..."></textarea> -->
                                    <button type="submit" class="btn btn-block btn-primary btn-md">Apply Now</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-block btn-secondary btn-md"
                                    onclick="return false;">Apply Now</a>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="mb-5">
                        {{-- Optional Image: You can remove this if you don't want a placeholder --}}
                        <figure class="mb-5">
                            <img src="{{ asset('assets/images/job_single_img_1.jpg') }}" alt="Image"
                                class="img-fluid rounded">
                        </figure>

                        <h3 class="h5 d-flex align-items-center mb-4 text-primary">
                            <span class="icon-align-left mr-3"></span>Job Description
                        </h3>

                        <p style="white-space: pre-line;">
                            {{ $job->description }}
                        </p>
                    </div>


                    <div class="row mb-5">
                        <div class="col-6">
                            @if (auth()->check() && auth()->user()->role === 'applicant')
                                <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                                    @csrf
                                    {{-- Optional: Cover Letter Input --}}
                                    <!-- <textarea name="cover_letter" class="form-control mb-2" rows="3" placeholder="Optional cover letter..."></textarea> -->
                                    <button type="submit" class="btn btn-block btn-primary btn-md">Apply Now</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-block btn-secondary btn-md"
                                    onclick="return false;">Apply Now</a>
                            @endif


                        </div>
                    </div>




                </div>
                <div class="col-lg-4">
                    <div class="bg-light p-3 border rounded mb-4">
                        <h3 class="text-primary mt-3 h5 pl-3 mb-3">Job Summary</h3>
                        <ul class="list-unstyled pl-3 mb-0">
                            <li class="mb-2">
                                <strong class="text-black">Published on:</strong>
                                {{ \Carbon\Carbon::parse($job->created_at)->translatedFormat('F j, Y') }}
                            </li>
                            <li class="mb-2">
                                <strong class="text-black">Employment Status:</strong>
                                {{ ucwords(str_replace('_', ' ', $job->employment_type)) }}
                            </li>
                            <li class="mb-2">
                                <strong class="text-black">Experience:</strong>
                                {{ $job->experience_level ?? 'Not specified' }}
                            </li>
                            <li class="mb-2">
                                <strong class="text-black">Job Location:</strong>
                                {{ $job->location }}
                            </li>
                            <li class="mb-2">
                                <strong class="text-black">Salary:</strong>
                                @if ($job->salary_min && $job->salary_max)
                                    Rp{{ number_format($job->salary_min, 0, ',', '.') }} -
                                    Rp{{ number_format($job->salary_max, 0, ',', '.') }}
                                @else
                                    Not specified
                                @endif
                            </li>

                            @if ($job->category)
                                <li class="mb-2">
                                    <strong class="text-black">Category:</strong> {{ $job->category }}
                                </li>
                            @endif

                            @php
                                $skills = $job->skills;
                                $decodedSkills = json_decode($skills, true);
                                if (!is_array($decodedSkills)) {
                                    $decodedSkills = array_map('trim', explode(',', $skills));
                                }
                            @endphp

                            @if (!empty($decodedSkills))
                                <li class="mb-2">
                                    <strong class="text-black">Skills:</strong>
                                    <div class="mt-2">
                                        @foreach ($decodedSkills as $skill)
                                            <span class="badge badge-secondary px-2 py-1 mr-1">
                                                {{ is_array($skill) && isset($skill['value']) ? $skill['value'] : $skill }}
                                            </span>
                                        @endforeach
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="site-section" id="related-jobs-section">
        <div class="container">
            <div class="row mb-5 justify-content-center">
                <div class="col-md-7 text-center">
                    <h2 class="section-title mb-2">Related Jobs</h2>
                </div>
            </div>
            <div id="related-jobs-container">

            </div>
        </div>
    </section>


    <section class="site-section bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center" data-aos="fade">
                    <h2 class="section-title mb-3">Hear from the people who work here</h2>
                </div>
            </div>

            @if ($companyReviews->isNotEmpty())
                <div class="owl-carousel review-carousel">
                    @foreach ($companyReviews as $review)
                        <div class="item">
                            <div class="block__87154 {{ $review->is_useful ? 'bg-light-success' : 'bg-white' }} rounded h-100 mx-2">
                                <blockquote>
                                    <p>&ldquo;{{ $review->comment }}&rdquo;</p>
                                </blockquote>
                                <div class="block__91147 d-flex align-items-center">
                                    <figure class="mr-4">
                                        <img src="{{ $review->user->profile?->profile_picture ? asset('storage/' . $review->user->profile->profile_picture) : asset('assets/img/default-avatar.jpg') }}"
                                            alt="Avatar" class="img-fluid rounded-circle" width="60">
                                    </figure>
                                    @php
                                        $latestJob = $review->user
                                            ->applications()
                                            ->whereHas('job', fn($q) => $q->where('company_id', $review->company_id))
                                            ->latest('created_at')
                                            ->first();
                                    @endphp
                                    <div>
                                        <h3>{{ $review->user->name }}</h3>
                                        @if ($latestJob)
                                            <span class="position">{{ $latestJob->job->title }}</span>
                                        @else
                                            <span class="position">No job info</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center">No reviews for this company yet.</p>
            @endif
        </div>
    </section>

    @auth
        @if ($canReview)
            <div class="comment-form-wrap pt-5">
                <h3 class="mb-5">What was your experience with <strong>{{ $job->company->company_name }} ?</strong></h3>
                <form method="POST" action="{{ route('company.review.submit', $job->company->id) }}">
                    @csrf
                    <div class="form-group">
                        <textarea name="content" class="form-control" rows="3" placeholder="Write your review..." required></textarea>
                    </div>
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </div>
                </form>
            </div>
        @endif
    @else
        <p class="text-muted">You must <a href="{{ route('login') }}">log in</a> to post a review.</p>
    @endauth


    <footer class="site-footer">

        <a href="#top" class="smoothscroll scroll-top">
            <span class="icon-keyboard_arrow_up"></span>
        </a>

        <div class="container">
            <div class="row mb-5">
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <h3>Search Trending</h3>
                    <ul class="list-unstyled">
                        <li><a href="#">Web Design</a></li>
                        <li><a href="#">Graphic Design</a></li>
                        <li><a href="#">Web Developers</a></li>
                        <li><a href="#">Python</a></li>
                        <li><a href="#">HTML5</a></li>
                        <li><a href="#">CSS3</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <h3>Company</h3>
                    <ul class="list-unstyled">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Career</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Resources</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <h3>Support</h3>
                    <ul class="list-unstyled">
                        <li><a href="#">Support</a></li>
                        <li><a href="#">Privacy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <h3>Contact Us</h3>
                    <div class="footer-social">
                        <a href="#"><span class="icon-facebook"></span></a>
                        <a href="#"><span class="icon-twitter"></span></a>
                        <a href="#"><span class="icon-instagram"></span></a>
                        <a href="#"><span class="icon-linkedin"></span></a>
                    </div>
                </div>
            </div>

            <div class="row text-center">
                <div class="col-12">
                    <p class="copyright"><small>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script> All rights reserved | This template is made with <i
                                class="icon-heart text-danger" aria-hidden="true"></i> by <a href="https://colorlib.com"
                                target="_blank">Colorlib</a>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        </small></p>
                </div>
            </div>
        </div>
    </footer>

    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            loadRelatedJobs(1);

            function loadRelatedJobs(page) {
                fetch(`/jobs/{{ $job->id }}/related?page=${page}`)
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('related-jobs-container').innerHTML = html;
                        attachPaginationListeners();
                    });
            }

            function attachPaginationListeners() {
                document.querySelectorAll('#related-jobs-container .custom-pagination a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = new URL(this.href).searchParams.get('page');
                        loadRelatedJobs(page);
                    });
                });
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- If not already loaded -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.review-carousel').owlCarousel({
                loop: true,
                margin: 30,
                nav: true,
                autoplay: true,
                autoplayTimeout: 5000, // 5 seconds
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    }
                }
            });
        });
    </script>
@endpush
