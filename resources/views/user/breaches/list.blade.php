@extends('master')

@section('title', 'Dashboard')

@section('content')

    @include('user.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Breaches']
        ]
    ])

    <div class="panel panel-info gap-top">

        <div class="panel-heading">
            <div class="panel-title">Submit Breach</div>
        </div>

        <div class="panel-body">
            <p>
                Search for the organisation that was breached. If you cannot find it you can add it
                <a href="{{ action('User\SubmissionController@submitOrganisation') }}">here</a>.
            </p>
            <form>
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Organisation" value="{{ $searchTerm or '' }}">
                    <span class="input-group-btn">
                        <input class="btn btn-default" type="submit" value="Search">
                    </span>
                </div>
            </form>

            @if (isset($organisations) && count($organisations) !== 0)
                <ul class="list-group">
                    @foreach ($organisations as $organisation)
                        <li class="list-group-item">
                            <span class="badge">{{ $organisation->getReadableStatus() }}</span>
                            <a href="{{ action('User\SubmissionController@submitBreach', $organisation->id) }}">
                                {{ $organisation->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @elseif (isset($organisations))
                <p>No organisations found.</p>
            @endif
        </div>

    </div>

    <table class="table table-striped gap-top">
        <tr>
            <th>Breach</th>
            <th>Organisation</th>
            <th>Last Change</th>
            <th>Status</th>
        </tr>
        @foreach ($breaches as $breach)
            <tr>
                <td>
                    <a href="{{ action('User\DashboardController@getBreach', $breach->id) }}">
                        {{ $breach->date_occurred->format(config('date.human')) }}
                    </a>
                </td>
                <td>{{ $breach->organisation->name }}</td>
                <td>{{ $breach->updated_at->format(config('date.long')) }}</td>
                <td>{{ $breach->getReadableStatus() }}</td>
            </tr>
        @endforeach
    </table>

@endsection