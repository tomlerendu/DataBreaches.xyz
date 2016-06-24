<div class="top-breadcrumbs gap-bottom">
    <div class="btn-group pull-right" role="group">
        @if (isset($breadcrumbButtons))
            @foreach ($breadcrumbButtons as $breadcrumbButton)
                @if (!isset($breadcrumbButton['if']) || $breadcrumbButton['if'])
                    @include('partials.formbutton', [
                        'buttonTitle' => $breadcrumbButton['title'],
                        'buttonAction' => $breadcrumbButton['href'],
                        'buttonParams' => $breadcrumbButton['params'] ?? [],
                        'buttonMethod' => $breadcrumbButton['method'] ?? 'get'
                    ])
                @endif
            @endforeach
        @endif
    </div>
    <ol>
        @if (isset($breadcrumbLinks))
            @foreach ($breadcrumbLinks as $breadcrumbLink)
                @if (isset($breadcrumbLink['href']))
                    <li><a href="{{ $breadcrumbLink['href'] }}">{!! $breadcrumbLink['title'] !!}</a></li>
                @else
                    <li>{{ $breadcrumbLink['title'] }}</li>
                @endif

                @if (end($breadcrumbLinks) !== $breadcrumbLink)
                    <li class="top-breadcrumb">/</li>
                @endif
            @endforeach
        @endif
    </ol>
</div>