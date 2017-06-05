<?php

namespace Kingsley\Mentions\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TestUserModel extends Model
{
    use Notifiable;

    protected $table = 'test_mention_users';
    protected $guarded = [];
    public $timestamps = false;
}
