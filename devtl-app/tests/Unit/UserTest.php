<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testAUserHasUserTokens()
    {
        $user = factory(\App\Models\User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->userTokens);
    }
}
