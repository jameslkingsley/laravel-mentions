<?php

namespace Kingsley\Mentions;

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
        $result_set = collect();

        $pool = (object)config('mentions.pools.'.$request->p);
        $model = app()->make($pool->model);

        $records = $model
            ->where($pool->column, 'LIKE', "%$request->q%")
            ->get([$model->getKeyName(), $pool->column])
            ->each(function($record) use($request, &$result_set) {
                $record->pool = $request->p;
                $result_set->push($record);
            });

        return response()->json($result_set);
    }
}
