<?php

namespace AppTests\Http\Controllers;

use App\Models\Breach;
use App\Models\Organisation;
use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BrowseControllerTest extends \TestCase
{
    use DatabaseMigrations;

    /**
     * Do tags show up on the browse view.
     *
     * @return void
     */
    public function testBrowseListTags()
    {
        factory(Tag::class)->create(['name' => 'TestTag']);

        $this
            ->visit('browse')
            ->see('TestTag');
    }

    /**
     * Does the browse view have tags.
     *
     * @return void
     */
    public function testBrowseViewTags()
    {
        $this
            ->visit('browse')
            ->assertViewHas('tags');
    }

    /**
     * Does a browse tag view return a page.
     *
     * @return void
     */
    public function testValidBrowseTag()
    {
        factory(Tag::class)->create(['id' => 'testtag']);

        $this
            ->visit('browse/tag/testtag')
            ->assertResponseOk();
    }

    /**
     * Does the tag and organisation information get to the view.
     *
     * @return void
     */
    public function testBrowseTagView()
    {
        factory(Tag::class)->create(['id' => 'testtag']);

        $this
            ->visit('browse/tag/testtag')
            ->assertViewHas('tag');

        $this
            ->visit('browse/tag/testtag')
            ->assertViewHas('organisations');
    }


    /**
     * Does organisation show in browse tag.
     *
     * @return void
     */
    public function testBrowseTagOrganisation()
    {
        factory(Tag::class)->create(['id' => 'testtag'])->each(function (Tag $record) {
            $record->organisations()->saveMany([factory(Organisation::class)->make([
                'status' => 'Accepted', 'styled_name' => 'Test Org'
            ])]);
            $record->save();
        });

        $this
            ->visit('browse/tag/testtag')
            ->see('Test Org');
    }

    /**
     * Do breaches show up on the recent breaches page.
     *
     * @return void
     */
    public function testRecentBreaches()
    {
        factory(Breach::class)
            ->create(['status' => 'Accepted', 'summary' => 'Testing Summary.'])
            ->each(function (Breach $record) {
                $record->organisation()->associate(factory(Organisation::class)->create());
                $record->save();
            });

        $this
            ->visit('recent')
            ->see('Testing Summary.');
    }

    /**
     * Does the recent view have a list of breaches
     *
     * @return void
     */
    public function testRecentView()
    {
        $this
            ->visit('recent')
            ->assertViewHas('breaches');
    }
}
