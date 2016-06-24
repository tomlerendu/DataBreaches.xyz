<?php

namespace AppTests\Models;

use App\Models\User;
use App\Models\UserRankEnum;

class UserTest extends \TestCase
{
    /**
     * Do user rank attributes work correctly.
     *
     * @return void
     */
    public function testUserRank()
    {
        $user = new User();

        $user->rank = UserRankEnum::User;
        $this->assertEquals($user->rank, UserRankEnum::User);
        $this->assertTrue($user->isRank(UserRankEnum::User));
        $this->assertTrue($user->isMinimumRank(UserRankEnum::User));
        $this->assertFalse($user->isMinimumRank(UserRankEnum::Editor));

        $user->rank = UserRankEnum::Editor;
        $this->assertEquals($user->rank, UserRankEnum::Editor);
        $this->assertTrue($user->isRank(UserRankEnum::Editor));
        $this->assertFalse($user->isRank(UserRankEnum::User));
        $this->assertTrue($user->isMinimumRank(UserRankEnum::Editor));
    }

    /**
     * Can a password be set and checked against.
     *
     * @return void
     */
    public function testUserPassword()
    {
        $user = new User();

        $user->changePassword('hunter2');
        $this->assertTrue($user->hasPassword('hunter2'));
        $this->assertFalse($user->hasPassword('HUNTER2'));

        $user->changePassword('hunter3');
        $this->assertFalse($user->hasPassword('hunter2'));
    }

    /**
     * Do long usernames get trimmed.
     *
     * @return void
     */
    public function testUserTrimmedUsername()
    {
        $user = new User();

        $user->username = 'Testing';
        $this->assertEquals($user->trimmed_username, 'Testing');
        $user->username = 'LongerUsername';
        $this->assertEquals($user->trimmed_username, 'LongerUse...');
    }
}
