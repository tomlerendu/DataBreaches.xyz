<div class="container-fluid company company-{{ $organisation->score_grade }}">
    <div class="row">
        <h1 class="col-xs-12 company-name">
            <a href="{{ action('OrganisationController@view', $organisation->slug) }}">
                {{ $organisation->styled_name }}
            </a>
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <ul class="company-tags">
                @foreach($organisation->tags as $tag)
                    <li><a href="{{ action('BrowseController@browseTag', $tag->id) }}">{{ $tag->name }}</a></li>
                @endforeach
            </ul>
            <ul class="company-facts">
                <li>Incorporated on {{ $organisation->incorporated_on->format(config('date.human')) }}</li>
                <li>{{ $organisation->breach_count }} recorded breach{{ $organisation->breach_count != 1 ? 'es' : '' }}</li>
                <li>Trading name of {{ $organisation->name }}</li>
            </ul>

            <a
                href="{{ action('User\SubmissionController@submitBreach', $organisation->id) }}"
                class="btn btn-default btn-sm">
                    Submit Breach
            </a>
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="company-score">
                <div>{{ round($organisation->score, 2) }}/10</div>
                <div class="company-score-text">{{ $organisation->formatted_score_grade }}</div>
            </div>
        </div>
    </div>
</div>