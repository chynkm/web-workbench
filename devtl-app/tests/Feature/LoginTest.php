<?php

namespace Tests\Feature;

use App\Notifications\LinkLoginEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testValidUserLogin()
    {
        $user = factory('App\Models\User')->create();

        $this->post(route('link.login'), ['email' => $user->email])
            ->assertRedirect(route('login'));

        Notification::fake();
        $user->notify(new LinkLoginEmail($user));

        // Assert a notification was sent to the given users...
        Notification::assertSentTo(
            [$user], LinkLoginEmail::class
        );

        $this->get(route('login'))
            ->assertOk()
            ->assertSee(__('form.login_email_sent'));
    }

    public function testInValidUserLogin()
    {
        $this->post(route('link.login'), ['email' => ''])
            ->assertSessionHasErrors(['email']);
    }

    public function testUnRegisteredUserLogin()
    {
        $this->post(route('link.login'), ['email' => $this->faker->safeEmail()])
            ->assertRedirect(route('login'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee(__('form.user_not_found'));
    }

}
