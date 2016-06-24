@extends('master')

@section('title', 'Tags')

@section('content')

    @include('editor.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Tags']
        ]
    ])

    <div class="panel panel-info gap-top">

        <div class="panel-heading">
            <div class="panel-title">Add Tag</div>
        </div>

        <div class="panel-body">
            <form method="post" action="{{ action('Editor\TagsController@submit') }}">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="text" name="tag" class="form-control" placeholder="Tag" value="{{ old('tag') }}">
                    <span class="input-group-btn">
                        <input class="btn btn-default" type="submit" value="Add">
                    </span>
                </div>
                @if ($errors->has('tag'))
                    <span class="help-block">
                        <strong>{{ $errors->first('tag') }}</strong>
                    </span>
                @endif
            </form>
        </div>

    </div>

    <table class="table table-striped">
        <tr>
            <th>Tag</th>
            <th>Name</th>
            <th>Organisations</th>
            <th>Action</th>
        </tr>
        @foreach ($tags as $tag)
            <tr>
                <td>{{ $tag->id }}</td>
                <td>{{ $tag->name }}</td>
                <td>{{ $tag->organisation_count }}</td>
                <td>
                    @include('partials.formbutton', [
                        'buttonTitle' => 'Delete',
                        'buttonAction' => action('Editor\TagsController@delete', $tag->id),
                        'buttonCSS' => 'btn-sm',
                        'buttonMethod' => 'post'
                    ])
                </td>
            </tr>
        @endforeach
    </table>

@endsection