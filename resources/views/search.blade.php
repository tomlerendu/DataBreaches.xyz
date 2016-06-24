@extends('master')

@section('title', 'Search')

@section('content')

    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">Search Results</div>
        </div>
        <div class="panel-body">
            <span class="pull-right">{{ count($organisations) }} {{ count($organisations) == 1 ? 'result' : 'results' }} found</span>
            Searching for "{{ $searchTerm }}"
        </div>
    </div>

    <div class="container-fluid">

        @forelse($organisations as $organisation)
            <div class="media">
                <div class="media-body">
                    <h4 class="media-heading">
                        <a href="{{ action('OrganisationController@view', $organisation->slug) }}">{{ $organisation->name }}</a>
                    </h4>
                    {{ $organisation->breach_count }} breach{{ $organisation->breach_count != 1 ? 'es' : '' }}
                </div>
            </div>
        @empty
            <p>No results found</p>
        @endforelse

    </div>
@endsection