@extends('master')

@section('title', 'Submit Organisation')

@section('content')

    @include('user.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Organisations', 'href' => action('User\DashboardController@listOrganisations')],
            ['title' => 'Submit']
        ]
    ])

    <div class="panel panel-info gap-top">
        <div class="panel-heading">
            <div class="panel-title">Organisation Information</div>
        </div>
        <div class="panel-body">
            Information about UK companies can be found on the <a href="https://beta.companieshouse.gov.uk/" target="_blank">companies house</a> website.
        </div>
    </div>

    <div>
        <form
                method="post"
                action="{{ action('User\SubmissionController@processOrganisation') }}"
                class="form-horizontal gap-top"
        >

            {{ csrf_field() }}

            {{-- Previous--}}
            @if (old('previous_id', false))
                <input type="hidden" name="previous_id" value="{{ old('previous_id') }}">
            @endif

            {{-- Name --}}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="control-label col-sm-2">Name</label>
                <div class="col-sm-10">
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Styled Name --}}
            <div class="form-group{{ $errors->has('styled_name') ? ' has-error' : '' }}">
                <label for="styled-name" class="col-sm-2 control-label">Styled Name</label>
                <div class="col-sm-10">
                    <input
                            type="text"
                            name="styled_name"
                            id="styled-name"
                            class="form-control"
                            value="{{ old('styled_name') }}"
                    >

                    @if ($errors->has('styled_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('styled_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Registration Number --}}
            <div class="form-group{{ $errors->has('registration_number') ? ' has-error' : '' }}">
                <label for="registration-number" class="col-sm-2 control-label">Registration Number</label>
                <div class="col-sm-10">
                    <input
                            type="text"
                            name="registration_number"
                            id="registration-number"
                            class="form-control"
                            value="{{ old('registration_number') }}"
                    >

                    @if ($errors->has('registration_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('registration_number') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Incorporation date --}}
            <div class="form-group{{ $errors->has('incorporated_on') ? ' has-error' : '' }}">
                <label for="incorporated-on" class="col-sm-2 control-label">Incorporation Date</label>
                <div class="col-sm-10">
                    <div class="input-group date" id="incorporatedOnContainer">
                        <input
                                type="text"
                                placeholder="YYYY-MM-DD"
                                name="incorporated_on"
                                id="incorporated-on"
                                class="form-control"
                                value="{{ oldDate('incorporated_on') }}"
                        >
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>

                    {{-- Setup the date picker --}}
                    <script type="text/javascript">
                        $(function () {
                            $('#incorporatedOnContainer').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: false
                            });
                        });
                    </script>

                    @if ($errors->has('incorporated_on'))
                        <span class="help-block">
                            <strong>{{ $errors->first('incorporated_on') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Tags --}}
            <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Tags</label>
                <div class="col-sm-10 padding-0">
                    <div class="container-fluid padding-0">
                        @foreach ($tags as $tag)
                            <div class="col-sm-6">
                                <input
                                        type="checkbox"
                                        name="tags[{{ $tag->id }}]"
                                        id="tag-{{ $tag->id }}"
                                        {{ oldCheckArray('tags', $tag->id) }}
                                >
                                <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    @if ($errors->has('tags'))
                        <span class="help-block">
                            <strong>{{ $errors->first('tags') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>
            </div>

        </form>
    </div>

@endsection