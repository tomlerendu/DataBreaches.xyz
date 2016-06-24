@extends('master')

@section('title', 'Browse')

@section('content')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Browse']
        ],
        'breadcrumbButtons' => [
            [
                'title' => 'Submit Organisation',
                'href' => action('User\SubmissionController@submitOrganisation')
            ]
        ]
    ])

    <div class="container-fluid">
        <div class="row">
            @foreach($tags as $tagKey => $tag)
                <div class="col-sm-6 padding-0">
                    <h3><a href="/browse/tag/{{ $tag->id }}">{{ $tag->name }}</a></h3>
                    <p>{{ $tag->organisation_count }} organisations</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection