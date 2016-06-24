<?php

namespace AppTests\Http\Controllers;

use App\Models\Organisation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomeControllerTest extends \TestCase
{
    use DatabaseMigrations;

    /**
     * Does the homepage load
     *
     * @return void
     */
    public function testLoad()
    {
        $this
            ->visit('/')
            ->assertResponseOk();
    }

    /**
     * Does the browse button work.
     *
     * @return void
     */
    public function testBrowseButton()
    {
        $this
            ->visit('/')
            ->click('Browse')
            ->seePageIs('/browse');
    }

    /**
     * Does the search form work.
     *
     * @return void
     */

    public function testSearchForm()
    {
        $this
            ->visit('/')
            ->type('keyword', 'q')
            ->press('Search')
            ->seePageIs('/search?q=keyword')
            ->see('Searching for "keyword"');
    }

    /**
     * Do the search results work.
     *
     * @return void
     */
    public function testInvalidSearchResults()
    {
        $this
            ->visit('/search?q=NotInDatabase')
            ->see('No results found')
            ->assertResponseOk();
    }

    /**
     * Do the search results work.
     *
     * @return void
     */
    public function testSearchResults()
    {
        factory(Organisation::class)->create([
            'status' => 'Accepted',
            'name' => 'Name',
            'styled_name' => 'StyledName',
            'slug' => 'Slug'
        ]);

        //Full exact queries

        $this
            ->visit('search?q=Name')
            ->dontSee('No results found')
            ->assertResponseOk();

        $this
            ->visit('search?q=StyledName')
            ->dontSee('No results found')
            ->assertResponseOk();

        $this
            ->visit('search?q=Slug')
            ->dontSee('No results found')
            ->assertResponseOk();


        //Partial queries

        $this
            ->visit('search?q=nam')
            ->dontSee('No results found')
            ->assertResponseOk();

        $this
            ->visit('search?q=slu')
            ->dontSee('No results found')
            ->assertResponseOk();

        $this
            ->visit('search?q=styled')
            ->dontSee('No results found')
            ->assertResponseOk();
    }

    /**
     * Are breaches passed to the view.
     *
     * @return void
     */
    public function testBreaches()
    {
        $this
            ->visit('/')
            ->assertViewHas('breaches');
    }

    /**
     * Does the view all button work.
     *
     * @return void
     */

    public function testViewAllButton()
    {
        $this
            ->visit('/')
            ->click('View All')
            ->seePageIs('/recent');
    }
}
