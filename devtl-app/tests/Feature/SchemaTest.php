<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class SchemaTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotViewSchemaList()
    {
        $this->get(route('schemas.index'))
            ->assertRedirect('login');
    }

    public function testGuestCannotCreateSchema()
    {
        $attributes = factory('App\Models\Schema')->raw();

        $this->post(route('schemas.store'), $attributes)
            ->assertRedirect('login');
    }

    public function testGuestCannotViewSchema()
    {
        $schema = factory('App\Models\Schema')->create();

        $this->get(route('schemas.show', ['schema' => $schema->id]))
            ->assertRedirect('login');
    }

    public function testUserCanCreateSchema()
    {
        $this->signIn();
        $attributes = factory('App\Models\Schema')->raw();

        $this->post(route('schemas.store'), $attributes)
            ->assertJsonStructure(['url']);

        $this->assertDatabaseHas('schemas', ['name' => $attributes['name']]);
    }

    /**
     * @dataProvider invalidSchemaNameProvider
     */
    public function testInvalidSchemaNames($input)
    {
        $this->signIn();

        $this->post(route('schemas.store'), ['name' => $input])
            ->assertSessionHasErrors('name');
    }

    public function invalidSchemaNameProvider()
    {
        return [
            [''],
            [null],
            [Str::random(1)],
            [Str::random(101)],
        ];
    }

    public function testUserCanViewSchemasListing()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()->schemas()
            ->sync([$schema->id => ['owner' => true]]);

        $this->get(route('schemas.index'))
            ->assertSee($schema->name);
    }

    public function testAUserCannotViewOthersSchema()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();

        $this->get(route('schemas.show', ['schema' => $schema->id]))
            ->assertRedirect(route('schemas.index'));
    }

    public function testAUserCanViewHisSchema()
    {
        $this->signIn();
        $schema = factory('App\Models\Schema')->create();
        Auth::user()->schemas()
            ->sync([$schema->id => ['owner' => true]]);

        $this->get(route('schemas.show', ['schema' => $schema->id]))
            ->assertOk()
            ->assertSeeText($schema->name);
    }

}
