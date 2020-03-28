<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserToken;
use App\Notifications\RegistrationEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testUserRegistration()
    {
        $attributes = ['email' => $this->faker->safeEmail()];

        $this->post(route('register'), $attributes)
            ->assertRedirect('login');

        $user = User::whereEmail($attributes['email'])
            ->first();

        Notification::fake();
        $user->notify(new RegistrationEmail($user));

        // Assert a notification was sent to the given users...
        Notification::assertSentTo(
            [$user], RegistrationEmail::class
        );

        $this->assertDatabaseHas('users', $attributes);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee(__('form.user_registered_successfully'));
    }

    public function testUniqueValidation()
    {
        $user = factory('App\Models\User')->create();
        $this->post(route('register'), ['email' => $user->email])
            ->assertSessionHasErrors('email');
    }

    /**
     * @dataProvider invalidEmailProvider
     */
    public function testInvalidEmailRegistration($email)
    {
        $this->post(route('register'), ['email' => $email])
            ->assertSessionHasErrors(['email']);
    }

    public function invalidEmailProvider()
    {
        return [
            [null],
            ['some-word'],
            ['some text for validation'],
            [912321312],
            [Str::random(256)],
        ];
    }

    public function testUserEmailVerification()
    {
        $this->get(route('home'))
            ->assertRedirect('login');

        $user = factory('App\Models\User')->create();

        $this->get(route('link.login', [$user->getMagicLoginToken(new UserToken)]))
            ->assertRedirect('home');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(__('form.logged_in_successfully'));
    }
}
