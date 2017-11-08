<?php

namespace Kingsley\Mentions\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MentionController extends Controller
{
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

        $query->get([$model->getKeyName(), $pool->column])
            ->each(function ($record) use ($request, &$resultSet) {
                $record->pool = $request->p;
                $resultSet->push($record);
            });

        return response()->json($resultSet);
    }
}
