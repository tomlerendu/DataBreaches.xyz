@extends('master')

@section('title', $organisation->name)

@section('content')

    @include('user.header')

    @include('partials.breadcrumbs', [
        'breadcrumbLinks' => [
            ['title' => 'Breaches', 'href' => action('User\DashboardController@listBreaches')],
            ['title' => $organisation->name],
            ['title' => 'Submit']
        ]
    ])

    <div class="gap-top">
        <form
                class="form-horizontal"
                method="post"
                action="{{ action('User\SubmissionController@processBreach', $organisation->id) }}"
        >

            {{ csrf_field() }}

            {{-- Previous--}}
            @if (old('previous_id', false))
                <input type="hidden" name="previous_id" value="{{ old('previous_id') }}">
            @endif

            {{-- Organisation --}}
            <div class="form-group">
                <label class="col-sm-2 control-label">Organisation</label>
                <div class="col-sm-10">
                    {{ $organisation->name }}
                    <input type="hidden" name="organisationId" value="{{ $organisation->id }}">
                </div>
            </div>

            {{-- Method --}}
            <div class="form-group{{ $errors->has('method') ? ' has-error' : '' }}">
                <label for="inputPassword" class="col-sm-2 control-label">Method</label>
                <div class="col-sm-10">
                    @foreach(BreachData::$methods as $methodKey => $method)
                        <input
                                type="radio"
                                name="method"
                                id="method-{{ $methodKey }}"
                                value="{{ $methodKey }}"
                                {{ oldRadio('method', $methodKey) }}
                        >
                        <label for="method-{{ $methodKey }}">{{ $method['title'] }}</label>
                        <p>{{ $method['description'] }}</p>
                        <br>
                    @endforeach

                    @if ($errors->has('method'))
                        <span class="help-block">
                            <strong>{{ $errors->first('method') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Date --}}
            <div class="form-group{{ $errors->has('date_occurred') ? ' has-error' : '' }}">
                <label for="date-occurred" class="col-sm-2 control-label">Date</label>
                <div class="col-sm-10">
                    <div class="input-group date" id="dateOccurredContainer">
                        <input
                                type="text"
                                placeholder="YYYY-MM-DD"
                                id="date-occurred"
                                name="date_occurred"
                                value="{{ oldDate('date_occurred') }}"
                                class="form-control"
                        >
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>

                    @if ($errors->has('date_occurred'))
                        <span class="help-block">
                            <strong>{{ $errors->first('date_occurred') }}</strong>
                        </span>
                    @endif

                    {{-- Setup the date picker --}}
                    <script type="text/javascript">
                        $(function () {
                            $('#dateOccurredContainer').datetimepicker({
                                format: 'YYYY-MM-DD',
                                useCurrent: false
                            });
                        });
                    </script>
                </div>
            </div>

            {{-- Summary --}}
            <div class="form-group{{ $errors->has('summary') ? ' has-error' : '' }}">
                <label for="summary" class="control-label col-sm-2">Summary</label>
                <div class="col-sm-10">
                    <textarea
                            class="form-control"
                            id="summary"
                            maxlength="1000"
                            name="summary"
                    >{{ old('summary') }}</textarea>

                    @if ($errors->has('summary'))
                        <span class="help-block">
                            <strong>{{ $errors->first('summary') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- People Affected --}}
            <div class="form-group{{ $errors->has('people_affected') ? ' has-error' : '' }}">
                <label for="people-affected" class="col-sm-2 control-label">People Affected</label>
                <div class="col-sm-10">
                    <input
                            type="number"
                            name="people_affected"
                            id="people-affected"
                            class="form-control"
                            value="{{ old('people_affected') }}"
                    >

                    @if ($errors->has('people_affected'))
                        <span class="help-block">
                            <strong>{{ $errors->first('people_affected') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Data Leaked --}}
            <div class="form-group{{ $errors->has('data_leaked') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Data Leaked</label>
                <div class="col-sm-10">
                    @foreach(BreachData::$dataTypes as $typeKey => $type)
                        <input
                                type="checkbox"
                                name="data_leaked[]"
                                value="{{ $typeKey }}"
                                id="data-type-{{ $typeKey }}"
                                {{ oldCheckArray('data_leaked', $typeKey) }}
                        >
                        <label for="data-type-{{ $typeKey }}">{{ $type }}</label>
                        <br>
                    @endforeach

                    @if ($errors->has('data_leaked'))
                        <span class="help-block">
                            <strong>{{ $errors->first('data_leaked') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Previously known --}}
            <div class="form-group{{ $errors->has('previously_known') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Previously known</label>
                <div class="col-sm-10">
                    <input type="checkbox" id="previouslyKnown" name="previously_known" {{ oldCheck('previously_known') }}>
                    <label for="previouslyKnown">The organisation knew about the vulnerability before the breach occurred</label>
                    @if ($errors->has('previously_known'))
                        <span class="help-block">
                            <strong>{{ $errors->first('previously_known') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Organisation Response --}}
            <div class="form-group{{ $errors->has('response_stance') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Organisation Response</label>
                <div class="col-sm-10">
                    @foreach(BreachData::$responseStances as $stanceKey => $stance)
                        <input
                                type="radio"
                                name="response_stance"
                                id="response-stance-{{ $stanceKey }}"
                                value="{{ $stanceKey }}"
                                {{ oldRadio('response_stance', $stanceKey) }}
                        >
                        <label for="response-stance-{{ $stanceKey }}">{{ $stance }}</label>
                        <br>
                    @endforeach

                    @if ($errors->has('response_stance'))
                        <span class="help-block">
                            <strong>{{ $errors->first('response_stance') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Patch Response --}}
            <div class="form-group{{ $errors->has('response_patched') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Resolution Time</label>
                <div class="col-sm-10">
                    @foreach(BreachData::$responsePatched as $patchKey => $patch)
                        <input
                                type="radio"
                                name="response_patched"
                                id="response-patched-{{ $patchKey }}"
                                value="{{ $patchKey }}"
                                {{ oldRadio('response_patched', $patchKey) }}
                        >
                        <label for="response-patched-{{ $patchKey }}">{{ $patch }}</label>
                        <br>
                    @endforeach

                    @if ($errors->has('response_patched'))
                        <span class="help-block">
                            <strong>{{ $errors->first('response_patched') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Informing Customers Response --}}
            <div class="form-group{{ $errors->has('response_customers_informed') ? ' has-error' : '' }}">
                <label class="col-sm-2 control-label">Informing Customers</label>
                <div class="col-sm-10">
                    @foreach(BreachData::$responseCustomersInformed as $informedKey => $informed)
                        <input
                                type="radio"
                                name="response_customers_informed"
                                id="response-customers-informed-{{ $informedKey }}"
                                value="{{ $informedKey }}"
                                {{ oldRadio('response_customers_informed', $informedKey) }}
                        >
                        <label for="response-customers-informed-{{ $informedKey }}">{{ $informed }}</label>
                        <br>
                    @endforeach

                    @if ($errors->has('response_customers_informed'))
                        <span class="help-block">
                            <strong>{{ $errors->first('response_customers_informed') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- More Information --}}
            <div class="form-group{{ $errors->has('more_url') ? ' has-error' : '' }}">
                <label for="more-information" class="col-sm-2 control-label">More Information</label>
                <div class="col-sm-10">
                    <input
                            type="url"
                            placeholder="URL"
                            id="more-information"
                            class="form-control"
                            name="more_url"
                            value="{{ old('more_url') }}"
                    >

                    @if ($errors->has('more_url'))
                        <span class="help-block">
                            <strong>{{ $errors->first('more_url') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Source Name --}}
            <div class="form-group{{ $errors->has('source_name') ? ' has-error' : '' }}">
                <label for="source" class="col-sm-2 control-label">Source Name</label>
                <div class="col-sm-10">
                    <input
                            type="text"
                            id="source"
                            class="form-control"
                            name="source_name"
                            value="{{ old('source_name') }}"
                    >

                    @if ($errors->has('source_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('source_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Source Url --}}
            <div class="form-group{{ $errors->has('source_url') ? ' has-error' : '' }}">
                <label for="source" class="col-sm-2 control-label">Source URL</label>
                <div class="col-sm-10">
                    <input type="url" class="form-control" name="source_url" value="{{ old('source_url') }}">

                    @if ($errors->has('source_url'))
                        <span class="help-block">
                            <strong>{{ $errors->first('source_url') }}</strong>
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