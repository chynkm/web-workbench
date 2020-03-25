<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get encrypted user-id
     *
     * @return void
     */
    public function testgetEncryptedEmailToken()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->assertEquals($user->id.'|'.$user->email, decrypt($user->getEncryptedEmailToken()));
    }

    public function testAUserHasUserTokens()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->userTokens);
    }
}
