@extends('layouts.sidebar')

@section('content')
<div class="horizontal-menu">
    <ul>
        <li class="active"><a href="{{ route('indents.index') }}" class="dropdown-item">Unquoted</a></li>
        <li><a class="dropdown-item" href="{{ route('fetch-last-two-details') }}">Quoted</a></li>
        <li><a class="dropdown-item" href="#.">On Time</a></li>
        <li><a class="dropdown-item" href="{{ route('indents.confirm', ['id' => $indent->id]) }}">$confirmationCount</a></li>
    </ul>
</div>
@endsection
