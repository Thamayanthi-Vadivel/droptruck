<!-- @extends('layouts.sidebar')
@section('content')\
<div id="quoted-content">
<div class="horizontal-menu mt-3">
    <ul>
        <li class="{{ request()->is('indents/index') ? 'active' : '' }}"><a href="{{ route('indents.index') }}" class="dropdown-item">Unquoted</a></li>
        <li class="{{ request()->is('fetch-last-two-details') ? 'active' : '' }}"><a class="dropdown-item" href="{{ route('fetch-last-two-details') }}">Quoted</a></li>
        <li class="{{ request()->is('indents.confirm') ? 'active' : '' }}"><a class="dropdown-item" href="indents.confirm">Confirmed</a></li>
    </ul>   
 </div>
 <a href="{{ route('showIndentDetails')}}" class="btn btn-danger float-end m-2">Details</a>
<table class="table table-bordered table-stripped">
    <thead>
        <tr>
            <th>Indent ID</th>
            <th>Customer Name</th>
            <th>Company Name</th>
            <th>Number 1</th>
            <th>Number 2</th>
            <th>Rate L1</th>
            @if(isset($secondLeastRateAmount)) 
                <th>Rate L2</th>
            @endif
            <th>Action</th> 
        </tr>
    </thead>
    <tbody>
        @foreach($indents as $indent)
        <tr>
            <td>{{ $indent->id }}</td>
            <td>{{ $indent->customer_name }}</td>
            <td>{{ $indent->company_name }}</td>
            <td>{{ $indent->number_1 }}</td>
            <td>{{ $indent->number_2 }}</td>
            <td>
                @if($indent->indentRate->isNotEmpty())
                {{ $indent->indentRate->sortBy('rate')->first()->rate }}
                @else
                N/A
                @endif
            </td>

            @if(isset($secondLeastRateAmount)) 
                <td>{{$secondLeastRateAmount }}</td>
            @endif
    
            <td class="d-flex">
                @if(auth()->user()->type == 'superadmin' || auth()->user()->type == 'admin' || auth()->user()->type == 'sales')
                <a href="{{ route('indents.show', $indent->id) }}" class="btn"><i class="fa fa-eye" style="font-size:17px"></i></a>
                <div>@include('indent.edit')</div>
                <div>@include('indent.delete')</div>
                <a href="{{ route('indents.confirm', ['id' => $indent->id]) }}" class="btn btn-success m-2">Win</a>
                @endif
                @if(auth()->user()->type == 'suppliers')
                <div>@include('rate')</div>
                @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
 -->