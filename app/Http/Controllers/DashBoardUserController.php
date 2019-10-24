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
    public function show($id)
    {
        return  DashBoardUser::find($id);
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
            'gomiFlag'       =>$request->gomiFlag,
            'souziFlag'      =>$request->souziFlag,
            'seisouFlag'     =>$request->seisouFlag,
            'hinomotoFlag'   =>$request->hinomotoFlag,
            'serverFlag'     =>$request->serverFlag,
            ]);
        event(new dashBordEvent($request->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }

    public function gomi(Request $request, DashBoardUser $dashBordUser)
    {
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('id', $d['id'])->update([
                'displayId'      =>$d['displayId'],
                'displayName'    =>$d['displayName'],
                'status'         =>$d['status'],
                'firstName'      =>$d['firstName'],
                'lastName'       =>$d['lastName'],
                'rankNo'         =>$d['rankNo'],
                'rankName'       =>$d['rankName'],
                'phoneNo'        =>$d['phoneNo'],
                'belongsId'      =>$d['belongsId'],
                'belongsName'    =>$d['belongsName'],
                'mail'           =>$d['mail'],
                'locationId'     =>$d['locationId'],
                'location'       =>$d['location'],
                'locationPhon'   =>$d['locationPhon'],
                'comentNum'      =>$d['comentNum'],
                'comment'        =>$d['comment'],
                'gomiFlag'       =>0,
                'souziFlag'      =>$d['souziFlag'],
                'seisouFlag'     =>$d['seisouFlag'],
                'hinomotoFlag'   =>$d['hinomotoFlag'],
                'serverFlag'     =>$d['serverFlag'],
            ]);
         }
    
        //event(new dashBordEvent($request->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }

    public function souzi(Request $request, DashBoardUser $dashBordUser)
    {
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('id', $d['id'])->update([
                'displayId'      =>$d['displayId'],
                'displayName'    =>$d['displayName'],
                'status'         =>$d['status'],
                'firstName'      =>$d['firstName'],
                'lastName'       =>$d['lastName'],
                'rankNo'         =>$d['rankNo'],
                'rankName'       =>$d['rankName'],
                'phoneNo'        =>$d['phoneNo'],
                'belongsId'      =>$d['belongsId'],
                'belongsName'    =>$d['belongsName'],
                'mail'           =>$d['mail'],
                'locationId'     =>$d['locationId'],
                'location'       =>$d['location'],
                'locationPhon'   =>$d['locationPhon'],
                'comentNum'      =>$d['comentNum'],
                'comment'        =>$d['comment'],
                'gomiFlag'       =>$d['gomiFlag'],
                'souziFlag'      =>0,
                'seisouFlag'     =>$d['seisouFlag'],
                'hinomotoFlag'   =>$d['hinomotoFlag'],
                'serverFlag'     =>$d['serverFlag'],
            ]);
         }
    
        //event(new dashBordEvent($request->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }

    public function seisou(Request $request, DashBoardUser $dashBordUser)
    {
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('id', $d['id'])->update([
                'displayId'      =>$d['displayId'],
                'displayName'    =>$d['displayName'],
                'status'         =>$d['status'],
                'firstName'      =>$d['firstName'],
                'lastName'       =>$d['lastName'],
                'rankNo'         =>$d['rankNo'],
                'rankName'       =>$d['rankName'],
                'phoneNo'        =>$d['phoneNo'],
                'belongsId'      =>$d['belongsId'],
                'belongsName'    =>$d['belongsName'],
                'mail'           =>$d['mail'],
                'locationId'     =>$d['locationId'],
                'location'       =>$d['location'],
                'locationPhon'   =>$d['locationPhon'],
                'comentNum'      =>$d['comentNum'],
                'comment'        =>$d['comment'],
                'gomiFlag'       =>$d['gomiFlag'],
                'souziFlag'      =>$d['souziFlag'],
                'seisouFlag'     =>0,
                'hinomotoFlag'   =>$d['hinomotoFlag'],
                'serverFlag'     =>$d['serverFlag'],
            ]);
         }
    
        //event(new dashBordEvent($request->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }


    public function hinomoto(Request $request, DashBoardUser $dashBordUser)
    {
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('id', $d['id'])->update([
                'displayId'      =>$d['displayId'],
                'displayName'    =>$d['displayName'],
                'status'         =>$d['status'],
                'firstName'      =>$d['firstName'],
                'lastName'       =>$d['lastName'],
                'rankNo'         =>$d['rankNo'],
                'rankName'       =>$d['rankName'],
                'phoneNo'        =>$d['phoneNo'],
                'belongsId'      =>$d['belongsId'],
                'belongsName'    =>$d['belongsName'],
                'mail'           =>$d['mail'],
                'locationId'     =>$d['locationId'],
                'location'       =>$d['location'],
                'locationPhon'   =>$d['locationPhon'],
                'comentNum'      =>$d['comentNum'],
                'comment'        =>$d['comment'],
                'gomiFlag'       =>$d['gomiFlag'],
                'souziFlag'      =>$d['souziFlag'],
                'seisouFlag'     =>$d['seisouFlag'],
                'hinomotoFlag'   =>0,
                'serverFlag'     =>$d['serverFlag'],
            ]);
         }
    
        //event(new dashBordEvent($request->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }

    public function server(Request $request, DashBoardUser $dashBordUser)
    {
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('id', $d['id'])->update([
                'displayId'      =>$d['displayId'],
                'displayName'    =>$d['displayName'],
                'status'         =>$d['status'],
                'firstName'      =>$d['firstName'],
                'lastName'       =>$d['lastName'],
                'rankNo'         =>$d['rankNo'],
                'rankName'       =>$d['rankName'],
                'phoneNo'        =>$d['phoneNo'],
                'belongsId'      =>$d['belongsId'],
                'belongsName'    =>$d['belongsName'],
                'mail'           =>$d['mail'],
                'locationId'     =>$d['locationId'],
                'location'       =>$d['location'],
                'locationPhon'   =>$d['locationPhon'],
                'comentNum'      =>$d['comentNum'],
                'comment'        =>$d['comment'],
                'gomiFlag'       =>$d['gomiFlag'],
                'souziFlag'      =>$d['souziFlag'],
                'seisouFlag'     =>$d['seisouFlag'],
                'hinomotoFlag'   =>$d['hinomotoFlag'],
                'serverFlag'     =>0,
            ]);
         }
    
        //event(new dashBordEvent($request->all()));
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
