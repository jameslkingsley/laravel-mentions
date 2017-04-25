<?php

namespace Kingsley\Mentions;

use Illuminate\Database\Eloquent\Model;
use Kingsley\Mentions\Exceptions\CannotFindPool;

class Mention extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model_type',
        'model_id',
        'recipient_type',
        'recipient_id'
    ];

    protected $reference;

    /**
     * Gets the recipient model.
     *
     * @return Model
     */
    public function recipient()
    {
        return $this->recipient_type::findOrFail($this->recipient_id);
    }

    /**
     * Notify the mentioned model.
     *
     * @return any
     */
    public function notify()
    {
        $model = $this->recipient();
        $pool = $this->pool($model);

        $model->notify(new $pool->notification($this->reference));
    }

    /**
     * Gets the pool config for the given model.
     *
     * @return void
     */
    public function pool(Model $model)
    {
        $name = get_class($model);

        foreach (config('mentions.pools') as $key => $pool) {
            if ($pool['model'] == $name) {
                return (object)$pool;
            }
        }

        throw CannotFindPool::create($name);
    }

    /**
     * Sets the reference model for this mention.
     *
     * @return this
     */
    public function setReference(Model $model)
    {
        $this->reference = $model;

        return $this;
    }
}
