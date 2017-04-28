<?php

namespace Kingsley\Mentions\Test;

use Illuminate\Database\Eloquent\Model;

class TestUserModel extends Model
{
    protected $table = 'test_mention_users';
    protected $guarded = [];
    public $timestamps = false;
}
