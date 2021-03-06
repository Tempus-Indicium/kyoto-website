@extends('layouts.app')

@section('content')
<h1>Top 10 stations based on visibility in Asia</h1>
<div class="table">
    <table border="1">
        <thead>
            <tr> <!-- tr = row -->
                <th colspan="4">
                    Date this list was generated: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $actualDate->toDateString() }}
                </th>
            </tr>
            <tr> <!-- tr = row -->
                <th id="number">#</th> <!-- td = column -->
                <th>Country</th>
                <th>Station</th>
                <th>Visibility (in kms)</th>
            </tr>
        </thead>
        <?php $i = 1; ?>
        @foreach($stations as $station)
            <tr class="alt">
                <td scope="row">{{ $i }}</td>
                <td>{{ $station['country'] }}</td>
                <td>{{ $station['name'] }}</td>
                <td>{{ number_format($station['averageVisibility'],2) }}</td>
            </tr>
            <?php $i++ ?>
        @endforeach
    </table>
</div>
@endsection
