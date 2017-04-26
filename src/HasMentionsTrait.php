<?php

namespace Kingsley\Mentions;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Kingsley\Mentions\Mention;

trait HasMentionsTrait
{
    /**
     * Create a new mention for the given model(s).
     *
     * @return [Collection] Kingsley\Mentions\Mention
     */
    public function mention($model, $notify = true)
    {
        if (is_null($model)) return;

        if (is_string($model) && strlen(trim($model)) != 0) {
            $model = $this->parseMentions($model);
        }

        if ($model instanceof Model) {
            return $this->createMention($model, $notify);
        }

        if ($model instanceof Collection) {
            $caller = $this;

            return $model->map(function($m) use(&$caller, $notify) {
                return $caller->createMention($m, $notify);
            });
        }

        return;
    }

    /**
     * Delete all mentions for the given model(s).
     *
     * @return this
     */
    public function unmention($model)
    {
        if ($model instanceof Model) {
            $this->deleteMention($model);
            return $this;
        }

        if ($model instanceof Collection || is_array($model)) {
            foreach ($model as $m) {
                $this->deleteMention($m);
            }

            return $this;
        }

        throw InvalidModelType::create();
    }

    /**
     * Gets all mentions for the given model.
     *
     * @return Collection Model
     */
    public function mentions(bool $resolve = true)
    {
        $mentions = Mention::where('model_type', get_class($this))
            ->where('model_id', $this->getKey())
            ->get();

        if ($resolve) {
            $mentions = $mentions->map(function($mention) {
                return $mention->recipient();
            });
        }

        return $mentions;
    }

    /**
     * Gets the mentions as an encoded string for use in form fields.
     *
     * @return string
     */
    public function mentionsEncoded()
    {
        $mentions = $this->mentions();
        $encoded = collect();

        foreach ($mentions as $mention) {
            $pool = Mention::pool($mention);
            $encoded->push("{$pool->key}:{$mention->getKey()}");
        }

        return $encoded->implode(',');
    }

    /**
     * Creates a new mention for the given model.
     *
     * @return Kingsley\Mentions\Mention
     */
    private function createMention(Model $model, $notify = true)
    {
        $mention = Mention::create([
            'model_type' => get_class($this),
            'model_id' => $this->getKey(),
            'recipient_type' => get_class($model),
            'recipient_id' => $model->getKey()
        ]);

        $mention->setReference($this);

        if ($mention->pool($model)->auto_notify && $notify) {
            $mention->notify();
        }

        return $mention;
    }

    /**
     * Deletes all mentions for the given model.
     *
     * @return void
     */
    private function deleteMention(Model $model)
    {
        Mention::where('model_type', get_class($this))
            ->where('model_id', $this->getKey())
            ->where('recipient_type', get_class($model))
            ->where('recipient_id', $model->getKey())
            ->delete();
    }

    /**
     * Parses the mentions list passed in via form.
     *
     * @return Collection Model
     */
    public function parseMentions($list)
    {
        $list = collect(explode(',', $list));

        return $list->map(function($item) {
            $parts = explode(':', $item);
            $model = app()->make(config('mentions.pools.'.$parts[0].'.model'));
            return $model::findOrFail($parts[1]);
        });
    }
}
