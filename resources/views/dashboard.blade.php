@extends('layouts.sidebar')
@section('content')

<div class="main">
    <div class="row align-items-center">
        <div>
            <h2 class="btn btn-primary text-white fw-bolder float-end mt-1">User : {{ auth()->user()->name }}</h2>
        </div>
        <div class="col">
            <div class="welcome-back">Welcome Back<span class="drop-truck">, DROP TRUCK!</span></div>
        </div>
        <div class="col-auto">
            <!-- <a href="#."><img src="images/dashboard/search.png" height="25" width="25"></a> -->
            <!-- Inside the 'content-wrapper' div -->
            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
            <button type="button" style="background-color: #F98917;border-radius: 20px; font-size:12px;" class="btn mt-3" data-bs-toggle="modal" data-bs-target="#createIndentModal">Create Indent</button>
            @endif
            <!--<img src="images/dashboard/profile.png" height="25" width="25">-->
            <!--<a href="#" class="text-dark text-decoration-none" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">-->
            <!--    <img src="images/dashboard/arrow.png" height="25" width="25">-->
            <!--</a>-->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            <!-- Modal -->
            <div class="modal fade container-fluid" id="createIndentModal" tabindex="-1" aria-labelledby="createIndentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header fw-bolder text-white" style="background-color:#F98917">
                            <h5 class="modal-title" id="createIndentModalLabel">Create Indent</h5>
                            <button type="button" class="close btn" data-dismiss="modal" aria-label="Close" fdprocessedid="uoe8">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @include('indent.create')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="top mt-2 mb-0">
        <p class="fw-bold">Indents</p>
        <div class="d-flex flex-row gap-1">
            <div class="indents p-3">
                <div class="d-flex gap-6 text-center top1">
                    <div>
                        <p class="shade">{{ $unquotedIndentCount }}</p> unquoted
                    </div>

                    <div>
                        <p class="shade"> {{ $quotedIndentCount }}</p> quoted
                    </div>
                    <div>
                        <p class="shade"> {{ $ontime }} </p> ontime
                    </div>
                    <div>
                        <p class="shade"> {{ $delayed }} </p> Delayed
                    </div>
                    <div>
                        <p class="shade">{{ $confirmationCount }}</p> confirmed
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="top mt-2 mb-0">
        <div class="d-flex flex-row gap-1">
            <div class="indents p-3">
                <div class="d-flex gap-4 text-center top1">
                    <div>
                        <p class="shade">{{$waitingCount}}</p> Waiting For Drivier
                    </div>

                    <div>
                        <p class="shade">{{$loadingCount}}</p> Loading
                    </div>
                    <div>
                        <p class="shade">{{ $roadCount }}</p> On The Road
                    </div>
                    <div>
                        <p class="shade">{{ $unloadingCount }}</p> Unloading
                    </div>
                    <div>
                        <p class="shade">{{ $podCount }}</p> POD
                    </div>
                <div>
                    <p class="shade">{{$confirmedtrips}}</p> Complete Tripss
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bottom mt-3">
    <div class="row">
        <div class="col-md-6 d-flex flex-column">
            <div class="firsthalf">
                <div class="d-flex gap-3 p-2 mb-0 align-items-start">
                    <p class="fw-bolder">Live Map</p>
                    <p class="fw-bolder">History</p>
                </div>
                <div class="range p-3 mt-0">
                    <h5 class="fw-bolder mb-0">Select Range</h5>
                    <br><br>
                    <input type="text" id="datepicker" placeholder="Date">
                    <span class="fw-bolder">To </span>
                    <input type="text" id="datepicker1" placeholder="Date">
                    <div class="ms-5 fw-bolder labels"> <!-- Your labels will be displayed here --> </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-center align-items-center">
            <div class="secondhalf">
                <div class="piechart">
                    <canvas id="myPieChart" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // setTimeout(function(){
    //   location.reload();
    // }, 100000); // 10000 milliseconds = 10 seconds
</script>
@endsection
