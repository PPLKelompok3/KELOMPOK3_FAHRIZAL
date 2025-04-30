@extends('layouts.recruiter')
@section('title', 'Dashboard')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Total Revenue Big Card (Left Side) -->
        <div class="col-12 col-lg-8 order-1 mb-4">
            <div class="card">
                <div class="row row-bordered g-0">
                    <div class="col-md-8">
                        <h5 class="card-header m-0 me-2 pb-3">Total Application</h5>
                        <div id="totalRevenueChart" class="px-2"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                        id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Select Job
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId" id="jobDropdownMenu" style="max-height: 300px; overflow-y: auto;">
                                        @foreach($jobPosts as $id => $title)
                                        <a class="dropdown-item" href="#" data-id="{{ $id }}">{{ $title }}</a>
                                        @endforeach
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div id="growthChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 Small Cards (Right Side) -->
        <!-- 4 Small Cards (Right Side) -->
        <div class="col-12 col-lg-4 order-2 mb-4">
            <div class="row">

                <!-- Total Applicants -->
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/chart-success.png') }}"
                                        alt="Applicants" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Total Applicants</span>
                            <h3 class="card-title mb-2">{{ $totalApplicants }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Total Jobs Posted -->
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/chart-success.png') }}"
                                        alt="Applicants" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Total Job Posts</span>
                            <h3 class="card-title mb-2">{{ $totalJobs }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Accepted Applicants -->
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/chart-success.png') }}"
                                        alt="Applicants" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Accepted</span>
                            <h3 class="card-title mb-2">{{ $acceptedApplicants }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Rejected Applicants -->
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/chart-success.png') }}"
                                        alt="Applicants" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Rejected</span>
                            <h3 class="card-title mb-2">{{ $rejectedApplicants }}</h3>
                        </div>
                    </div>
                </div>

            </div> <!-- End row inside col-4 -->
        </div> <!-- End right-side column -->

    </div> <!-- End main row -->
</div> <!-- End container -->

@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalRevenueChartEl = document.querySelector('#totalRevenueChart');
        const growthChartEl = document.querySelector('#growthChart');
        let totalRevenueChart, growthChart;


        // Setup Total Revenue Chart
        const totalRevenueChartOptions = {
            series: [{
                name: 'Applicants',
                data: []
            }],
            chart: {
                height: 300,
                stacked: true,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%', // 🔥 Increase from '33%' to '50%' or even '55%'
                    borderRadius: 10,
                    startingShape: 'rounded',
                    endingShape: 'rounded'
                }
            },

            colors: ['#696cff'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 6,
                lineCap: 'round',
                colors: ['#fff']
            },
            legend: {
                show: false
            },
            grid: {
                borderColor: '#e0e0e0',
                padding: {
                    top: 0,
                    bottom: -8,
                    left: 20,
                    right: 20
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'], // 🔥 Only up to July
                labels: {
                    style: {
                        fontSize: '13px',
                        colors: '#6e6b7b'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '13px',
                        colors: '#6e6b7b'
                    }
                }
            }
        };

        if (typeof totalRevenueChartEl !== undefined && totalRevenueChartEl !== null) {
            totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
            totalRevenueChart.render();
        }

        // Setup Growth Radial Chart
        const growthChartOptions = {
            series: [0],
            labels: ['Growth'],
            chart: {
                height: 240,
                type: 'radialBar'
            },
            plotOptions: {
                radialBar: {
                    size: 150,
                    offsetY: 10,
                    startAngle: -150,
                    endAngle: 150,
                    hollow: {
                        size: '55%'
                    },
                    track: {
                        background: '#e0e0e0',
                        strokeWidth: '100%'
                    },
                    dataLabels: {
                        name: {
                            offsetY: 15,
                            color: '#6e6b7b',
                            fontSize: '15px',
                            fontWeight: '600'
                        },
                        value: {
                            offsetY: -25,
                            color: '#6e6b7b',
                            fontSize: '22px',
                            fontWeight: '500'
                        }
                    }
                }
            },
            colors: ['#696cff'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#696cff'],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 0.6,
                    stops: [30, 70, 100]
                }
            },
            stroke: {
                dashArray: 5
            }
        };

        if (typeof growthChartEl !== undefined && growthChartEl !== null) {
            growthChart = new ApexCharts(growthChartEl, growthChartOptions);
            growthChart.render();
        }


        // Dropdown Select Job Post
        const jobDropdownItems = document.querySelectorAll('#jobDropdownMenu a');

        // Then: Add event listeners to job items
        jobDropdownItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const jobId = this.getAttribute('data-id');
                const button = document.getElementById('growthReportId');
                button.innerText = this.innerText;

                if (jobId) {
                    fetch(`/recruiter/dashboard/job-applicants-graph?job_id=${jobId}`)
                        .then(response => response.json())
                        .then(data => {
                            const monthlyData = Array(7).fill(0);
                            for (const month in data) {
                                monthlyData[month - 1] = data[month];
                            }
                            totalRevenueChart.updateSeries([{
                                name: "Applicants",
                                data: monthlyData
                            }]);

                            // Calculate Growth
                            const currentMonth = new Date().getMonth();
                            const thisMonthApplicants = monthlyData[currentMonth] || 0;
                            const lastMonthApplicants = monthlyData[currentMonth - 1] || 0;

                            let growth = 0;
                            if (lastMonthApplicants === 0 && thisMonthApplicants > 0) {
                                growth = 100;
                            } else if (lastMonthApplicants > 0) {
                                growth = ((thisMonthApplicants - lastMonthApplicants) / lastMonthApplicants) * 100;
                            }

                            growth = Math.round(growth);

                            growthChart.updateOptions({
                                series: [growth >= 0 ? growth : 0], // 🔥
                                colors: [growth >= 0 ? '#696cff' : '#ff4c51']
                            });
                        });
                }
            });
        });

        // THEN after event listeners created, click first job
        const firstJobItem = document.querySelector('#jobDropdownMenu a');
        if (firstJobItem) {
            firstJobItem.click();
        }
    });
</script>
@endpush