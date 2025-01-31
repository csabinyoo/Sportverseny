<?php

namespace App\Http\Controllers;

use App\Models\team;
use App\Http\Requests\StoreteamRequest;
use App\Http\Requests\UpdateteamRequest;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreteamRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateteamRequest $request, team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(team $team)
    {
        //
    }
}
