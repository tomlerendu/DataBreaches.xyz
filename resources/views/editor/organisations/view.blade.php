@extends('master')

@section('title', 'Review Organisation')

@section('content')

    @include('editor.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Organisations', 'href' => action('Editor\OrganisationController@list')],
            ['title' => $organisation->name]
        ],
        'breadcrumbButtons' => [
            [
                'title' => 'Missing Info',
                'href' => action('Editor\OrganisationController@status', $organisation->id),
                'params' => ['status' => 'RejectedInfo'],
                'method' => 'post'
            ],
            [
                'title' => 'Duplicate',
                'href' => action('Editor\OrganisationController@status', $organisation->id),
                'params' => ['status' => 'RejectedDuplicate'],
                'method' => 'post'
            ],
            [
                'title' => 'Accept',
                'href' => action('Editor\OrganisationController@status', $organisation->id),
                'params' => ['status' => 'Accepted'],
                'method' => 'post'
            ],
        ]
    ])

    <table class="table table-bordered">
        <tr>
            <th>Status</th>
            <td>{{ $organisation->getReadableStatus() }}</td>
            <th>Last Change</th>
            <td>{{ $organisation->updated_at->format(config('date.long')) }}</td>
        </tr>
    </table>

    <table class="table table-bordered">
        <tr>
            <th></th>
            <th>Current</th>
            <th>Previous</th>
        </tr>
        <tr>
            <td>Name</td>
            <td>{{ $organisation->name }}</td>
            <td>{{ $previousOrganisation->name or 'None' }}</td>
        </tr>
        <tr>
            <td>Styled Name</td>
            <td>{{ $organisation->styled_name }}</td>
            <td>{{ $previousOrganisation->name or 'None' }}</td>
        </tr>
        <tr>
            <td>Registration Number</td>
            <td>{{ $organisation->registration_number }}</td>
            <td>{{ $previousOrganisation->registration_number or 'None' }}</td>
        </tr>
        <tr>
            <td>Incorporation Date</td>
            <td>{{ $organisation->incorporated_on->format(config('date.short')) }}</td>
            <td>{{
                isset($previousOrganisation) ?
                    $previousOrganisation->incorporated_on->format(config('date.short')) :
                    'None'
            }}</td>
        </tr>
        <tr>
            <td>Tags</td>
            <td>
                <div class="container-fluid">
                    <div class="row">
                        @foreach( $organisation->tags as $tag)
                            <div class="col-sm-6 padding-0">{{ $tag->name }}</div>
                        @endforeach
                    </div>
                </div>
            </td>
            <td>
                <div class="container-fluid">
                    <div class="row">
                        @if (isset($previousOrganisation))
                            @forelse( $previousOrganisation->tags as $tag)
                                <div class="col-sm-6 padding-0">{{ $tag->name }}</div>
                            @endforeach
                        @else
                            None
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

@endsection