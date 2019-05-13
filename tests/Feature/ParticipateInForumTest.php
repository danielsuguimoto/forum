<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\AuthenticationException;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function unauthenticated_user_cannot_participate_in_forum_threads()
    {
        $this->withoutExceptionHandling();

        $this->expectException(AuthenticationException::class);
        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();

        $this->post('/threads/' . $thread->id . '/replies', $reply->toArray());
    }

    /**
     * @test
     */
    public function an_user_can_participate_in_forum_threads()
    {
        $user = factory('App\User')->create();

        $this->be($user);

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();

        $this->post('/threads/' . $thread->id . '/replies', $reply->toArray());

        $this->get($thread->path())->assertSee($reply->body);
    }
}
