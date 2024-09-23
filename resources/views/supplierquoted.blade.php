@extends('layouts.sidebar')
@section('content')
    <h1>Quoted Indents</h1>

    <table>
        <thead>
            <tr>
                <th>Indent ID</th>
                <th>Customer Name</th>
                <th>Rate</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($indents as $indent)
                <tr>
                    <td>{{ $indent->id }}</td>
                    <td>{{ $indent->customer_name }}</td>
                    <td>
                        @if ($indent->indentRate->count() > 0)
                            {{ $indent->indentRate->first()->rate }}
                        @else
                            No Rate Available
                        @endif
                    </td>
                    <td>
                        @if ($indent->indentRate->count() > 0)
                            {{ $indent->indentRate->first()->remarks }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($secondLeastRateAmount !== null)
        <p>Second Least Rate: {{ $secondLeastRateAmount }}</p>
    @endif
@endsection
