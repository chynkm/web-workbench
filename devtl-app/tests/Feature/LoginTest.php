<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    // if the user verification isn't updated,
    // update it when the user first logs in
    public function testValidUserLoggedIn()
    {
        $user = factory('App\Models\User')->create();

        $this->post(route('login'), ['email' => $user->email])
            ->assertRedirect('login');

        Notification::fake();
        $user->notify(new LoginEmail($user));

        // Assert a notification was sent to the given users...
        Notification::assertSentTo(
            [$user], LoginEmail::class
        );

        $this->get(route('login'))
            ->assertOk()
            ->assertSee(__('form.login_email_sent'));
    }
}
