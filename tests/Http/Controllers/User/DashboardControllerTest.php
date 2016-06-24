<?php

namespace AppTests\Http\Controllers\User;

use App\Models\Breach;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DashboardControllerTest extends \TestCase
{
    use DatabaseMigrations;

    /**
     * Test stats on the user dashboard.
     *
     * @return void
     */
    public function testUserDashboardStats()
    {
        $user = factory(User::class)->create(['approved_organisations' => 3, 'approved_breaches' => 4]);

        $this
            ->actingAs($user)
            ->visit('/user')
            ->see('3 approved organisations');

        $this
            ->actingAs($user)
            ->visit('/user')
            ->see('4 approved breaches');
    }

    /**
     * Test user dashboard search form.
     *
     * @return void
     */
    public function testUserDashboardSearchBox()
    {
        $user = factory(User::class)->create();

        $this
            ->actingAs($user)
            ->visit('/user')
            ->type('testing123', 'q')
            ->press('Search')
            ->seePageIs('/user/breach?q=testing123')
            ->see('testing123');
    }

    /**
     * Test visiting the breach list from the user dashboard.
     *
     * @return void
     */
    public function testUserDashboardVistBreaches()
    {
        $user = factory(User::class)->create();
        
        $this
            ->actingAs($user)
            ->visit('/user')
            ->click('Breaches')
            ->seePageIs('/user/breach');
    }

    /**
     * Test visiting the organisation list from the user dashboard.
     *
     * @return void
     */
    public function testUserDashboardVistOrganisations()
    {
        $user = factory(User::class)->create();

        $this
            ->actingAs($user)
            ->visit('/user')
            ->click('Organisations')
            ->seePageIs('/user/organisation');
    }

    /**
     * Test viewing organisations on the user dashboard.
     *
     * @return void
     */
    public function testUserDashboardListOrganisations()
    {
        $user = factory(User::class)->create();
        factory(Organisation::class)->create(['user_id' => $user->id, 'status' => 'Accepted', 'name' => 'Org1']);
        factory(Organisation::class)->create(['user_id' => $user->id, 'status' => 'Submitted', 'name' => 'Org2']);
        factory(Organisation::class)->create(['user_id' => 9999, 'status' => 'Accepted', 'name' => 'HiddenOrg']);

        $this
            ->actingAs($user)
            ->visit('/user/organisation')
            ->see('Org1')
            ->see('Org2')
            ->dontSee('HiddenOrg');
    }

    /**
     * Test viewing breaches on the user dashboard.
     *
     * @return void
     */
    public function testUserDashboardListBreaches()
    {
        $user = factory(User::class)->create();
        $org1 = factory(Organisation::class)->create(['user_id' => $user->id, 'name' => 'Org1']);
        $org2 = factory(Organisation::class)->create(['user_id' => $user->id, 'name' => 'Org2']);
        factory(Breach::class)->create(['user_id' => $user->id, 'organisation_id' => $org1->id]);
        factory(Breach::class)->create(['user_id' => 9999, 'organisation_id' => $org2->id]);

        $this
            ->actingAs($user)
            ->visit('/user/breach')
            ->see('Org1')
            ->dontSee('Org2');
    }
}
