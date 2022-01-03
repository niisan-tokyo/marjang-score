<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeasonRequest;
use App\Http\Requests\UpdateSeasonRequest;
use App\Models\Season;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('season.index', ['seasons' => Season::all()->sortByDesc('id')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('season.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSeasonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSeasonRequest $request)
    {
        $season = new Season;
        $season->fill($request->validated());
        $season->save();
        return redirect(route('season.show', ['season' => $season->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Season  $season
     * @return \Illuminate\Http\Response
     */
    public function show(Season $season)
    {
        return view('season.show', compact('season'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Season  $season
     * @return \Illuminate\Http\Response
     */
    public function edit(Season $season)
    {
        return view('season.edit', compact('season'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSeasonRequest  $request
     * @param  \App\Models\Season  $season
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSeasonRequest $request, Season $season)
    {
        $season->fill($request->validated());
        $season->save();
        return redirect(route('season.show', ['season' => $season->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Season  $season
     * @return \Illuminate\Http\Response
     */
    public function destroy(Season $season)
    {
        //
    }
}
