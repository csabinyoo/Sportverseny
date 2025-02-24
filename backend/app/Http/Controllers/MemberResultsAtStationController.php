<?php

namespace App\Http\Controllers;

use App\Models\member_results_at_station;
use App\Http\Requests\Storemember_results_at_stationRequest;
use App\Http\Requests\Updatemember_results_at_stationRequest;

class MemberResultsAtStationController extends Controller
{
    public function index()
    {
        $rows = member_results_at_station::all();
        return response()->json(['data' => $rows], options: JSON_UNESCAPED_UNICODE);
    }

    public function store(Storemember_results_at_stationRequest $request)
    {
        try {
            $row = member_results_at_station::create($request->all());
            $data = [
                'message' => 'ok',
                'data' => $row
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            $data = [
                'message' => 'The post failed',
                'data' => $request->all()
            ];
        }

        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function show(int $id)
    {
        $row = member_results_at_station::find($id);
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

    public function update(Updatemember_results_at_stationRequest $request, int $id)
    {
        $row = member_results_at_station::find($id);
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
        $row = member_results_at_station::find($id);
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
