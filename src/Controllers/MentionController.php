<?php

namespace Kingsley\Mentions\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Kingsley\Mentions\Collections\ModelCollection;

class MentionController extends Controller
{
    public function __construct()
    {
        $this->middleware(config('mentions.middleware'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resultSet = collect();

        $pool = (object) config("mentions.pools.{$request->p}");
        $model = app()->make($pool->model);

        $query = $model->where($pool->column, 'LIKE', "%$request->q%");

        if ($filter = isset($pool->filter) ? $pool->filter : null) {
            $query = $filter::handle($query);
        }

        $query->each(function ($record) use ($request, &$resultSet) {
            $record->pool = $request->p;
            $resultSet->push($record);
        });

        $resource = $pool->resource ?: ModelCollection::class;

        return new $resource($resultSet);
    }
}
