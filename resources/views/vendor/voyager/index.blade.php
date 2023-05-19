@extends('voyager::master')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .page-content {
        padding: 0px 40px 0 40px;
    }
</style>

@section('content')
    <div class="page-content">
        @include('voyager::alerts')
        @include('voyager::dimmers')
        <h1>Alien Count by Country</h1>
    <div class="row">
            <div class="col-md-6">
                 <canvas id="barChart" width="400" height="400"></canvas>
            </div>
            <div class="col-md-6">
            <h2>Room for other things</h2>
            </div>
    </div>
        @php 
        $countries = App\Models\Country::withCount('aliens')->get();
        // put county names and alien counts into arrays
        $country_names = [];
        $alien_counts = [];

        // loop through countries and add to array
        foreach ($countries as $country) {
            $country_names[] = $country->name;
            $alien_counts[] = $country->aliens_count;
        }

        @endphp

<div class="content">
                <script>
                    // PHP to JavaScript data conversion
                    let countries = @json($country_names);
                    let data = @json($alien_counts);

                    // Create bar chart
                    var ctx = document.getElementById('barChart').getContext('2d');
                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: countries,
                            datasets: [{
                                label: '',
                                data: data,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
      </div>
    </div>
@stop