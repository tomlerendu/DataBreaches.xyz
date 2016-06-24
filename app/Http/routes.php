<?php


/**
 * Homepage & search
 */

Route::get('/', ['uses' => 'HomeController@view']);
Route::get('/search', ['uses' => 'HomeController@search']);

/**
 * Browse & recent
 */

Route::get('/browse', ['uses' => 'BrowseController@browse']);
Route::get('/browse/tag/{tag}', ['uses' => 'BrowseController@browseTag']);
Route::get('/recent', ['uses' => 'BrowseController@recent']);

/**
 * Breaches
 */

Route::get('/organisation/{organisationSlug}', ['uses' => 'OrganisationController@view']);
Route::get('/organisation/{organisationSlug}/breach/{breachId}', ['uses' => 'BreachController@get']);

/**
 * Account
 */

Route::get('/account', ['uses' => 'AccountController@view']);
Route::post('/account/password', ['uses' => 'AccountController@changePassword']);

/**
 * User dashboard
 */

Route::get('/user', ['uses' => 'User\DashboardController@dashboard']);

Route::get('/user/organisation', ['uses' => 'User\DashboardController@listOrganisations']);
Route::get('/user/organisation/{organisationId}', ['uses' => 'User\DashboardController@getOrganisation'])->where('organisationId', '[0-9]*');
Route::get('/user/organisation/submit', ['uses' => 'User\SubmissionController@submitOrganisation']);
Route::get('/user/organisation/{organisationId}/submit', ['uses' => 'User\SubmissionController@editOrganisation']);
Route::post('/user/organisation/submit', ['uses' => 'User\SubmissionController@processOrganisation']);
Route::post('/user/organisation/{organisationId}/delete', ['uses' => 'User\SubmissionController@deleteOrganisation']);

Route::get('/user/breach', ['uses' => 'User\DashboardController@listBreaches']);
Route::get('/user/breach/{breachId}', ['uses' => 'User\DashboardController@getBreach'])->where('breachId', '[0-9]*');
Route::get('/user/breach/{organisationId}/submit', ['uses' => 'User\SubmissionController@submitBreach']);
Route::get('/user/breach/{organisationId}/{breachId}/submit', ['uses' => 'User\SubmissionController@editBreach']);
Route::post('/user/breach/{organisationId}/submit', ['uses' => 'User\SubmissionController@processBreach']);
Route::post('/user/breach/{organisationId}/{breachId}/delete', ['uses' => 'User\SubmissionController@deleteBreach']);

/**
 * Editor dashboard
 */
Route::get('/editor', ['uses' => 'Editor\DashboardController@dashboard']);
Route::post('/editor/command/{command}', ['uses' => 'Editor\DashboardController@command']);

Route::get('/editor/tags', ['uses' => 'Editor\TagsController@list']);
Route::post('/editor/tags/submit', ['uses' => 'Editor\TagsController@submit']);
Route::post('/editor/tags/{tagId}/delete', ['uses' => 'Editor\TagsController@delete']);

Route::get('/editor/organisations', ['uses' => 'Editor\OrganisationController@list']);
Route::get('/editor/organisations/{organisationId}', ['uses' => 'Editor\OrganisationController@view']);
Route::post('/editor/organisations/{organisationId}/status', ['uses' => 'Editor\OrganisationController@status']);

Route::get('/editor/breaches', ['uses' => 'Editor\BreachController@list']);
Route::get('/editor/breaches/{breachId}', ['uses' => 'Editor\BreachController@view']);
Route::post('/editor/breaches/{breachId}/status', ['uses' => 'Editor\BreachController@status']);

/**
 * User authentication
 */
Route::auth();
