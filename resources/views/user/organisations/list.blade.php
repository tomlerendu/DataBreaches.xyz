@extends('master')

@section('title', 'Dashboard - Organisations')

@section('content')

    @include('user.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Organisations']
        ],
        'breadcrumbButtons' => [
            ['title' => 'Submit Organisation', 'href' => action('User\SubmissionController@submitOrganisation')]
        ]
    ])

    <table class="table table-striped">
        <tr>
            <th>Organisation</th>
            <th>Last Change</th>
            <th>Status</th>
        </tr>
        @foreach ($organisations as $organisation)
            <tr>
                <td>
                    <a href="{{ action('User\DashboardController@getOrganisation', $organisation->id) }}">
                        {{ $organisation->name }}
                    </a>
                </td>
                <td>{{ $organisation->updated_at->format(config('date.human')) }}</td>
                <td>{{ $organisation->getReadableStatus() }}</td>
            </tr>
        @endforeach
    </table>

@endsection