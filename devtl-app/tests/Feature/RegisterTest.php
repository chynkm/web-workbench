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
        $this->assertDatabaseHas('schemas', [
            'name' => config('env.first_schema_name'),
        ]);
        $this->assertDatabaseHas('schema_user', [
            'user_id' => $user->id,
            'owner' => 1,
        ]);
        $this->assertDatabaseHas('schema_tables', [
            'user_id' => $user->id,
            'name' => config('env.first_table_name'),
            'engine' => config('env.first_table_engine'),
            'collation' => config('env.first_table_collation'),
        ]);
        $this->assertDatabaseHas('schema_table_columns', [
            'user_id' => $user->id,
            'name' => config('env.first_column_name'),
            'datatype' => config('env.first_column_datatype'),
            'length' => config('env.first_column_length'),
            'primary_key' => config('env.first_column_primary_key'),
            'auto_increment' => config('env.first_column_auto_increment'),
            'unsigned' => config('env.first_column_unsigned'),
            'nullable' => config('env.first_column_nullable'),
            'order' => config('env.first_column_order'),
        ]);

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

        $this->get(route('link.login', [$user->getLinkLoginToken(new UserToken)]))
            ->assertRedirect(route('home'));

        $this->get(route('home'))
            ->assertOk();
    }
}
