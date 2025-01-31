<?php

namespace App\Http\Controllers;

use App\Models\team_member;
use App\Http\Requests\Storeteam_memberRequest;
use App\Http\Requests\Updateteam_memberRequest;

class TeamMemberController extends Controller
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
    public function store(Storeteam_memberRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(team_member $team_member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateteam_memberRequest $request, team_member $team_member)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(team_member $team_member)
    {
        //
    }
}
