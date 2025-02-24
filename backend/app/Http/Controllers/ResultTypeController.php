<?php

namespace App\Http\Controllers;

use App\Models\result_type;
use App\Http\Requests\Storeresult_typeRequest;
use App\Http\Requests\Updateresult_typeRequest;

class ResultTypeController extends Controller
{
    public function index()
    {
        $rows = result_type::all();
        return response()->json(['data' => $rows], options: JSON_UNESCAPED_UNICODE);
    }

    public function store(Storeresult_typeRequest $request)
    {
        try {
            $row = result_type::create($request->all());
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
        $row = result_type::find($id);
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

    public function update(Updateresult_typeRequest $request, int $id)
    {
        $row = result_type::find($id);
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
        $row = result_type::find($id);
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
