@extends('backend.layout.template')
@section('title')
    Dashboard
@endsection
@section('body-content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Dashboard</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <!-- Status Cards -->
        <div class="row">
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0 shadow-sm">
                    <div class="bg-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="shopping-bag"></i></div>
                            <div class="media-body"><span class="m-0">Total Sales</span>
                                <h4 class="mb-0 counter">{{ number_format($totalSales, 2) }}</h4><i class="icon-bg"
                                    data-feather="shopping-bag"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0 shadow-sm">
                    <div class="bg-secondary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="shopping-cart"></i></div>
                            <div class="media-body"><span class="m-0">Total Orders</span>
                                <h4 class="mb-0 counter">{{ $totalOrders }}</h4><i class="icon-bg"
                                    data-feather="shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0 shadow-sm">
                    <div class="bg-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="box"></i></div>
                            <div class="media-body"><span class="m-0">Total Stock</span>
                                <h4 class="mb-0 counter">{{ $totalStock }}</h4><i class="icon-bg" data-feather="box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0 shadow-sm">
                    <div class="bg-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="users"></i></div>
                            <div class="media-body"><span class="m-0">Total Users</span>
                                <h4 class="mb-0 counter">{{ $totalUsers }}</h4><i class="icon-bg" data-feather="users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 pb-0">
                        <h5>Monthly Sales</h5>
                    </div>
                    <div class="card-body">
                        <div id="salesChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Low Stock Products -->
            <div class="col-xl-4 col-lg-12 box-col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header border-0">
                        <h5>Low Stock Products</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lowStockProducts as $product)
                                        <tr>
                                            <td class="d-flex align-items-center">
                                                @if($product->pro_img1)
                                                <img src="{{ asset('uploads/' .$product->pro_img1) }}" alt=""
                                                    class="img-fluid img-40 rounded-circle me-2">
                                                @else
                                                <div class="img-fluid img-40 rounded-circle me-2 bg-light d-flex align-items-center justify-content-center">
                                                    <i data-feather="image"></i>
                                                </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="f-w-600 mb-0">{{ Str::limit($product->pro_title, 20) }}</h6>
                                                    @if($product->size)
                                                        <small class="text-muted">Size: {{ $product->size }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-danger">{{ $product->stock }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="col-xl-4 col-lg-12 box-col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header border-0">
                        <h5>Top Selling Products</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topSellingProducts as $item)
                                        <tr>
                                            <td class="d-flex align-items-center">
                                                <img src="{{ asset('uploads/' . $item->product->pro_img1) }}" alt=""
                                                    class="img-fluid img-40 rounded-circle me-2">
                                                <div class="flex-grow-1">
                                                    <h6 class="f-w-600 mb-0">{{ Str::limit($item->product->pro_title, 20) }}
                                                    </h6>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ $item->total_sold }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Top Reviewed Products -->
             <div class="col-xl-4 col-lg-12 box-col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header border-0">
                        <h5>Top Reviewed Products</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topReviewedProducts as $review)
                                        <tr>
                                            <td class="d-flex align-items-center">
                                                <img src="{{ asset('uploads/' . $review->product->pro_img1) }}" alt=""
                                                    class="img-fluid img-40 rounded-circle me-2">
                                                <div class="flex-grow-1">
                                                    <h6 class="f-w-600 mb-0">{{ Str::limit($review->product->pro_title, 20) }}
                                                    </h6>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-warning"><i class="fa fa-star"></i> {{ number_format($review->average_rating, 1) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: [{
                    name: "Sales",
                    data: @json($sales)
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    categories: @json($months),
                    labels: {
                        style: {
                            colors: '#8e8da4',
                            fontSize: '12px',
                            fontFamily: 'Roboto, sans-serif',
                            fontWeight: 400,
                            cssClass: 'apexcharts-xaxis-label',
                        },
                    },
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#8e8da4',
                            fontSize: '12px',
                            fontFamily: 'Roboto, sans-serif',
                            fontWeight: 400,
                            cssClass: 'apexcharts-yaxis-label',
                        },
                    },
                },
                grid: {
                    show: true,
                    borderColor: '#f2f2f2',
                    strokeDashArray: 0,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },   
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },  
                },
                title: {
                    text: 'Monthly Sales Overview',
                    align: 'left',
                    style: {
                        fontSize:  '16px',
                        fontWeight:  'bold',
                        fontFamily:  'Roboto, sans-serif',
                        color:  '#263238'
                    },
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100]
                    }
                },
                colors: ['#7366ff'], // Use your primary color
            };

            var chart = new ApexCharts(document.querySelector("#salesChart"), options);
            chart.render();
        });
    </script>
@endsection
