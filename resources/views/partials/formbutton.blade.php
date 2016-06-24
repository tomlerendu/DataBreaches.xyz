
<form method="{{ $buttonMethod or 'get' }}" class="inline-form" action="{{ $buttonAction }}">

    @if (isset($buttonMethod) && strtoupper($buttonMethod) === 'POST')
        {{ csrf_field() }}
    @endif

    @if (isset($buttonParams))
        @foreach ($buttonParams as $paramKey => $param)
            <input type="hidden" name="{{ $paramKey }}" value="{{ $param }}">
        @endforeach
    @endif

    <input type="submit" class="btn btn-default {{ $buttonCSS or '' }}" value="{{ $buttonTitle }}">

</form>