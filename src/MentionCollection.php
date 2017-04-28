<?php

use Illuminate\Database\Eloquent\Collection;

class MentionCollection extends Collection
{
    /**
     * Gets the mentions collection as an encoded string.
     * Encodes it in the format <pool>:<reference>
     *
     * @return string
     */
    public function encoded()
    {
        $encoded = collect();

        $this->each(function($mention) use(&$encoded) {
            $encoded->push("{$mention->pool()->key}:{$mention->getKey()}");
        });

        return $encoded->implode(',');
    }

    /**
     * Notifies all mentions in the collection.
     *
     * @return any
     */
    public function notify($notify_class = '')
    {
        $this->each(function($mention) use($notify_class) {
            $mention->notify($notify_class);
        });

        return $this;
    }
}
