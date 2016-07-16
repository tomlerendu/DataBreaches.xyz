@extends('master')

@section('title', $breaches[0]->date_occurred->format(config('date.short')) . ' breach - ' . $organisation['name'])

@section('header')

    @include('organisation.header')

@endsection

@section('content')

    @include('partials.timeline', [
        'showBreachFull' => true
    ])

@endsection