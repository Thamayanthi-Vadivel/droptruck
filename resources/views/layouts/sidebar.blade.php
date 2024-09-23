<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drop Truck</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<style>
    @media (max-width: 767px) {

        /* Hide some elements on small screens */
        .sidebar a {
            display: none;
        }

        /* Show only necessary elements */
        .sidebar .btn.dash,
        .sidebar a.d-block {
            display: block;
        }
    }



    /* Media query for larger screens */
    @media (min-width: 768px) {

        /* Hide/show elements accordingly */
        .sidebar .btn.dash,
        .sidebar a.d-none.d-lg-block {
            display: none;
        }

        .sidebar a.d-block.d-lg-none {
            display: block;
        }
    }

    span {
        color: black;
    }


    .active>span {
        background-color: #F98917 !important;
        color: white;
        padding: 10px;
        border-radius: 15px;
    }

    /* Override Bootstrap's default link styles */
    .nav-link {
        color: black !important;
        /* Change link color */
    }

    .nav-link:hover {
        color: black !important;
        /* Change link hover color */
    }

    .nav-link:active,
    a.nav-link.active {
        background-color: transparent !important;
        /* Remove active link background */
        color: black !important;
        /* Change active link color */
    }

    .nav-link i.fa-arrow-right {
        display: none;
    }

    .nav-link.active i.fa-arrow-right {
        display: inline-block;
        /* Show the arrow icon */
        margin-left: 5px;
        /* Adjust margin as needed */
    }

    .toast-error {
        color: red;
    }
</style>

<body>
    <div class="container-fluid" style="font-size:12px;">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-white shadow">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/dashboard" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline"><img src="{{ asset('images/logo.jpg') }}" alt="Drop Truck Logo" width="100" height="90"></span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="{{ route('indents.dashboard') }}" class="nav-link align-middle px-0 {{ request()->route()->getName() === 'indents.dashboard' ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">
                                    <img src="{{ asset('images/dashboard/dashboard.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1">Dashboard <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3 || auth()->user()->role_id == 4 )
                        <li class="nav-item">
                            <a href="{{ route('indents.index') }}" class="nav-link align-middle px-0 {{ request()->route()->getName() === 'indents.indent' ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">
                                    <img src="{{ asset('images/dashboard/home.png') }}" height="25" width="25" class="img">
                                    <span class="ms-1">Enquirys <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('confirmed-trips')}}" class="nav-link align-middle px-0 {{ request()->route()->getName() === 'trips' ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">
                                    <img src="{{ asset('images/dashboard/trip.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Trips <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                        <li class="nav-item">
                            <a href="{{route('reports')}}" class="nav-link align-middle px-0 {{ request()->route()->getName() === 'reports' ? 'active' : '' }}">
                                <span class="d-none d-sm-inline me-2">
                                    <img src="{{ asset('images/dashboard/reports.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Reports <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                        <li class="nav-item">
                            <a href="{{route('customers.create')}}" class="nav-link align-middle px-0 {{ request()->route()->getName() === 'customers.create' ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">
                                    <img src="{{ asset('images/dashboard/customer.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Customers <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 4)

                        <li class="nav-item">
                            <a href="{{ route('suppliers.index') }}" class="nav-link px-0 {{ request()->route()->getName() === 'suppliers.index' ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">
                                    <img src="{{ asset('images/dashboard/suppiliers.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Suppliers <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>

                        @endif

                        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                        <li class="nav-item">
                            <a href="{{route('truck.truck-types')}}" class="nav-link px-0 {{ request()->route()->getName() === 'truck.truck-types' ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">
                                    <img src="{{ asset('images/dashboard/trucks.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Truck Type</span>
                                </span>
                            </a>
                        </li>
                      
                        <li class="nav-item">
                            <a href="{{route('vehicles.create')}}" class="nav-link px-0 {{ request()->route()->getName() === 'vehicles.create' ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">
                                    <img src="{{ asset('images/dashboard/trucks.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Trucks <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('accounts.ongoing')}}" class="nav-link align-middle px-0 {{ request()->route()->getName() === 'accounts' ? 'active' : '' }}">
                                <span class="d-none d-sm-inline me-2">
                                    <img src="{{ asset('images/dashboard/pricing.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Accounts <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role_id == 1)
                        <li class="nav-item">
                            <a href="{{route('pricings.create')}}" class="nav-link align-middle px-0 {{ request()->route()->getName() === 'pricing.create' ? 'active' : '' }}">
                                <span class="d-none d-sm-inline me-2">
                                    <img src="{{ asset('images/dashboard/pricing.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Pricing <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employees.create') }}" class="nav-link align-middle px-0 {{ request()->route()->getName() === 'employees.create' ? 'active' : '' }}">
                                <span class="d-none d-sm-inline me-2">
                                    <img src="{{ asset('images/dashboard/master.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Master <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role_id == 5)
                        <li class="nav-item">
                            <a href="{{route('confirmed-trips')}}" class="nav-link align-middle px-0 {{ request()->route()->getName() === 'trips' ? 'active' : '' }}">
                                <span class="ms-1 d-none d-sm-inline">
                                    <img src="{{ asset('images/dashboard/trip.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Trips <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                        <li class="nav-item">
                            <a href="#." class="nav-link align-middle px-0 {{ request()->route()->getName() === 'reports' ? 'active' : '' }}">
                                <span class="d-none d-sm-inline me-2">
                                    <img src="{{ asset('images/dashboard/reports.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Reports <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{route('accounts.ongoing')}}" class="nav-link align-middle px-0 {{ request()->route()->getName() === 'accounts' ? 'active' : '' }}">
                                <span class="d-none d-sm-inline me-2">
                                    <img src="{{ asset('images/dashboard/pricing.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Accounts <i class="fas fa-arrow-right"></i></span>
                                </span>
                            </a>
                        </li>
                        @endif
                        
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link align-middle px-0" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="d-none d-sm-inline me-2">
                                    <img src="{{ asset('images/dashboard/arrow.png') }}" height="20" width="20" class="img">
                                    <span class="ms-1"> Logout</span>
                                </span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="col-md-9 col-xl-10 px-sm-4 px-0">
                <!-- This div controls the width of the content on the right side -->


                <div class="content-wrapper">
                    <!-- Your form and other content -->
                    @yield('content')
                </div>
            </div>


            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
            <script src="{{ asset('js/script.js') }}"></script>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2lqVIxP7qPSSMnoEjUnq30xWiQCOu-Ds&libraries=places" async defer></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <!--<script src="{{ asset('js/validation.js') }}"></script>-->
</body>

</html>