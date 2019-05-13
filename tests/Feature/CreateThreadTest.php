<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function guest_cannot_create_thread()
    {
        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);
        $thread = factory('App\Thread')->make();

        $this->post('/threads',$thread->toArray());

        $this->get($thread->path())->assertSee($thread->title)->assertSee($thread->body);
    }

    /**
     * @test
     */
    public function authenticated_user_can_create_new_thread()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->create();
        $this->actingAs($user);

        $thread = factory('App\Thread')->make();

        $this->post('/threads',$thread->toArray());

        $this->get($thread->path())->assertSee($thread->title)->assertSee($thread->body);
    }
}
