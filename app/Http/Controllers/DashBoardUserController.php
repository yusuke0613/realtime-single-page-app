<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\DashBoardUser;
use App\Http\Resources\DashBordUserResource;
use App\Events\DashBordEvent;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
     * @param  string  $comentNum
     * @return \Illuminate\Http\Response
     */
    public function show($comentNum)
    {
        return  DashBoardUser::find($comentNum);
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $comentNum
     * @return \Illuminate\Http\Response
     */
    public function tag($request)
    {
        return  DashBoardUser::find($request);
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
        
        DB::beginTransaction();
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
            'comment'        =>$request->comment,
            'comentNum'      =>$request->comentNum,
            'gomiFlag'       =>$request->gomiFlag,
            'souziFlag'      =>$request->souziFlag,
            'seisouFlag'     =>$request->seisouFlag,
            'hinomotoFlag'   =>$request->hinomotoFlag,
            'serverFlag'     =>$request->serverFlag,
            ]);
        event(new dashBordEvent($request->all()));
        DB::commit();
        return response($request, Response::HTTP_ACCEPTED);
    }

    public function gomi(Request $request, DashBoardUser $dashBordUser)
    {
        DB::beginTransaction();
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DB::beginTransaction();
            DashBoardUser::where('id', '!=', $request->id)->update([
                'gomiFlag'       =>0,
            ]);
            DashBoardUser::where('id', $request->id)->update([
                'gomiFlag'       =>$request->gomiFlag,
            ]);
            
         }
        DB::commit();
        $users = DashBordUserResource::collection(DashBoardUser::get());
        event(new dashBordEvent($users->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }

    public function souzi(Request $request, DashBoardUser $dashBordUser)
    {
        DB::beginTransaction();
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('id', $request->id)->update([
                'souziFlag'      =>$request->souziFlag,
            ]);

            DashBoardUser::where('id', '!=', $request->id)->update([
                'souziFlag'      =>0,
            ]);
         }
        DB::commit();
        $users = DashBordUserResource::collection(DashBoardUser::get());
        event(new dashBordEvent($users->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }

    public function seisou(Request $request, DashBoardUser $dashBordUser)
    {
        DB::beginTransaction();
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('id', $request->id)->update([
                'seisouFlag'      =>$request->seisouFlag,
            ]);

            DashBoardUser::where('id', '!=', $request->id)->update([
                'seisouFlag'      =>0,
            ]);
         }
        DB::commit();
        $users = DashBordUserResource::collection(DashBoardUser::get());
        event(new dashBordEvent($users->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }


    public function hinomoto(Request $request, DashBoardUser $dashBordUser)
    {
        DB::beginTransaction();
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('id', $request->id)->update([
                'hinomotoFlag'      =>$request->hinomotoFlag,
            ]);

            DashBoardUser::where('id', '!=', $request->id)->update([
                'hinomotoFlag'      =>0,
            ]);
         }
        DB::commit();
        $users = DashBordUserResource::collection(DashBoardUser::get());
        event(new dashBordEvent($users->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }

    public function server(Request $request, DashBoardUser $dashBordUser)
    {
        DB::beginTransaction();
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('id', $request->id)->update([
                'serverFlag'      =>$request->serverFlag,
            ]);

            DashBoardUser::where('id', '!=', $request->id)->update([
                'serverFlag'      =>0,
            ]);
         }
        DB::commit();
        $users = DashBordUserResource::collection(DashBoardUser::get());
        event(new dashBordEvent($users->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }



    public function zaiseki(Request $request, DashBoardUser $dashBordUser)
    {
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('comentNum', $request->id)->update([
                'status'         =>0,
            ]);
         }
         $users = DashBordUserResource::collection(DashBoardUser::get());
         event(new dashBordEvent($users->all()));
        return response($request, Response::HTTP_ACCEPTED);
    }

    public function taiseki(Request $request, DashBoardUser $dashBordUser)
    {
        $users = DashBordUserResource::collection(DashBoardUser::get());
        foreach($users as $d){
            DashBoardUser::where('comentNum', $request->id)->update([
                'status'         =>4,
            ]);
         }
        $users = DashBordUserResource::collection(DashBoardUser::get());
        event(new dashBordEvent($users->all()));
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
