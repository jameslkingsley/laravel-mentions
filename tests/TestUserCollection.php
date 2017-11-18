<?php

namespace Kingsley\Mentions\Test;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TestUserCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = null;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => 'test'
        ];
    }
}
