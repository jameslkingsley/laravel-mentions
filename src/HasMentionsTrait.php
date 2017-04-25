<?php

namespace Kingsley\Mentions;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Kingsley\Mentions\Exceptions\InvalidModelType;
use Kingsley\Mentions\Mention;

trait HasMentionsTrait
{
    /**
     * Create a new mention for the given model(s).
     *
     * @return [Collection] Kingsley\Mentions\Mention
     */
    public function mention($model)
    {
        if ($model instanceof Model) {
            return $this->createMention($model);
        }

        if ($model instanceof Collection) {
            $added = [];

            foreach ($model as $m) {
                $added[] = $this->createMention($m);
            }

            return collect($added);
        }

        throw InvalidModelType::create();
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
     * Creates a new mention for the given model.
     *
     * @return Kingsley\Mentions\Mention
     */
    private function createMention(Model $model)
    {
        $mention = Mention::create([
            'model_type' => get_class($this),
            'model_id' => $this->getKey(),
            'recipient_type' => get_class($model),
            'recipient_id' => $model->getKey()
        ]);

        $mention->setReference($this);

        if ($mention->pool($model)->auto_notify) {
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
}
