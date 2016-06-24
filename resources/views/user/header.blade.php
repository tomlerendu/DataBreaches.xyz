<ul class="nav nav-tabs gap-bottom">
    <li role="presentation" class="{{ ifActiveRoute('user') }}"><a href="{{ action('User\DashboardController@dashboard') }}">Dashboard</a></li>
    <li role="presentation" class="{{ ifActiveRoute('user/organisation*') }}"><a href="{{ action('User\DashboardController@listOrganisations') }}">Organisations</a></li>
    <li role="presentation" class="{{ ifActiveRoute('user/breach*') }}"><a href="{{ action('User\DashboardController@listBreaches') }}">Breaches</a></li>
</ul>