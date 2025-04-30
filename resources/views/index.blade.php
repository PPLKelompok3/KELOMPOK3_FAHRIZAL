@extends('layouts.landing')

@section('title', 'Job Details - Laravel Developer')

@section('content')

    <!-- HOME -->
    <section class="home-section section-hero overlay bg-image" style="background-image: url('{{ asset('assets/images/hero_1.jpg') }}');" id="home-section">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-12">
            <div class="mb-5 text-center">
              <h1 class="text-white font-weight-bold">Discover Your <span style="color: #ffc901;">Workora </span>Match!</h1>
              <p>Find the perfect job that matches your skills and passion. Choose the position you desire and get the best opportunities!</p>
            </div>
            <form method="GET" action="{{ route('home') }}" class="search-jobs-form">
              <div class="row mb-5">
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                  <input class="form-control form-control-lg" 
                  type="text" 
                  id="search-title" 
                  name="title" 
                  style="height: 52px;" 
                  placeholder="Job title, Company..." 
                  value="{{ request('title') }}" />
           
                </div>
                
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                  <input class="form-control form-control-lg" 
       type="text" 
       id="search-location" 
       name="location" 
       placeholder="Select Region" 
       value="{{ request('location') }}" />

                </div>
                
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                  <input class="form-control form-control-lg" 
                  type="text" 
                  id="search-type" 
                  name="employment_type" 
                  placeholder="Select Job Type" 
                  value="{{ request('employment_type') }}" />
           
                </div>
                
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                  <button type="submit" class="btn btn-lg btn-block text-dark btn-search"
                          style="background-color: #ffc901; border: none;">
                    <span class="icon-search icon mr-2"></span>Search Job
                  </button>
                  
                  <!-- Reset Button (below Search Job) -->
                  <a href="{{ route('home') }}" 
   class="btn btn-lg btn-block mt-2 d-flex justify-content-center align-items-center" 
   style="background-color: #ffc901; border: none; color: #333; height: 52px;">
   Reset
</a>

                </div>
                
              </div>
              @if(count($recommendedSkills))
  <div class="row mt-4">
    <div class="col-md-12 popular-keywords">
      <h3>Recommended for You:</h3>
      <ul class="keywords list-unstyled m-0 p-0">
        @foreach ($recommendedSkills as $skill)
          <li style="display: inline-block; margin-right: 10px; margin-bottom: 10px;">
            <a href="#" 
   class="text-dark recommended-skill {{ request('title') == $skill ? 'selected-skill' : '' }}"
   data-skill="{{ $skill }}"
   style="display: inline-block; padding: 10px 18px; font-size: 1.1rem; background-color: #ffc901; border-radius: 20px; text-decoration: none;">
   {{ ucfirst($skill) }}
</a>


          </li>
        @endforeach
      </ul>
    </div>
  </div>
@endif



            </form>
          </div>
        </div>
      </div>

      <a href="#next" class="scroll-button smoothscroll">
        <span class=" icon-keyboard_arrow_down"></span>
      </a>

    </section>
    
    <section class="py-5 bg-image overlay-primary fixed overlay" id="next" style="background-image: url('{{ asset('assets/images/hero_1.jpg') }}');" style="background-color: #ffc901;">
      <div class="container">
        <div class="row mb-5 justify-content-center">
          <div class="col-md-7 text-center">
            <h2 class="section-title mb-2 text-white">JobBoard Site Stats</h2>
            <p class="lead text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita unde officiis recusandae sequi excepturi corrupti.</p>
          </div>
        </div>
        <div class="row pb-0 block__19738 section-counter">

          <div class="col-6 col-md-6 col-lg-3 mb-5 mb-lg-0">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <strong class="number" data-number="1930">0</strong>
            </div>
            <span class="caption">Candidates</span>
          </div>

          <div class="col-6 col-md-6 col-lg-3 mb-5 mb-lg-0">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <strong class="number" data-number="54">0</strong>
            </div>
            <span class="caption">Jobs Posted</span>
          </div>

          <div class="col-6 col-md-6 col-lg-3 mb-5 mb-lg-0">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <strong class="number" data-number="120">0</strong>
            </div>
            <span class="caption">Jobs Filled</span>
          </div>

          <div class="col-6 col-md-6 col-lg-3 mb-5 mb-lg-0">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <strong class="number" data-number="550">0</strong>
            </div>
            <span class="caption">Companies</span>
          </div>

            
        </div>
      </div>
    </section>

    

