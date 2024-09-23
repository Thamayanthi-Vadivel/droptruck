<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autocomplete Example</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
</head>
<body>

<h1>Add Location</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('locations.store') }}" method="post">
    @csrf
    <label for="district">District:</label>
    <input type="text" id="district" name="district" required class="autocomplete">


    <button type="submit">Add Location</button>
</form>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
 $(function() {
    $(".autocomplete").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{ route('locations.autocomplete') }}",
                dataType: "json",
                data: {
                    search: request.term
                },
                success: function(data) {
                    response(data.map(item => ({ label: item.district, value: item.id })));
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            var inputId = $(this).attr('id');
            var hiddenInputId = inputId + '_id';
            $('#' + hiddenInputId).val(ui.item.value);
        }
    });
});

</script>

</body>
</html>
