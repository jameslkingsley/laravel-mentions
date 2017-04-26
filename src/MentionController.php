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

        $pools = $request->p;
        $query = $request->q;

        foreach ($pools as $p) {
            $pool = (object)config('mentions.pools.'.$p);
            $model = app()->make($pool->model);

            $records = $model
                ->where($pool->column, 'LIKE', "%$query%")
                ->get([$model->getKeyName(), $pool->column])
                ->each(function($record) use($p, &$result_set) {
                    $record->pool = $p;
                    $result_set->push($record);
                });
        }

        return response()->json($result_set);
    }
}
