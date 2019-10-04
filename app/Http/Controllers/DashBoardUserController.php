<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\DashBoardUser;
use App\Http\Resources\DashBordUserResource;
use App\Events\DashBordEvent;
use Illuminate\Http\Response;

class DashBoardUserController extends Controller
{

     /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DashBordUserResource::collection(DashBoardUser::get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dashBordUser = new DashBoardUser();
        $dashBordUser->displayId   = $request->displayId;
        $dashBordUser->displayName = $request->displayName;
        $dashBordUser->status      = $request->status;
        $dashBordUser->firstName   = $request->firstName;
        $dashBordUser->lastName    = $request->lastName;
        $dashBordUser->rankNo      = $request->rankNo;
        $dashBordUser->mail        = $request->mail;
        $dashBordUser->rankName    = $request->rankName;
        $dashBordUser->phoneNo     = $request->phoneNo;
        $dashBordUser->belongsId   = $request->belongsId;
        $dashBordUser->belongsName = $request->belongsName;
        $dashBordUser->locationId  = $request->locationId;
        $dashBordUser->location    = $request->location;
        $dashBordUser->comentNum   = $request->comentNum;
        $dashBordUser->seisouFlag  = $request->seisouFlag;
        $dashBordUser->soujiFlag   = $request->soujiFlag;
        $dashBordUser->garbageFlag = $request->garbageFlag;
    
        $dashBordUser->save();
        event(new dashBordEvent($dashBordUser));
        
        return response($request, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DashBoardUser $dashBordUser)
    {
        return new DashBoardUserResource($dashBordUser);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\DashBoardUser  $dashBordUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DashBoardUser $dashBordUser)
    {
        //broadcast(new dashBordEvent($request->all()))->toOthers();
        DashBoardUser::where('id', $request->id)->update([
            'displayId'      =>$request->displayId,
            'displayName'    =>$request->displayName,
            'status'         =>$request->status,
            'firstName'      =>$request->firstName,
            'lastName'       =>$request->lastName,
            'rankNo'         =>$request->rankNo,
            'rankName'       =>$request->rankName,
            'phoneNo'        =>$request->phoneNo,
            'belongsId'      =>$request->belongsId,
            'belongsName'    =>$request->belongsName,
            'mail'           =>$request->mail,
            'locationId'     =>$request->locationId,
            'location'       =>$request->location,
            'locationPhon'   =>$request->locationPhon,
            'comentNum'      =>$request->comentNum,
            'comment'        =>$request->comment,
            ]);
        event(new dashBordEvent($request->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function convert_to_utf8_recursively($dat){
        if( is_string($dat) ){
           return mb_convert_encoding($dat, 'UTF-8', 'UTF-8');
        }
        elseif( is_array($dat) ){
           $ret = [];
           foreach($dat as $i => $d){
             $ret[$i] = convert_to_utf8_recursively($d);
           }
           return $ret;
        }
        else{
           return $dat;
        }
  }
}