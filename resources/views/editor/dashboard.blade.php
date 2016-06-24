@extends('master')

@section('title', 'Editor Dashboard')

@section('content')

    @include('editor.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Dashboard']
        ]
    ])

    <h3>Commands</h3>
    @foreach ($commands as $command => $description)
        @include('partials.formbutton', [
            'buttonAction' => action('Editor\DashboardController@command', $command),
            'buttonTitle' => $description,
            'buttonMethod' => 'post'
        ])
    @endforeach

@endsection