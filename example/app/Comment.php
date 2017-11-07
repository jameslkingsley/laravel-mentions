<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kingsley\Mentions\Traits\HasMentions;

class Comment extends Model
{
    use HasMentions;

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];
}
