<?php

namespace App\Http\Controllers;

use App\Models\Broadcast;
use App\Models\ReadBroadcast;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BroadcastController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function send(Request $request)
    {
        $Role = Session::get('user_role');
        $SenderId = Auth::id();
  			$Users = $request->post('checkAllBox');
        $BroadcastMessage = $request['broadcast_message'];

        DB::beginTransaction();
  			$Affected = null;
  			$Affected1 = null;
  			foreach ($Users as $key => $RecieverId) {
  				$Affected = Broadcast::create([
  						'sender_id' => $SenderId,
  						'message' => $BroadcastMessage,
  						'created_at' => Carbon::now(),
  						'updated_at' => Carbon::now()
  				]);

  				$Affected1 = ReadBroadcast::create([
  						'broadcast_id' => $Affected->id,
  						'reciever_id' => $RecieverId,
  						'created_at' => Carbon::now(),
  						'updated_at' => Carbon::now()
  				]);
  			}

        if ($Affected && $Affected1) {
            DB::commit();
            return redirect()->route('users')->with('success', 'Broadcast sent successfully');
        } else {
            DB::rollback();
            return redirect()->route('users')->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function getUserUnreadBroadcast() {
  		 	$RecieverId = Auth::id();
  			$BroadcastId = 0;
  			$ReadBroadcastId = 0;
  			$Message = "";
  			$Total = 0;
  			$broadcasts = DB::table('broadcasts')
  				 ->join('read_broadcasts', 'broadcasts.id', '=', 'read_broadcasts.broadcast_id')
  				 ->where('read_broadcasts.reciever_id', '=', $RecieverId)
  				 ->where('read_broadcasts.read_status', '=', 0)
  				 ->select('broadcasts.*', 'read_broadcasts.id AS ReadBroadcastId')
  				 ->limit(1)
  				 ->get();

  			if ($broadcasts != "" && count($broadcasts) > 0) {
  				$Total = 1;
  				$BroadcastId = $broadcasts[0]->id;
  				$ReadBroadcastId = $broadcasts[0]->ReadBroadcastId;
  				$Message = $broadcasts[0]->message;
  			}

  			$Data['Total'] = $Total;
  			$Data['BroadcastId'] = $BroadcastId;
  			$Data['ReadBroadcastId'] = $ReadBroadcastId;
  			$Data['RecieverId'] = $RecieverId;
  	    $Data['Message'] = $Message;
  	    return json_encode($Data);
	 }

   public function updateReadStatus(Request $request)
   {
       $BroadcastId = $request->post('BroadcastId');
			 $ReadBroadcastId = $request->post('ReadBroadcastId');
			 $BroadcastRecieverId =	$request->post('BroadcastRecieverId');

	     DB::beginTransaction();
	     $affected = DB::table('read_broadcasts')
					 ->where('id', $ReadBroadcastId)
	         ->update([
	             'read_status' => 1,
	             'updated_at' => Carbon::now()
	         ]);
	     if ($affected) {
	         DB::commit();
	         echo "Success";
	     } else {
	         DB::rollback();
	         echo "Failed";
	     }
   }
}
