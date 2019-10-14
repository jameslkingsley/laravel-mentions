<?php

namespace Kingsley\Mentions\Test\Unit;

use Illuminate\Support\Facades\App;
use Kingsley\Mentions\Test\TestCase;
use Illuminate\Support\Facades\Route;
use Kingsley\Mentions\Models\Mention;
use Illuminate\Support\Facades\Request;
use Kingsley\Mentions\Test\TestCommentModel;
use Kingsley\Mentions\Collections\MentionCollection;

class MentionsTest extends TestCase
{
    /** @test */
    public function can_mention_single_model()
    {
        $mention = $this->testCommentModel->mention($this->testUserModel->first());

        $this->assertTrue($this->testCommentModel->mentions(false)->contains($mention));
    }

    /** @test */
    public function can_mention_many_models()
    {
        $mentions = $this->testCommentModel->mention($this->testUserModel->all());

        $this->assertInstanceOf(MentionCollection::class, $mentions);
    }

    /** @test */
    public function can_mention_encoded_string()
    {
        $encoded = $this->testUserModel->all()->map(function ($user) {
            return "users:{$user->id}";
        })->implode(',');

        $mentions = $this->testCommentModel->mention($encoded);

        $this->assertInstanceOf(MentionCollection::class, $mentions);
    }

    /** @test */
    public function can_unmention_single_model()
    {
        $result = $this->testCommentModel->unmention($this->testUserModel->first());

        $this->assertInstanceOf(TestCommentModel::class, $result);
    }

    /** @test */
    public function can_unmention_many_models()
    {
        $result = $this->testCommentModel->unmention($this->testUserModel->all());

        $this->assertInstanceOf(TestCommentModel::class, $result);
    }

    /** @test */
    public function can_unmention_encoded_string()
    {
        $encoded = $this->testUserModel->all()->map(function ($user) {
            return "users:{$user->id}";
        })->implode(',');

        $result = $this->testCommentModel->unmention($encoded);

        $this->assertInstanceOf(TestCommentModel::class, $result);
    }

    /** @test */
    public function can_get_mentions_collection_unresolved()
    {
        $mentions = $this->testCommentModel->mentions(false);

        $this->assertInstanceOf(MentionCollection::class, $mentions);
    }

    /** @test */
    public function can_get_mentions_collection_resolved()
    {
        $mentions = $this->testCommentModel->mentions(true);

        $this->assertInstanceOf(MentionCollection::class, $mentions);
    }

    /** @test */
    public function can_notify_new_mention()
    {
        $mention = $this->testCommentModel->mention($this->testUserModel->first());

        $mention->notify();

        $this->assertInstanceOf(Mention::class, $mention);
    }

    /** @test */
    public function can_notify_new_mentions()
    {
        $mentions = $this->testCommentModel->mention($this->testUserModel->all());

        $mentions->notify();

        $this->assertInstanceOf(MentionCollection::class, $mentions);
    }

    /** @test */
    public function can_get_recipient()
    {
        $mention = $this->testCommentModel->mention($this->testUserModel->first());
        $recipient = $mention->recipient();

        $this->assertInstanceOf(get_class($this->testUserModel), $recipient);
    }

    /** @test */
    public function can_get_collection_encoded()
    {
        $this->testCommentModel->mention($this->testUserModel->all());
        $encoded = $this->testCommentModel->mentions()->encoded();

        $this->assertInternalType('string', $encoded);
    }

    /** @test */
    public function can_clear_mentions_off_of_model()
    {
        $mention = $this->testCommentModel->mention($this->testUserModel->first());

        $this->assertTrue($this->testCommentModel->mentions(false)->contains($mention));

        $this->testCommentModel->clearMentions();

        $this->assertTrue($this->testCommentModel->mentions()->isEmpty());
    }

    /** @test */
    public function can_get_mentions_from_route()
    {
        $request = Request::create('/api/mentions/?p=users&q=Ke', 'GET');
        $response = App::handle($request);
        $data = json_decode($response->getContent());

        $this->assertInternalType('array', $data);
        $this->assertTrue(sizeof($data) === 2);
    }

    /** @test */
    public function can_get_mentions_from_route_with_custom_resource()
    {
        $this->app['config']->set(
            'mentions.pools.users.resource',
            'Kingsley\Mentions\Test\TestUserCollection'
        );

        $request = Request::create('/api/mentions/?p=users&q=Ke', 'GET');
        $response = App::handle($request);
        $data = json_decode($response->getContent());

        $this->assertInternalType('object', $data);
        $this->assertTrue(isset($data->meta) && $data->meta === 'test');
    }

    /** @test */
    public function can_use_filter_in_pool()
    {
        $this->app['config']->set(
            'mentions.pools.users.filter',
            'Kingsley\Mentions\Test\TestUserFilter'
        );

        $request = Request::create('/api/mentions/?p=users&q=Ke', 'GET');
        $response = App::handle($request);
        $data = json_decode($response->getContent());

        $this->assertInternalType('array', $data);
        $this->assertTrue(sizeof($data) === 2);
    }

    /** @test */
    public function can_assign_global_middleware_to_route()
    {
        $this->app['config']->set(
            'mentions.middleware',
            'test-middleware'
        );

        $request = Request::create('/api/mentions/?p=users&q=Ke', 'GET');
        App::handle($request);
        $route = Route::current();

        $this->assertContains('test-middleware', $route->gatherMiddleware());
    }
}
