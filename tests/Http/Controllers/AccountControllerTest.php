<?php

namespace AppTests\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AccountControllerTest extends \TestCase
{
    use DatabaseMigrations;

    /**
     * Test visiting the account information page.
     *
     * @return void
     */
    public function testAccountPageVisit()
    {
        $user = factory(User::class)->create();

        $this
            ->actingAs($user)
            ->visit('/account')
            ->see('Change Password')
            ->see('Email')
            ->see('Username')
            ->assertResponseOk();
    }

    /**
     * Test changing password on the account page.
     *
     * @return void
     */
    public function testAccountPasswordChange()
    {
        $user = factory(User::class)->create();
        $user->changePassword('hunter2');

        $this
            ->actingAs($user)
            ->visit('/account')
            ->type('hunter2', 'current_password')
            ->type('hunter3', 'new_password')
            ->type('hunter3', 'new_password_confirmation')
            ->press('Change Password')
            ->see('Your password was updated.');

        $this
            ->actingAs($user)
            ->visit('/account')
            ->type('hunter2', 'current_password')
            ->type('hunter5', 'new_password')
            ->type('hunter5', 'new_password_confirmation')
            ->press('Change Password')
            ->dontSee('Your password was updated.');

        $this
            ->actingAs($user)
            ->visit('/account')
            ->type('hunter3', 'current_password')
            ->type('hunter5', 'new_password')
            ->type('hunter6', 'new_password_confirmation')
            ->press('Change Password')
            ->dontSee('Your password was updated.');


        $user = User::first();
        $this->assertTrue($user->hasPassword('hunter3'));
    }
}
