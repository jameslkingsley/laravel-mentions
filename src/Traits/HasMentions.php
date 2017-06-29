<?php

namespace Kingsley\Mentions\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Kingsley\Mentions\Collections\MentionCollection;
use Kingsley\Mentions\Repositories\MentionRepository;

trait HasMentions
{
    /**
     * Mention repository.
     *
     * @return Kingsley\Mentions\Repositories\MentionRepository
     */
    protected $mentionRepository;

    /**
     * Constructor method.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mentionRepository = new MentionRepository($this);
    }

    /**
     * Create a new mention for the given model(s).
     *
     * @return Collection Kingsley\Mentions\Models\Mention
     */
    public function mention($model, $notify = true)
    {
        if (is_null($model)) return null;

        if (is_string($model)) {
            $model = $this->mentionRepository->parse($model);
        }

        if ($model instanceof Model) {
            return $this->mentionRepository->create($model, $notify);
        }

        if ($model instanceof Collection) {
            $mentionCollection = new MentionCollection;

            foreach ($model as $item) {
                $mentionCollection->push($this->mentionRepository->create($item, $notify));
            }

            return $mentionCollection;
        }

        return null;
    }

    /**
     * Delete all mentions for the given model(s).
     *
     * @return this
     */
    public function unmention($model)
    {
        if (is_null($model)) return;

        if (is_string($model)) {
            $model = $this->mentionRepository->parse($model);
        }

        if ($model instanceof Model) {
            $this->mentionRepository->destroy($model);
            return $this;
        }

        if ($model instanceof Collection) {
            foreach ($model as $item) {
                $this->mentionRepository->destroy($item);
            }

            return $this;
        }

        return $this;
    }

    /**
     * Gets all mentions for the given model.
     *
     * @return Collection Model
     */
    public function mentions($resolve = true)
    {
        $mentions = $this->mentionRepository->get();

        if ($resolve) {
            $mentions->transform(function($mention) {
                return $mention->recipient();
            });
        }

        return $mentions;
    }
}
