@extends('master')

@section('title', 'Breaches')

@section('content')

    @include('editor.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Breaches']
        ]
    ])

    <table class="table table-striped">
        <tr>
            <th>Breach Date</th>
            <th>Organisation</th>
            <th>Last Change</th>
        </tr>
        @foreach ($breaches as $breach)
            <tr>
                <td>
                    <a href="{{ action('Editor\BreachController@view', $breach->id) }}">
                        {{ $breach->date_occurred->format(config('date.short')) }}
                    </a>
                </td>
                <td>{{ $breach->organisation->name }}</td>
                <td>{{ $breach->updated_at->format(config('date.long')) }}</td>
            </tr>
        @endforeach
    </table>
@endsection