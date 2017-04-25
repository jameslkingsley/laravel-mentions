<?php

namespace Kingsley\Mentions\Exceptions;

use Exception;

class InvalidModelType extends Exception
{
    public static function create()
    {
        return new static('Invalid model type provided. Must be Eloquent model, or collection of Eloquent models.');
    }
}
