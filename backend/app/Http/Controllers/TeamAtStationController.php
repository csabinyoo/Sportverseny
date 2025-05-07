<?php

namespace App\Http\Controllers;

use App\Models\team_at_station;
use App\Http\Requests\Storeteam_at_stationRequest;
use App\Http\Requests\Updateteam_at_stationRequest;
use Illuminate\Support\Facades\DB;

class TeamAtStationController extends Controller
{
    public function index()
    {
        $rows = team_at_station::all();
        return response()->json(['data' => $rows], options: JSON_UNESCAPED_UNICODE);
    }

    public function store(Storeteam_at_stationRequest $request)
    {
        try {
            $row = team_at_station::create($request->all());
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
        $row = team_at_station::find($id);
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

    public function update(Updateteam_at_stationRequest $request, int $id)
    {
        $row = team_at_station::find($id);
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
        $row = team_at_station::find($id);
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
    public function teamAtStation(int $stationId){
        $query = '
        SELECT ts.id, t.name, ts.stationId, t.id teamId FROM team_at_stations ts
  INNER JOIN teams t ON ts.teamId = t.id
  WHERE ts.stationId = ?
  ORDER BY t.name
        ';
        $rows = DB::select($query, [$stationId]);
        $data = [
            'message' => 'ok',
            'data' => $rows
        ];
        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }
}
