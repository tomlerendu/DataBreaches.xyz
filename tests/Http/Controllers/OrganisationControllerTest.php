<?php

namespace AppTests\Http\Controllers;

use App\Models\Organisation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OrganisationControllerTest extends \TestCase
{
    use DatabaseMigrations;

    /**
     * Does an invalid organisation trigger a 404.
     *
     * @return void
     */
    public function testInvalidOrganisation()
    {
        $this
            ->get(url('/organisation/notreal'))
            ->assertResponseStatus(404);
    }

    /**
     * Does an non-approved organisation trigger a 404.
     *
     * @return void
     */
    public function testUnapprovedOrganisation()
    {
        factory(Organisation::class)->create(['status' => 'Submitted', 'slug' => 'real']);

        $this
            ->get(url('/organisation/real'))
            ->assertResponseStatus(404);
    }

    /**
     * Does a valid organisation trigger a 200.
     *
     * @return void
     */
    public function testValidOrganisation()
    {
        factory(Organisation::class)->create(['status' => 'Accepted', 'slug' => 'real']);

        $this
            ->visit('/organisation/real')
            ->assertResponseOk();
    }

    /**
     * Is all data passed to the view.
     *
     * @return void
     */
    public function testViewData()
    {
        factory(Organisation::class)->create(['status' => 'Accepted', 'slug' => 'real']);

        $this
            ->visit('organisation/real')
            ->assertViewHas('breaches');

        $this
            ->visit('organisation/real')
            ->assertViewHas('organisation');
    }
}
