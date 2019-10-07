<?php

namespace App\Http\Controllers;

use App\Model\Tantou;
use Illuminate\Http\Request;
use App\Http\Resources\TantouResource;
use Illuminate\Http\Response;
use App\Events\LikeEvent;

class TantouController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TantouResource::collection(Tantou::get());
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Tantou  $tantou
     * @return \Illuminate\Http\Response
     */
    public function show(Tantou $tantou)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Tantou  $tantou
     * @return \Illuminate\Http\Response
     */
    public function edit(Tantou $tantou)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Tantou  $tantou
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tantou $tantou)
    {
        Tantou::where('id', $request->id)->update([
            'userName'=>$request->userName,
        ]);
        event(new LikeEvent($request->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Tantou  $tantou
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tantou $tantou)
    {
        //
    }
}
