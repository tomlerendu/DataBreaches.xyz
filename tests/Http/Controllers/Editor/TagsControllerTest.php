<?php

namespace AppTests\Http\Controllers\Editor;

use App\Models\Tag;
use App\Models\User;
use App\Models\UserRankEnum;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TagsControllerTest extends \TestCase
{
    use DatabaseMigrations;

    /**
     * Test tags data is available to the view
     *
     * @return void
     */
    public function testTagDataView()
    {
        $user = factory(User::class)->create(['rank' => UserRankEnum::Editor]);

        $this
            ->actingAs($user)
            ->visit('/editor/tags')
            ->assertViewHas('tags');
    }

    /**
     * Test viewing tags.
     *
     * @return void
     */
    public function testTagsView()
    {
        $user = factory(User::class)->create(['rank' => UserRankEnum::Editor]);
        factory(Tag::class)->create(['name' => '1tag1']);
        factory(Tag::class)->create(['name' => '2tag2']);

        $this
            ->actingAs($user)
            ->visit('/editor/tags')
            ->see('1tag1')
            ->see('2tag2');
    }

    /**
     * Test submitting a valid tag.
     *
     * @return void
     */
    public function testTagSubmission()
    {
        $user = factory(User::class)->create(['rank' => UserRankEnum::Editor]);

        $this
            ->actingAs($user)
            ->visit('/editor/tags')
            ->type('newtag', 'tag')
            ->press('Add')
            ->see('newtag')
            ->see('Delete');
    }

    /**
     * Test submitting an invalid tag (tag name already in use).
     *
     * @return void
     */
    public function testInvalidTagSubmission()
    {
        $user = factory(User::class)->create(['rank' => UserRankEnum::Editor]);
        factory(Tag::class)->create(['id' => '1tag1', 'name' => '1tag1']);

        $this
            ->actingAs($user)
            ->visit('/editor/tags')
            ->type('1tag1', 'tag')
            ->press('Add')
            ->see('The tag has already been taken.');
    }

    /**
     * Test deleting a tag.
     *
     * @return void
     */
    public function testTagDeletion()
    {
        $user = factory(User::class)->create(['rank' => UserRankEnum::Editor]);
        factory(Tag::class)->create(['id' => '1tag1', 'name' => '1tag1']);

        $this
            ->actingAs($user)
            ->visit('/editor/tags')
            ->press('Delete')
            ->dontSee('1tag1');
    }
}
