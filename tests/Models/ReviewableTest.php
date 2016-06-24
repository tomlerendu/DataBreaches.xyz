<?php

namespace AppTests\Models;

use App\Models\Reviewable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;

class ReviewableMock extends \Illuminate\Database\Eloquent\Model
{
    use Reviewable;
}

class ReviewableTest extends \TestCase
{
    use DatabaseTransactions;

    /**
     * Creates a table to test ReviewableMock.
     */
    private function createMockTable()
    {
        Schema::create('reviewable_mocks', function (Blueprint $table) {

            $table->increments('id');
            $table->enum('status', [
                'Submitted',
                'RejectedInfo',
                'RejectedDuplicate',
                'RejectedSource',
                'Accepted',
                'Superseded'
            ])->default('Submitted')->nullable()->default(null);
            $table->integer('user_id')->nullable()->default(null);
            $table->integer('previous_id')->nullable()->default(null);
            $table->timestamps();

        });
    }

    /**
     * Test the status description of a record.
     *
     * @return void
     */
    public function testReviewableReadableStatus()
    {
        $reviewable = new ReviewableMock();
        $reviewable->status = 'Submitted';
        $this->assertEquals($reviewable->getReadableStatus(), 'Pending Approval');
    }

    /**
     * Test if previous revisions work correctly.
     *
     * @return void
     */
    public function testReviewablePreviousRevisions()
    {
        $this->createMockTable();

        $reviewable1 = new ReviewableMock();
        $reviewable1->id = 1;
        $reviewable1->previous_id = null;
        $reviewable1->save();

        $reviewable2 = new ReviewableMock();
        $reviewable2->id = 2;
        $reviewable2->previous_id = 1;
        $reviewable2->save();

        $reviewable3 = new ReviewableMock();
        $reviewable3->id = 3;
        $reviewable3->previous_id = 2;
        $reviewable3->save();

        $this->assertEquals(count($reviewable1->getPreviousRevisions()), 0);
        $this->arrayHasKey($reviewable2->getPreviousRevisions(), [1]);
        $this->arrayHasKey($reviewable2->getPreviousRevisions(), [1, 2]);
    }

    /**
     * Test reviewable permissions.
     *
     * @return void
     */
    public function testReviewablePermissions()
    {
        $reviewable = new ReviewableMock();

        $reviewable->user_id = 1;
        $reviewable->status = 'Accepted';
        $this->assertFalse($reviewable->canDelete(1));
        $this->assertFalse($reviewable->canEdit(1));
        $this->assertTrue($reviewable->canSupersede(1));

        $reviewable->user_id = 2;
        $reviewable->status = 'Superseded';
        $this->assertFalse($reviewable->canDelete(1));
        $this->assertFalse($reviewable->canEdit(1));
        $this->assertFalse($reviewable->canSupersede(1));

        $reviewable->user_id = 1;
        $reviewable->status = 'Submitted';
        $this->assertTrue($reviewable->canDelete(1));
        $this->assertTrue($reviewable->canEdit(1));
        $this->assertFalse($reviewable->canSupersede(1));
    }

    /**
     * Test the reviewable status scope.
     *
     * @return void
     */
    public function testReviewableStatusScope()
    {
        $this->createMockTable();

        $reviewable1 = new ReviewableMock();
        $reviewable1->status = 'Accepted';
        $reviewable1->save();

        $reviewable2 = new ReviewableMock();
        $reviewable2->status = 'RejectedInfo';
        $reviewable2->save();

        $reviewable3 = new ReviewableMock();
        $reviewable3->status = 'Submitted';
        $reviewable3->save();

        $this->assertEquals(ReviewableMock::status(['Accepted'])->count(), 1);
        $this->assertEquals(ReviewableMock::status(['RejectedMeta'])->count(), 0);
        $this->assertEquals(ReviewableMock::status(['Accepted', 'RejectedInfo'])->count(), 2);
        $this->assertEquals(ReviewableMock::status(['Accepted', 'Submitted', 'RejectedInfo'])->count(), 3);
        $this->assertEquals(ReviewableMock::status(['Accepted', 'RejectedMeta'])->count(), 1);
    }
}
