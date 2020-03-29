<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTokenTest extends TestCase
{
    use RefreshDatabase;

    public function testUserTokenBelongsToAUser()
    {
        $userToken = factory('App\Models\UserToken')->create();

        $this->assertInstanceOf('App\Models\User', $userToken->user);
    }
}
