@extends('layouts.landing')

@section('title', 'Submit Referral Code')

@section('content')
<section class="section-hero overlay inner-page bg-image"
    style="background-image: url('{{ asset('assets/images/hero_1.jpg') }}');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Referral Application</h1>
                <div class="custom-breadcrumbs">
                    <a href="{{ url('/') }}">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Use Referral Code</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="site-section">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">

                <h3 class="mb-4">Enter Your Referral Code</h3>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops! Something went wrong.</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('referral.submit') }}" class="border p-4 rounded bg-light">
                    @csrf
                    <div class="mb-3">
                        <label for="referral_code" class="form-label">Referral Code</label>
                        <input type="text" name="referral_code" id="referral_code"
                               class="form-control @error('referral_code') is-invalid @enderror"
                               placeholder="e.g. XYZ123ABC" required>
                        @error('referral_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Apply with Referral</button>
                </form>

            </div>
        </div>

    </div>
</section>
@endsection
