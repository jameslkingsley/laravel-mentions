<?php

namespace Kingsley\Mentions\Test\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Kingsley\Mentions\Test\TestCase;
use Kingsley\Mentions\Mention;

class MentionsTest extends TestCase
{
    /** @test */
    public function can_mention_single_model()
    {
        $mention = $this->testCommentModel->mention($this->testUserModel->first());

        $this->assertEquals(Mention::class, $mention);
    }
}
