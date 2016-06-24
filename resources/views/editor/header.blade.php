<ul class="nav nav-tabs gap-bottom">
    <li role="presentation" class="{{ ifActiveRoute('editor') }}"><a href="{{ action('Editor\DashboardController@dashboard') }}">Dashboard</a></li>
    <li role="presentation" class="{{ ifActiveRoute('editor/tags*') }}"><a href="{{ action('Editor\TagsController@list') }}">Tags</a></li>
    <li role="presentation" class="{{ ifActiveRoute('editor/organisations*') }}"><a href="{{ action('Editor\OrganisationController@list') }}">Organisations</a></li>
    <li role="presentation" class="{{ ifActiveRoute('editor/breaches*') }}"><a href="{{ action('Editor\BreachController@list') }}">Breaches</a></li>
</ul>