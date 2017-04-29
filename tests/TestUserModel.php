<?php

namespace Kingsley\Mentions\Test;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class TestUserModel extends Model
{
    use Notifiable;

    protected $table = 'test_mention_users';
    protected $guarded = [];
    public $timestamps = false;
}
