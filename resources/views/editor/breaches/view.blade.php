@extends('master')

@section('title', 'View Breach')

@section('content')

    @include('editor.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Breaches', 'href' => action('Editor\BreachController@list')],
            ['title' => $breach->organisation->name],
            ['title' => $breach->date_occurred->format(config('date.short'))]
        ],
        'breadcrumbButtons' => [
            [
                'title' => 'Missing Info',
                'href' => action('Editor\BreachController@status', $breach->id),
                'params' => ['status' => 'RejectedInfo'],
                'method' => 'post'
            ],
            [
                'title' => 'Duplicate',
                'href' => action('Editor\BreachController@status', $breach->id),
                'params' => ['status' => 'RejectedDuplicate'],
                'method' => 'post'
            ],
            [
                'title' => 'Invalid Source',
                'href' => action('Editor\BreachController@status', $breach->id),
                'params' => ['status' => 'RejectedSource'],
                'method' => 'post'
            ],
            [
                'title' => 'Accept',
                'href' => action('Editor\BreachController@status', $breach->id),
                'params' => ['status' => 'Accepted'],
                'method' => 'post'
            ],
        ]
    ])

    <table class="table table-bordered">
        <tr>
            <th>Status</th>
            <td>{{ $breach->getReadableStatus() }}</td>
            <th>Last Change</th>
            <td>{{ $breach->updated_at->format(config('date.long')) }}</td>
        </tr>
    </table>

    <table class="table table-bordered">
        <tr>
            <th></th>
            <th>Current Record</th>
            <th>Previous Record</th>
        </tr>
        <tr>
            <td>Organisation</td>
            <td>{{ $breach->organisation->name }}</td>
            <td>{{ isset($previousBreach) ? $previousBreach->organisation->name : 'None' }}</td>
        </tr>
        <tr>
            <td>Date Occurred</td>
            <td>{{ $breach->date_occurred->format(config('date.short')) }}</td>
            <td>
                {{ isset($previousBreach) ? $previousBreach->date_occurred->format(config('date.short')) : 'None' }}
            </td>
        </tr>
        <tr>
            <td>Method</td>
            <td>{{ $breach->method_title }} ({{ $breach->method_description }})</td>
            <td>
                @if (isset($previousBreach))
                    {{ $previousBreach->method_title }} ({{ $previousBreach->method_description }})
                @else
                    None
                @endif
            </td>
        </tr>
        <tr>
            <td>Summary</td>
            <td>{{ $breach->summary }}</td>
            <td>{{ $previousBreach->summary or 'None' }}</td>
        </tr>
        <tr>
            <td>People Affected</td>
            <td>{{ $breach->people_affected }}</td>
            <td>{{ $previousBreach->people_affected or 'None' }}</td>
        </tr>
        <tr>
            <td>Data Leaked</td>
            <td>
                <div class="container-fluid">
                    <div class="row">
                        @foreach ($breach->formatted_data_leaked as $type)
                            <div class="col-sm-6 padding-0">
                                {{ $type }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </td>
            <td>
                @if (isset($previousBreach))
                    <div class="container-fluid">
                        <div class="row">
                            @foreach ($previousBreach->formatted_data_leaked as $type)
                                <div class="col-sm-6 padding-0">
                                    {{ $type }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    None
                @endif
            </td>
        </tr>
        <tr>
            <td>Previously Known</td>
            <td>{{ $breach->previously_known ? 'Yes' : 'No' }}</td>
            <td>{{ isset($previousBreach) ? ($previousBreach->previously_known ? 'Yes' : 'No') : 'None' }}</td>
        </tr>
        <tr>
            <td>Organisation Stance</td>
            <td>{{ $breach->formatted_response_stance }}</td>
            <td>{{ $previousBreach->formatted_response_stance or 'None' }}</td>
        </tr>
        <tr>
            <td>Patched</td>
            <td>{{ $breach->formatted_response_patched }}</td>
            <td>{{ $previousBreach->formatted_response_patched or 'None' }}</td>
        </tr>
        <tr>
            <td>Customers Informed</td>
            <td>{{ $breach->formatted_response_customers_informed }}</td>
            <td>{{ $previousBreach->formatted_response_customers_informed or 'None' }}</td>
        </tr>
        <tr>
            <td>More Information</td>
            <td><a href="{{ $breach->more_url }}" target="_blank">{{ $breach->more_url }}</a></td>
            <td>
                @if (isset($previousBreach))
                    <a href="{{ $previousBreach->more_url }}" target="_blank">{{ $previousBreach->more_url }}</a>
                @else
                    None
                @endif
            </td>
        </tr>
        <tr>
            <td>Source</td>
            <td>
                <p>{{ $breach->source_name }}</p>
                <small><a href="{{ $breach->source_url }}" target="_blank">{{ $breach->source_url }}</a></small>
            </td>
            <td>
                @if (isset($previousBreach))
                    <p>{{ $breach->source_name }}</p>
                    <small>
                        <a href="{{ $previousBreach->source_url }}" target="_blank">
                            {{ $previousBreach->source_url }}
                        </a>
                    </small>
                @else
                    None
                @endif
            </td>
        </tr>
    </table>

@endsection