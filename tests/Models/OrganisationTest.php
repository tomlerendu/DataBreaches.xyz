<?php

namespace AppTests\Models;

use App\Models\Breach;
use App\Models\Organisation;

class OrganisationTest extends \TestCase
{
    /**
     * Is the number of breaches calculated correctly.
     *
     * @return void
     */
    public function testOrganisationBreachCount()
    {
        $organisation = factory(Organisation::class)->create(['status' => 'Accepted']);

        $organisation->breaches()->saveMany(factory(Breach::class)->times(3)->create(['status' => 'Accepted']));
        $organisation->breaches()->saveMany(factory(Breach::class)->times(5)->create(['status' => 'RejectedMeta']));
        $organisation->breaches()->saveMany(factory(Breach::class)->times(2)->create(['status' => 'RejectedSource']));
        $organisation->breaches()->saveMany(factory(Breach::class)->times(3)->create(['status' => 'Superseded']));
        $organisation->breaches()->saveMany(factory(Breach::class)->times(4)->create(['status' => 'Submitted']));

        $organisation->save();
        $organisation->calculateBreaches();

        $this->assertEquals($organisation->breach_count, 3);
    }

    /**
     * Are all tag IDs for an organisation correct.
     *
     * @return void
     */
    public function testOrganisationTagIds()
    {
        $organisation = factory(Organisation::class)->create();
        $tags = [
            factory(\App\Models\Tag::class)->create(['id' => 'a']),
            factory(\App\Models\Tag::class)->create(['id' => 'b']),
            factory(\App\Models\Tag::class)->create(['id' => 'c'])
        ];
        $organisation->tags()->saveMany($tags);
        $organisation->save();

        $this->assertEquals($organisation->getTagIds(), ['a', 'b', 'c']);
    }

    /**
     * Is the grade for an organisations score calculated correctly.
     *
     * @return void
     */
    public function testOrganisationScoreGrade()
    {
        $organisation = new Organisation();

        $organisation->score = 9.5;
        $this->assertEquals($organisation->score_grade, 'excellent');
        $this->assertEquals($organisation->formatted_score_grade, 'Excellent');

        $organisation->score = 8.5;
        $this->assertEquals($organisation->score_grade, 'good');
        $this->assertEquals($organisation->formatted_score_grade, 'Good');

        $organisation->score = 6.5;
        $this->assertEquals($organisation->score_grade, 'variable');
        $this->assertEquals($organisation->formatted_score_grade, 'Variable');

        $organisation->score = 4.5;
        $this->assertEquals($organisation->score_grade, 'poor');
        $this->assertEquals($organisation->formatted_score_grade, 'Poor');
    }

    /**
     * Search query scope test.
     *
     * @return void
     */
    public function testOrganisationSearchScope()
    {
        factory(Organisation::class)->create([
            'slug' => 'testing',
            'name' => 'Testing LTD',
            'styled_name' => 'Testing',
            'status' => 'Accepted'
        ]);

        factory(Organisation::class)->create([
            'slug' => 'submitted',
            'name' => 'Submitted PLC',
            'styled_name' => 'Submitted PLC',
            'status' => 'Submitted'
        ]);

        $this->assertEquals(Organisation::search('test')->count(), 1);
        $this->assertEquals(Organisation::search('testing')->count(), 1);
        $this->assertEquals(Organisation::search('testing ltd')->count(), 1);
        $this->assertEquals(Organisation::search('submitted')->count(), 0);
        $this->assertEquals(Organisation::search('submitted plc')->count(), 0);
        $this->assertEquals(Organisation::search('submitted', true)->count(), 1);
        $this->assertEquals(Organisation::search('submitted plc', true)->count(), 1);
    }

    /**
     * Slug query scope test.
     *
     * @return void
     */
    public function testOrganisationSlugScope()
    {
        factory(Organisation::class)->create(['slug' => 'testing', 'status' => 'Accepted']);
        factory(Organisation::class)->create(['slug' => 'testing2', 'status' => 'Superseded']);
        factory(Organisation::class)->create(['slug' => 'pending', 'status' => 'Submitted']);

        $this->assertEquals(Organisation::slug('testing')->count(), 1);
        $this->assertEquals(Organisation::slug('testing', ['Accepted'])->count(), 1);
        $this->assertEquals(Organisation::slug('testing', ['Accepted', 'RejectedInfo'])->count(), 1);
        $this->assertEquals(Organisation::slug('pending', ['Submitted'])->count(), 1);
        $this->assertEquals(Organisation::slug('testing2', ['Superseded'])->count(), 1);
    }
}
