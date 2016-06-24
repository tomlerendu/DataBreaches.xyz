@extends('master')

@section('title', $organisation['name'])

@section('header')

    @include('organisation.header')

@endsection

@section('content')

    @include('partials.timeline')

    @if ($breaches->count() == 0)
        <p>There are no recorded data breaches for this organisation.</p>
    @endif

@endsection