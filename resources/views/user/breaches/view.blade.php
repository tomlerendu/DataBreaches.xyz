@extends('master')

@section('title', 'View Breach')

@section('content')

    @include('user.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Breaches', 'href' => action('User\DashboardController@listBreaches')],
            ['title' => $breach->organisation->name],
            ['title' => $breach->date_occurred->format('d/m/Y')]
        ],
        'breadcrumbButtons' => [
            [
                'title' => 'Edit',
                'href' => action('User\SubmissionController@editBreach', [$breach->organisation->id, $breach->id]),
                'if' => $breach->canEdit(Auth::user()->id)
            ],
            [
                'title' => 'Supersede',
                'href' => action('User\SubmissionController@editBreach', [$breach->organisation->id, $breach->id]),
                'if' => $breach->canSupersede(Auth::user()->id)
            ],
            [
                'title' => 'Remove',
                'href' => action('User\SubmissionController@deleteBreach', [$breach->organisation->id, $breach->id]),
                'method' => 'post',
                'if' => $breach->canDelete(Auth::user()->id)
            ]
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
            <td>Organisation</td>
            <td>{{ $breach->organisation->name }}</td>
        </tr>
        <tr>
            <td>Date Occurred</td>
            <td>{{ $breach->date_occurred->format(config('date.short')) }}</td>
        </tr>
        <tr>
            <td>Method</td>
            <td>{{ $breach->method_title }} ({{ $breach->method_description }})</td>
        </tr>
        <tr>
            <td>Summary</td>
            <td>{{ $breach->summary }}</td>
        </tr>
        <tr>
            <td>People Affected</td>
            <td>{{ $breach->people_affected }}</td>
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
        </tr>
        <tr>
            <td>Previously Known</td>
            <td>{{ $breach->previously_known ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td>Organisation Stance</td>
            <td>{{ $breach->formatted_response_stance }}</td>
        </tr>
        <tr>
            <td>Patched</td>
            <td>{{ $breach->formatted_response_patched }}</td>
        </tr>
        <tr>
            <td>Customers Informed</td>
            <td>{{ $breach->formatted_response_customers_informed }}</td>
        </tr>
        <tr>
            <td>More Information</td>
            <td><a href="{{ $breach->more_url }}" target="_blank">{{ $breach->more_url }}</a></td>
        </tr>
        <tr>
            <td>Source</td>
            <td>
                <p>{{ $breach->source_name }}</p>
                <small><a href="{{ $breach->source_url }}" target="_blank">{{ $breach->source_url }}</a></small>
            </td>
        </tr>
    </table>

@endsection