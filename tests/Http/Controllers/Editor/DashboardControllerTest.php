<?php

namespace AppTests\Http\Controllers\Editor;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class DashboardControllerTest extends \TestCase
{
    use DatabaseMigrations;

    /**
     * Test editor dashboard recalculate organisations score command.
     *
     * @return void
     */
    public function testEditorRecalculateOrganisations()
    {
        $this
            ->withoutMiddleware()
            ->visit('/editor')
            ->press('Recalculate organisation scores')
            ->assertResponseOk();
    }

    /**
     * Test editor dashboard recalculate tag organisation counts.
     *
     * @return void
     */
    public function testEditorRecalculateTags()
    {
        $this
            ->withoutMiddleware()
            ->visit('/editor')
            ->press('Recalculate tag organisation counts')
            ->assertResponseOk();
    }
}
