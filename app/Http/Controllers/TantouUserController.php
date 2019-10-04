<?php

namespace App\Http\Controllers;

use App\Model\TantouUser;
use Illuminate\Http\Request;
use App\Http\Resources\TantouUserResource;
use Illuminate\Http\Response;



class TantouUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TantouUserResource::collection(TantouUser::get());
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
     * @param  \App\Model\TantouUser  $tantouUser
     * @return \Illuminate\Http\Response
     */
    public function show(TantouUser $tantouUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\TantouUser  $tantouUser
     * @return \Illuminate\Http\Response
     */
    public function edit(TantouUser $tantouUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\TantouUser  $tantouUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TantouUser $tantouUser)
    {
        TantouUser::where('id', $request->id)->update([
            'gomiFlag'           =>$request->gomiFlag,
            'souziFlag'          =>$request->souziFlag,
            'seisouFlag'         =>$request->seisouFlag,
            'serverSoujiFlag'    =>$request->serverSoujiFlag,
            'hinomotoFlag'       =>$request->hinomotoFlag,

        ]);
        return response($request, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\TantouUser  $tantouUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(TantouUser $tantouUser)
    {
        //
    }
}