<section class="site-section">
  <div class="container">
    <div class="row mb-5 justify-content-center">
      <div class="col-md-7 text-center">
        <h2 class="section-title mb-2">{{ $totalJobs }} Job Listed</h2>
      </div>
    </div>
    <div id="job-loader" style="display: none; text-align: center; margin-bottom: 1rem;">
      <div class="spinner-border text-warning" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>    
    <div id="job-list-container">
      @include('partials.job-list', ['jobs' => $jobs])
    </div>
  </div>
</section>


    <section class="py-5 bg-image overlay-primary fixed overlay" style="background-image: url('{{ asset('assets/images/hero_1.jpg') }}');">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h2 class="text-white">Looking For A Job?</h2>
            <p class="mb-0 text-white lead">Lorem ipsum dolor sit amet consectetur adipisicing elit tempora adipisci impedit.</p>
          </div>
          <div class="col-md-3 ml-auto">
            <a href="#" class="btn btn-warning btn-block btn-lg">Sign Up</a>
          </div>
        </div>
      </div>
    </section>

    
   


    <section class="bg-light pt-5 testimony-full">
        
        <div class="owl-carousel single-carousel">

        
          <div class="container">
            <div class="row">
              <div class="col-lg-6 align-self-center text-center text-lg-left">
                <blockquote>
                  <p>&ldquo;Soluta quasi cum delectus eum facilis recusandae nesciunt molestias accusantium libero dolores repellat id in dolorem laborum ad modi qui at quas dolorum voluptatem voluptatum repudiandae.&rdquo;</p>
                  <p><cite> &mdash; Corey Woods, @Dribbble</cite></p>
                </blockquote>
              </div>
              <div class="col-lg-6 align-self-end text-center text-lg-right">
                <img src="{{ asset('assets/images/person_transparent_2.png') }}" alt="Image" class="img-fluid mb-0">
              </div>
            </div>
          </div>

          <div class="container">
            <div class="row">
              <div class="col-lg-6 align-self-center text-center text-lg-left">
                <blockquote>
                  <p>&ldquo;Soluta quasi cum delectus eum facilis recusandae nesciunt molestias accusantium libero dolores repellat id in dolorem laborum ad modi qui at quas dolorum voluptatem voluptatum repudiandae.&rdquo;</p>
                  <p><cite> &mdash; Chris Peters, @Google</cite></p>
                </blockquote>
              </div>
              <div class="col-lg-6 align-self-end text-center text-lg-right">
                <img src="{{ asset('assets/images/person_transparent.png') }}" alt="Image" class="img-fluid mb-0">
              </div>
            </div>
          </div>

      </div>

    </section> 
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
            Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart text-danger" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" >Colorlib</a>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></small></p>
          </div>
        </div>
      </div>
    </footer>
  
  </div>
  @endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
  <script>
    const config = (url) => ({
      create: false,
      enforceWhitelist: true,
      maxItems: 1,
      valueField: 'value',
      labelField: 'value',
      searchField: 'value',
      load: function(query, callback) {
        fetch(url + '?q=' + encodeURIComponent(query))
          .then(response => response.json())
          .then(callback).catch(() => callback());
      }
    });

    new TomSelect('#search-title', config('/api/job-posts/titles'));
    new TomSelect('#search-location', config('/api/job-posts/locations'));
    new TomSelect('#search-type', config('/api/job-posts/types'));
</script>
<script>
  console.log("🔥 Script is running");
</script>
<script>
  $(function () {
    $(document).on('click', '.pagination a', function(e) {
      e.preventDefault();
      const url = $(this).attr('href');

      $('#job-loader').show(); // Show spinner
      $('html, body').animate({ scrollTop: $('#job-list-container').offset().top - 80 }, 600); // Scroll to job list

      $.ajax({
        url: url,
        type: 'GET',
        success: function(data) {
          $('#job-list-container').html(data); // Replace only job section
          $('#job-loader').hide(); // Hide spinner
          window.history.pushState({}, '', url); // Optional: update URL
        },
        error: function() {
          $('#job-loader').hide(); // Hide on error too
          alert("Something went wrong while loading jobs.");
        }
      });
    });
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const skillButtons = document.querySelectorAll('.recommended-skill');
    const titleInput = document.getElementById('search-title');

    skillButtons.forEach(button => {
      button.addEventListener('click', function (e) {
        e.preventDefault();

        const skill = this.dataset.skill;

        // Set the skill to the search title input
        titleInput.value = skill;

        // Submit the form
        this.closest('form').submit();
      });
    });
  });
</script>

@endpush   
  </body>
</html>