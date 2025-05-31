@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <div class="row">
        <!-- Order Statistics -->
        <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Job Posts</h5>
                        <small class="text-muted">All job posts on Workora website</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                            <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column align-items-center gap-1">
                            <h2 class="mb-2">{{ $jobPostCount }}</h2>
                            <span>Total Job Posts</span>
                        </div>
                        <div id="orderStatisticsChart"></div>
                    </div>
                    <div style="max-height: 250px; overflow-y: auto;">
                        <div style="max-height: 250px; overflow-y: auto;">
                            <ul class="p-0 m-0">
                                @foreach ($jobPostStats as $stat)
                                    <li class="d-flex mb-4 pb-1">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-info">
                                                <i class="bx bx-briefcase-alt"></i>
                                            </span>
                                        </div>
                                        <div
                                            class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <h6 class="mb-0">{{ $stat['category'] }}</h6>
                                                <small class="text-muted">
                                                    {{ implode(', ', $stat['examples']->toArray()) }}
                                                </small>
                                            </div>
                                            <div class="user-progress">
                                                <small class="fw-semibold">{{ $stat['total'] }}</small>
                                                {{-- ✅ This is your actual count --}}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!--/ Order Statistics -->

        <!-- Expense Overview -->
        <div class="col-md-6 col-lg-4 order-1 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Application</h5>
                        <small class="text-muted">All application on Workora website</small>
                    </div>
                </div>
                <div class="card-body px-0">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                            <div class="d-flex p-4 pt-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="../assets/img/icons/unicons/wallet.png" alt="User" />
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Application</small>
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">{{ $applicationCount }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div id="incomeChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Expense Overview -->

        <!-- Transactions -->
        <div class="col-md-6 col-lg-4 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Users</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                            <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @foreach ($adminUserStats as $stat)
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded {{ $stat['bg'] }}">
                                        <i class="{{ $stat['icon'] }}"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="text-muted d-block mb-1">{{ $stat['title'] }}</small>
                                        <h6 class="mb-0">{{ $stat['label'] }}</h6>
                                    </div>
                                    <div class="user-progress d-flex align-items-center gap-1">
                                        <h6 class="mb-0">{{ $stat['count'] }}</h6>
                                        <span class="text-muted">Users</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
        <!--/ Transactions -->
    </div>
    <div class="card">
    <h5 class="card-header">Recruiter Verification</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($recruiters as $recruiter)
                    <tr>
                        <td><strong>{{ $recruiter->name }}</strong></td>
                        <td>{{ $recruiter->email }}</td>
                        <td>{{ $recruiter->company->company_name ?? '-' }}</td>
                        <td>
                            @if ($recruiter->is_verified === 'approved')
                                <span class="badge bg-label-success me-1">Approved</span>
                            @elseif ($recruiter->is_verified === 'rejected')
                                <span class="badge bg-label-danger me-1">Rejected</span>
                            @else
                                <span class="badge bg-label-warning me-1">Pending</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.verifyRecruiter', $recruiter->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.verifyRecruiter', $recruiter->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 ms-2 d-flex justify-content-center">
    {{ $recruiters->links() }}
</div>
</div>


@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof ApexCharts !== 'undefined' && document.querySelector('#orderStatisticsChart')) {
                const chartData = {
                    chart: {
                        type: 'donut',
                        height: 165,
                        width: 130
                    },
                    labels: {!! json_encode($categories) !!},
                    series: {!! json_encode($categoryCounts) !!},
                    colors: ['#696cff', '#28c76f', '#00cfe8', '#ff9f43', '#ea5455', '#7367f0'],
                    stroke: {
                        width: 5,
                        colors: ['#fff']
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                labels: {
                                    show: true,
                                    value: {
                                        fontSize: '1.5rem',
                                        offsetY: -15
                                    },
                                    name: {
                                        offsetY: 20
                                    }
                                }
                            }
                        }
                    }
                };

                const chart = new ApexCharts(document.querySelector("#orderStatisticsChart"), chartData);
                chart.render();
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof ApexCharts !== 'undefined' && document.querySelector('#incomeChart')) {
                const incomeChartData = {
                    series: [{
                        data: {!! json_encode($applicationSeries) !!}
                    }],
                    chart: {
                        height: 215,
                        parentHeightOffset: 0,
                        parentWidthOffset: 0,
                        toolbar: {
                            show: false
                        },
                        type: 'area'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },
                    legend: {
                        show: false
                    },
                    markers: {
                        size: 6,
                        colors: 'transparent',
                        strokeColors: 'transparent',
                        strokeWidth: 4,
                        discrete: [{
                            fillColor: '#fff',
                            seriesIndex: 0,
                            dataPointIndex: 5,
                            strokeColor: '#696cff',
                            strokeWidth: 2,
                            size: 6,
                            radius: 8
                        }],
                        hover: {
                            size: 7
                        }
                    },
                    colors: ['#696cff'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            shadeIntensity: 0.6,
                            opacityFrom: 0.5,
                            opacityTo: 0.25,
                            stops: [0, 95, 100]
                        }
                    },
                    grid: {
                        borderColor: '#e7e7e7',
                        strokeDashArray: 3,
                        padding: {
                            top: -20,
                            bottom: -8,
                            left: -10,
                            right: 8
                        }
                    },
                    xaxis: {
                        categories: {!! json_encode($months) !!},
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            show: true,
                            style: {
                                fontSize: '13px',
                                colors: '#888'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            show: false
                        },
                        min: 0,
                        tickAmount: 4
                    }
                };

                setTimeout(function() {
                    const incomeChart = new ApexCharts(document.querySelector('#incomeChart'),
                        incomeChartData);
                    incomeChart.render().then(() => {
                        window.dispatchEvent(new Event('resize'));
                    });
                }, 200);

            }
        });
    </script>
@endpush
