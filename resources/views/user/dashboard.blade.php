@extends('master')

@section('title', 'Dashboard')

@section('content')

    @include('user.header')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>Your Stats</h3>
                <ul>
                    <li>{{ Auth::user()->approved_organisations }} approved organisations</li>
                    <li>{{ Auth::user()->approved_breaches }} approved breaches</li>
                </ul>
            </div>
            <div class="col-sm-6">

                <h3>Organisation</h3>
                <a href="{{ action('User\SubmissionController@submitOrganisation') }}" class="btn btn-default">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
                </a>
                <a href="{{ action('User\DashboardController@listOrganisations') }}" class="btn btn-default">
                    <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> View
                </a>

                <h3>Breach</h3>
                <p>
                    To add a breach search for the organisation. If you cannot find it you can add it
                    <a href="{{ action('User\SubmissionController@submitOrganisation') }}">here</a>.
                </p>
                <form action="{{ action('User\DashboardController@listBreaches') }}">
                    <div class="input-group">
                        <input
                                type="text"
                                name="q"
                                class="form-control"
                                placeholder="Organisation"
                                value="{{ $searchTerm or '' }}"
                        >
                        <span class="input-group-btn">
                            <input class="btn btn-default" type="submit" value="Search">
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection