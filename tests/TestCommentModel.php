<?php

namespace Kingsley\Mentions\Test;

use Illuminate\Database\Eloquent\Model;
use Kingsley\Mentions\HasMentionsTrait;

class TestCommentModel extends Model
{
    use HasMentionsTrait;

    protected $table = 'test_mention_comments';
    protected $guarded = [];
    public $timestamps = false;
}
