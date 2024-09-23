<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Pickup City</th>
            <th>Drop City</th>
            <th>Vehicle Type</th>
            <th>Body Type</th>
            <th>Rate From</th>
            <th>Rate To</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pricings as $pricing)
        <tr>
            <td>{{ $pricing->id }}</td>
            <td>{{ $pricing->pickup_city }}</td>
            <td>{{ $pricing->drop_city }}</td>
            <td>{{ $pricing->vehicle_type }}</td>
            <td>{{ $pricing->body_type }}</td>
            <td>{{ $pricing->rate_from }}</td>
            <td>{{ $pricing->rate_to }}</td>
            <td>
                <a href="{{ route('pricings.show', ['pricing' => $pricing]) }}">
                    <i class="fa fa-eye" style="font-size:17px"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>