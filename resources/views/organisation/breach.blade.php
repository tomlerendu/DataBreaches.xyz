@extends('master')

@section('title', $organisation['name'])

@section('header')

    @include('organisation.header')

@endsection

@section('content')

    @include('partials.timeline', [
        'showBreachFull' => true
    ])

@endsection