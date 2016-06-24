<?php

namespace AppTests\Models;

use App\Models\Breach;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BreachTest extends \TestCase
{
    use DatabaseMigrations;

    /**
     * Test the breaches method title.
     *
     * @return void
     */
    public function testBreachMethodTitle()
    {
        $breach = new Breach();

        $breach->method = 'InvalidMethod';
        $this->assertEquals($breach->method_title, 'Unknown');
        $breach->method = 'Hack';
        $this->assertEquals($breach->method_title, 'Hacking');
    }

    /**
     * Test the breaches method description.
     *
     * @return void
     */
    public function testBreachMethodDescription()
    {
        $breach = new Breach();

        $breach->method = 'InvalidMethod';
        $this->assertEquals($breach->method_description, 'Unknown');
        $breach->method = 'Hack';
        $this->assertEquals(
            $breach->method_description,
            'A technical vulnerability such as SQL injection or remote code execution.'
        );
    }

    /**
     * Test organisation stance.
     *
     * @return void
     */
    public function testBreachOrganisationStance()
    {
        $breach = new Breach();

        $breach->response_stance = 'InvalidStance';
        $this->assertEquals($breach->formatted_response_stance, 'Unknown');
        $breach->response_stance = 'Denied';
        $this->assertEquals($breach->formatted_response_stance, 'Organisation denied breach');
    }

    /**
     * Test breach patch time.
     *
     * @return void
     */
    public function testBreachPatchTime()
    {
        $breach = new Breach();

        $breach->response_patched = 'InvalidPatchTime';
        $this->assertEquals($breach->formatted_response_patched, 'Unknown');
        $breach->response_patched = 'Patched2';
        $this->assertEquals($breach->formatted_response_patched, 'Patched within 48 hours');
    }

    /**
     * Test breach patch time.
     *
     * @return void
     */
    public function testBreachCustomersInformedTime()
    {
        $breach = new Breach();

        $breach->response_customers_informed = 'InvalidInformedTime';
        $this->assertEquals($breach->formatted_response_customers_informed, 'Unknown');
        $breach->response_customers_informed = 'Informed1';
        $this->assertEquals($breach->formatted_response_customers_informed, 'Informed within 24 hours');
    }

    /**
     * Test the data leaked by the breach.
     *
     * @return void
     */
    public function testBreachDataLeaked()
    {
        $breach = new Breach();

        $dataLeaked = ['bank.card.pin', 'gov.ni', 'account.email'];
        $formattedRealLeaked = ['Credit/Debit Card PIN', 'National Insurance Number', 'Email Address'];
        $breach->data_leaked = $dataLeaked;
        $this->assertEquals($breach->data_leaked, $dataLeaked);
        $this->assertEquals($breach->formatted_data_leaked, $formattedRealLeaked);
    }

    /**
     * Test recent query scope.
     *
     * @return void
     */
    public function testRecentScope()
    {
        factory(Breach::class)->times(4)->create(['status' => 'Submitted']);
        factory(Breach::class)->create(['status' => 'Accepted', 'date_occurred' => \Carbon\Carbon::today()]);
        factory(Breach::class)->create(['status' => 'Accepted', 'date_occurred' => \Carbon\Carbon::yesterday()]);
        factory(Breach::class)->create(['status' => 'Accepted', 'date_occurred' => \Carbon\Carbon::tomorrow()]);

        $this->assertEquals(Breach::recent(10)->get()->count(), 3);
        $this->assertEquals(Breach::recent(2)->get()->count(), 2);

        $recent = Breach::recent()->get();

        $this->assertEquals($recent[0]->date_occurred, \Carbon\Carbon::tomorrow());
        $this->assertEquals($recent[1]->date_occurred, \Carbon\Carbon::today());
        $this->assertEquals($recent[2]->date_occurred, \Carbon\Carbon::yesterday());
    }
}
