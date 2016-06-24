@extends('master')

@section('title', 'Organisations')

@section('content')

    @include('editor.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Organisations']
        ]
    ])

    <table class="table table-striped">
        <tr>
            <th>Organisation</th>
            <th>Last Change</th>
        </tr>
        @foreach ($organisations as $organisation)
            <tr>
                <td><a href="{{ action('Editor\OrganisationController@view', $organisation->id) }}">{{ $organisation->name }}</a></td>
                <td>{{ $organisation->updated_at->format(config('date.long')) }}</td>
            </tr>
        @endforeach
    </table>
@endsection