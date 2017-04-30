<?php

namespace Kingsley\Mentions;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Kingsley\Mentions\MentionRepository;

trait HasMentionsTrait
{
    protected $mentionRepository;

    /**
     * Constructor method.
     *
     * @return any
     */
    public function __construct()
    {
        $this->mentionRepository = new MentionRepository($this);
    }

    /**
     * Create a new mention for the given model(s).
     *
     * @return [Collection] Kingsley\Mentions\Mention
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

        if ($model instanceof Collection || $model instanceof MentionCollection) {
            $caller = $this;
            $mentionCollection = new MentionCollection;

            $model->each(function($m) use(&$mentionCollection, $caller, $notify) {
                $mentionCollection->push($caller->mentionRepository->create($m, $notify));
            });

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

        if ($model instanceof Collection || $model instanceof MentionCollection) {
            foreach ($model as $m) {
                $this->mentionRepository->destroy($m);
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
    public function mentions(bool $resolve = true)
    {
        $mentions = $this->mentionRepository->get();

        if ($resolve) {
            $mentions = $mentions->map(function($mention) {
                return $mention->recipient();
            });
        }

        return $mentions;
    }
}
