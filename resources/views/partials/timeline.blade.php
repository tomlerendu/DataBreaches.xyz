
{{-- The following parameters can be passed to this view --}}
{{-- $showBreachOrganisation - Set if organisation information should be shown --}}
{{-- $showBreachFull - Set if the full breach should be shown --}}

@if ($breaches->count() > 0)

    <ul class="container-fluid breach-timeline gap-top">

        @foreach ($breaches as $breach)

            <li class="row breach">
                <div class="col-xs-4 col-sm-3 col-md-2">
                    <div class="breach-date">
                        <div class="breach-date-day">{{ $breach->date_occurred->format('d M') }}</div>
                        <div class="breach-date-year">{{ $breach->date_occurred->format('Y') }}</div>
                    </div>
                    @if (isset($showBreachFull))
                        <div class="breach-edit">
                            <a
                                href="{{ action('User\SubmissionController@editBreach', [
                                    $breach->organisation->id,
                                    $breach->id
                                ]) }}"
                                class="btn btn-default btn-sm">
                                    Edit
                            </a>
                        </div>
                    @endif
                </div>
                <div class="col-xs-8 col-sm-9 col-md-10">
                    <div class="panel panel-default">
                        @if (isset($showBreachOrganisation))
                            <div class="panel-heading">
                                <h3 class="panel-title">{{ $breach->organisation->name }}</h3>
                            </div>
                        @endif
                        <div class="panel-body breach-content">

                            <ul class="breach-meta">
                                <li><strong>Method</strong><span>{{ $breach->method_title }}</span></li>
                                <li><strong>Severity</strong><span>{{ $breach->calculateScore() }}</span></li>
                            </ul>

                            <p>{{ $breach->summary }}</p>

                            @if (isset($showBreachFull))
                                <h3>Data Leaked</h3>
                                <div class="container-fluid">
                                    <div class="row">
                                        <ul>
                                            @foreach ($breach->formatted_data_leaked as $dataType)
                                                <li class="col-sm-6">{{ $dataType }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <h3>Organisation Response</h3>
                                <ul>
                                    <li>
                                        Initial stance:
                                        <strong>{{ $breach->formatted_response_stance }}</strong>
                                    </li>
                                    <li>
                                        Time taken to close or patch the breach:
                                        <strong>{{ $breach->formatted_response_patched }}</strong>
                                    </li>
                                    <li>
                                        Time taken to inform cusotmers:
                                        <strong>{{ $breach->formatted_response_customers_informed }}</strong>
                                    </li>
                                </ul>
                                <h3>More Information</h3>
                                <ul>
                                    @if ($breach->source_url)
                                        <li>
                                            <a href="{{ $breach->source_url }}">{{ $breach->source_name }} (source)</a>
                                        </li>
                                    @endif
                                    @if ($breach->more_url)
                                        <li><a href="{{ $breach->more_url }}">More Information</a></li>
                                    @endif
                                </ul>
                            @else
                                <a href="{{ action('BreachController@get', [$breach->organisation->slug, $breach->id]) }}">
                                    More Information
                                </a>
                            @endif

                        </div>
                    </div>
                </div>
            </li>

        @endforeach

    </ul>

@endif