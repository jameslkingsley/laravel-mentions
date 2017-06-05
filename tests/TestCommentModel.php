<?php

namespace Kingsley\Mentions\Test;

use Illuminate\Database\Eloquent\Model;
use Kingsley\Mentions\Traits\HasMentions;

class TestCommentModel extends Model
{
    use HasMentions;

    protected $table = 'test_mention_comments';
    protected $guarded = [];
    public $timestamps = false;
}
