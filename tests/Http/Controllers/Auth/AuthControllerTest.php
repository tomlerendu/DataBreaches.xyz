<?php

namespace AppTests\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthControllerTest extends \TestCase
{
    use DatabaseMigrations;

    /*
     * Helper to create a user
     *
     * @return void
     */
    private function createUserHelper()
    {
        $user = factory(User::class)->create(['username' => 'dave', 'email' => 'dave@gmail.com']);
        $user->changePassword('hunter2');
        $user->save();
    }

    /**
     * Test logging in with email.
     *
     * @return void
     */
    public function testAuthLogin()
    {
        $this->createUserHelper();

        $this
            ->visit('/login')
            ->type('dave', 'username')
            ->type('hunter2', 'password')
            ->press('Login')
            ->seePageIs('/');
    }

    /**
     * Test logging in with a username.
     *
     * @return void
     */
    public function testAuthLoginUsername()
    {
        $this->createUserHelper();

        $this
            ->visit('/login')
            ->type('dave', 'username')
            ->type('hunter2', 'password')
            ->press('Login')
            ->seePageIs('/');
    }

    /**
     * Test logging in with invalid credentials.
     *
     * @return void
     */
    public function testAuthLoginInvalid()
    {
        $this->createUserHelper();

        $this
            ->visit('/login')
            ->type('dave', 'username')
            ->type('wrongpassword', 'password')
            ->press('Login')
            ->seePageIs('/login');
    }
}
