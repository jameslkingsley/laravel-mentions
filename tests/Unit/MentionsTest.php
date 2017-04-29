<?php

namespace Kingsley\Mentions\Test\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Kingsley\Mentions\Test\TestCase;
use Kingsley\Mentions\Test\TestCommentModel;
use Kingsley\Mentions\Mention;
use Kingsley\Mentions\MentionCollection;

class MentionsTest extends TestCase
{
    /** @test */
    public function can_mention_single_model()
    {
        $mention = $this->testCommentModel->mention($this->testUserModel->first());

        $this->assertTrue($this->testCommentModel->mentions()->contains($mention));
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
        $encoded = $this->testUserModel->all()->map(function($user) {
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
        $encoded = $this->testUserModel->all()->map(function($user) {
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
}
