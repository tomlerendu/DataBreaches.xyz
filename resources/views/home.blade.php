@extends('master')

@section('header')

    <div class="intro-header">
        <h1>What is {{ config('app.name') }}?</h1>
        <p>{{ config('app.name') }} categorieses how well an organisation handles your personal information. Each organisation is assigned a score based on the number of data breaches they have had and how well they have reacted to them.</p>
    </div>

@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-5 padding-0">
                <p>Browse organisations by category.</p>
                <a href="{{ action('BrowseController@browse') }}" class="btn btn-primary btn-lg" role="button">
                    Browse
                </a>
            </div>
            <div class="col-sm-7 padding-0">
                <p>Search for a specific organisation.</p>
                <form action="{{ action('HomeController@search') }}">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" name="q" placeholder="Search Organisations">
                        <span class="input-group-btn">
                            <input class="btn btn-default" type="submit" value="Search">
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <h2>Recent Breaches</h2>

    @include('partials.timeline', [
        'showBreachOrganisation' => true
    ])

    <a href="{{ action('BrowseController@recent') }}">View All</a>

@endsection