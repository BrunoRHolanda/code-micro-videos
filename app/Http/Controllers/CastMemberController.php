<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCastMemberRequest;
use App\Http\Requests\UpdateCastMemberRequest;
use App\Models\CastMember;

class CastMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCastMemberRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCastMemberRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CastMember  $castMember
     * @return \Illuminate\Http\Response
     */
    public function show(CastMember $castMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CastMember  $castMember
     * @return \Illuminate\Http\Response
     */
    public function edit(CastMember $castMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCastMemberRequest  $request
     * @param  \App\Models\CastMember  $castMember
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCastMemberRequest $request, CastMember $castMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CastMember  $castMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(CastMember $castMember)
    {
        //
    }
}
