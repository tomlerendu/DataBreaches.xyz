<?php

namespace AppTests\Http\Controllers;

use App\Models\Breach;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BreachControllerTest extends \TestCase
{
    use DatabaseMigrations;

    private function validBreachHelper(): string {
        factory(Organisation::class)
            ->create(['status' => 'Accepted', 'slug' => 'org'])
            ->each(function (Organisation $record) {
                $record->breaches()
                    ->save(factory(Breach::class)
                        ->create(['id' => 2, 'status' => 'Accepted', 'summary' => 'abcdefg']));
                $record->save();
            });

        return 'organisation/org/breach/2';
    }

    /**
     * Does an invalid breach return a 404
     *
     * @return void
     */
    public function testInvalidBreach()
    {
        $this->validBreachHelper();

        $this
            ->get(url('organisation/invalidorg/breach/2'))
            ->assertResponseStatus(404);
    }

    /**
     * Does the data get to the view
     *
     * @return void
     */
    public function testBreachViewData()
    {
        $url = $this->validBreachHelper();

        $this
            ->visit($url)
            ->assertViewHas('breaches');

        $this
            ->visit($url)
            ->assertViewHas('organisation');
    }

    /**
     * Does a breach show up.
     *
     * @return void
     */
    public function testBreachViewDisplay()
    {
        $url = $this->validBreachHelper();

        $this
            ->visit($url)
            ->see('abcdefg');
    }
}
