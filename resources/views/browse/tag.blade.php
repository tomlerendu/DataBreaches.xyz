@extends('master')

@section('title', $tag->name)

@section('content')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Browse', 'href' => action('BrowseController@browse')],
            ['title' => $tag->name],
        ],
        'breadcrumbButtons' => [
            [
                'title' => 'Submit Organisation',
                'href' => action('User\SubmissionController@submitOrganisation')
            ]
        ]
    ])

    @if (!$organisations->isEmpty())
        <div class="container-fluid">
            <div class="row">
                @foreach ($organisations as $organisation)
                    <div class="col-sm-6 padding-0">
                        <h3><a href="{{ action('OrganisationController@view', $organisation->slug) }}">{{ $organisation->styled_name }}</a></h3>
                        <p>{{ $organisation->breach_count }} breach{{ $organisation->breach_count != 1 ? 'es' : '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <p>There are no organisations in this category.</p>
    @endif
@endsection