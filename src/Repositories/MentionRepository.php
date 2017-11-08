<?php

namespace Kingsley\Mentions\Repositories;

use Illuminate\Support\Collection;
use Kingsley\Mentions\Models\Mention;
use Illuminate\Database\Eloquent\Model;

class MentionRepository
{
    protected $model;
    protected $mention;

    /**
     * Constructor method.
     *
     * @return any
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->mention = new Mention;
    }

    /**
     * Gets all mentions for this model.
     *
     * @return any
     */
    public function get()
    {
        return $this->mention->where('model_type', get_class($this->model))
            ->where('model_id', $this->model->getKey())
            ->get();
    }

    /**
     * Gets the mentions as an encoded string for use in form fields.
     *
     * @return any
     */
    public function encoded()
    {
        $mentions = $this->get();
        $encoded = collect();

        foreach ($mentions as $mention) {
            $pool = $this->mention->pool($mention);
            $encoded->push("{$pool->key}:{$mention->getKey()}");
        }

        return $encoded->implode(',');
    }

    /**
     * Parses the encoded string and returns a collection of models.
     *
     * @return Collection Model
     */
    public function parse(string $list)
    {
        if (!$list) {
            return $list;
        }

        $list = collect(explode(',', $list));

        return $list->map(function ($item) {
            $parts = explode(':', $item);
            $model = app()->make(config('mentions.pools.'.$parts[0].'.model'));
            return $model::findOrFail($parts[1]);
        });
    }

    /**
     * Creates a mention record.
     *
     * @return any
     */
    public function create(Model $recipient, $notify = true)
    {
        $mention = $this->mention->create([
            'model_type' => get_class($this->model),
            'model_id' => $this->model->getKey(),
            'recipient_type' => get_class($recipient),
            'recipient_id' => $recipient->getKey()
        ]);

        $mention->setReference($this->model);

        if ($mention->pool($recipient)->auto_notify && $notify) {
            $mention->notify($notify);
        }

        return $mention;
    }

    /**
     * Destroys all mentions for the given model.
     *
     * @return any
     */
    public function destroy(Model $model)
    {
        $this->mention->where('model_type', get_class($this->model))
            ->where('model_id', $this->model->getKey())
            ->where('recipient_type', get_class($model))
            ->where('recipient_id', $model->getKey())
            ->delete();
    }
}
