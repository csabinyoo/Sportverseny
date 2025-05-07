<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Http\Requests\StoreCompetitionRequest;
use App\Http\Requests\UpdateCompetitionRequest;
use DB;

class CompetitionController extends Controller
{

    public function competitionCurrentCompToFalse() {
        $query = 'update competitions set currentComp = 0';

        DB::statement($query);
        try {
            $data = [
                'message' => 'ok',
                'data' => []
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            $data = [
                'message' => 'The update failed',
                'data' => []
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function getCurrentComp() {
        // $row = Competition::where(currentComp, null, 1);
        $row = DB::table('competitions')->where('currentComp', 1)->get();

        if ($row) {
            $data = [
                'message' => 'ok',
                'data' => $row
            ];
        } else {
            $data = [
                'message' => 'Not competition selected',
                'data' => []
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function index()
    {
        $rows = Competition::all();
        return response()->json(['data' => $rows], options: JSON_UNESCAPED_UNICODE);
    }

    public function store(StoreCompetitionRequest $request)
    {
        try {
            $row = Competition::create($request->all());
            $data = [
                'message' => 'ok',
                'data' => $row
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            $data = [
                'message' => 'The post failed',
                'data' => []
            ];
        }

        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function show(int $id)
    {
        $row = Competition::find($id);
        if ($row) {
            $data = [
                'message' => 'ok',
                'data' => $row
            ];
        } else {
            $data = [
                'message' => 'Not found',
                'data' => [
                    'id' => $id
                ]
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function update(UpdateCompetitionRequest $request, int $id)
    {
        $row = Competition::find($id);
        if ($row) {
            $row->update($request->all());
            $data = [
                'message' => 'ok',
                'data' => $row
            ];
        } else {
            $data = [
                'message' => 'Not found',
                'data' => [
                    'id' => $id
                ]
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function destroy(int $id)
    {
        $row = Competition::find($id);
        if ($row) {
            $row->delete();
            $data = [
                'message' => 'ok',
                'data' => [
                    'id' => $id
                ]
            ];
        } else {
            $data = [
                'message' => 'Not found',
                'data' => [
                    'id' => $id
                ]
            ];
        }
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }
}
