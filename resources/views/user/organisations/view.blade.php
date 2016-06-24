@extends('master')

@section('title', 'Submit Organisation')

@section('content')

    @include('user.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Organisations', 'href' => action('User\DashboardController@listOrganisations')],
            ['title' => $organisation->name]
        ],
        'breadcrumbButtons' => [
            [
                'title' => 'Edit',
                'href' => action('User\SubmissionController@editOrganisation', $organisation->id),
                'if' => $organisation->canEdit(Auth::user()->id)
            ],
            [
                'title' => 'Supersede',
                'href' => action('User\SubmissionController@editOrganisation', $organisation->id),
                'if' => $organisation->canSupersede(Auth::user()->id)
            ],
            [
                'title' => 'Remove',
                'href' => action('User\SubmissionController@deleteOrganisation', $organisation->id),
                'method' => 'post',
                'if' => $organisation->canDelete(Auth::user()->id)
            ]
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
            <td>Name</td>
            <td>{{ $organisation->name }}</td>
        </tr>
        <tr>
            <td>Styled Name</td>
            <td>{{ $organisation->styled_name }}</td>
        </tr>
        <tr>
            <td>Registration Number</td>
            <td>{{ $organisation->registration_number }}</td>
        </tr>
        <tr>
            <td>Incorporation Date</td>
            <td>{{ $organisation->incorporated_on->format(config('date.short')) }}</td>
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
        </tr>
    </table>

@endsection