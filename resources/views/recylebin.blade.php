@extends('layouts.sidebar')

@section('content')
    <h1 class="btn dash1 m-3">INDENT DETAILS BACKUP</h1>
    <a class="btn dash1 float-end m-3" href="{{ route('fetch-last-two-details') }}">Quoted</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($softDeletedIndents->isEmpty())
        <p>No soft-deleted indents in the recycle bin.</p>
    @else
        <table class="table table-bordered table-stripped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Company Name</th>
                <th>Number 1</th>
                <th>Number 2</th>
                <th>Rate L1</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($softDeletedIndents as $softDeletedIndent)
                    <tr>
                        <td>{{ $softDeletedIndent->id }}</td>
                        <td>{{ $softDeletedIndent->customer_name }}</td>
                        <td>{{ $softDeletedIndent->company_name  }}</td>
                        <td>{{ $softDeletedIndent->number_1 }}</td>
                        <td>{{ $softDeletedIndent->number_2 }}</td>
                        <td>
                    @if($softDeletedIndent->indentRate->isNotEmpty())
                    {{ $softDeletedIndent->indentRate->sortBy('rate')->first()->rate }}
                    @else
                    N/A
                    @endif
                </td>
                        <td>
                            <form action="{{ route('recyclebin.restore', $softDeletedIndent->id) }}" method="post">
                                @csrf
                                @method('patch')
                                <button type="submit" class="btn dash1" style="border:none">Restore</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
