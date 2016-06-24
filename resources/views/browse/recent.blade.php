@extends('master')

@section('title', 'Recent Breaches')

@section('header')

@endsection

@section('content')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Recent Breaches']
        ]
    ])

    @include('partials.timeline', [
        'showBreachOrganisation' => true
    ])

@endsection