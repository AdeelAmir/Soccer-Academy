<?php

namespace App\Helpers;

use App\Models\TrainingAssignment;
use App\Models\TrainingAssignmentFolder;
use App\Models\UserPower;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiteHelper
{
    public static function settings()
    {
        $Settings = array();
        $Settings['PrimaryColor'] = '#00007B';
        $Settings['SiteUrl'] = './';
        $Settings['Instagram'] = '';
        $Settings['Facebook'] = '';
        $Settings['LinkedIn'] = '';
        $Settings['Twitter'] = '';
        $Settings['SiteUrl'] = '';
        return $Settings;
    }

    public static function GetCityFromZipCode($ZipCode)
    {
        $City = null;
        $LocationsSql = "SELECT * FROM locations WHERE ((FIND_IN_SET(:zipcode, zipcode) > 0));";
        $Location = DB::select(DB::raw($LocationsSql), array($ZipCode));
        foreach ($Location as $item) {
            $City = $item->city;
        }
        return $City;
    }

    public static function ConvertPhoneNumberFormat($Phone)
    {
        $Phone = str_replace("-", "", $Phone);
        $Phone = substr_replace($Phone, "-", 3, 0);
        $Phone = substr_replace($Phone, "-", 7, 0);
        return $Phone;
    }

    public static function GetManagerLocation($ManagerId)
    {
        $UserDetails = DB::table('users')
          ->join('user_details', 'users.id', '=', 'user_details.user_id')
          ->where('users.deleted_at', '=', null)
          ->where('users.id', '=', $ManagerId)
          ->select('user_details.managerLocations')
          ->get();
        $ManagerLocations = array();
        if ($UserDetails[0]->managerLocations != "")  {
            $ManagerLocations = explode(",", $UserDetails[0]->managerLocations);
        }
        return $ManagerLocations;
    }

    public static function GetLocationManager($LocationId)
    {
        $ManagerName = "";
        $UserSql = "SELECT * FROM user_details WHERE ((FIND_IN_SET(:locationId, managerLocations) > 0));";
        $Users = DB::select(DB::raw($UserSql), array($LocationId));
        foreach ($Users as $user) {
            $ManagerName = $user->firstName . " " . $user->lastName;
        }
        return $ManagerName;
    }

    public static function CheckForUserDocumentStatus () {
        $Check = DB::table('users')
            ->where('id', '=', Auth::id())
            ->where('player_document_status', '=', 1)
            ->get();
        if(sizeof($Check) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function CheckForPackagePurchase() {
        $Check = DB::table('orders')
            ->where('user_id', '=', Auth::id())
            ->whereIn('status', array(1,3))
            ->get();
        if(sizeof($Check) > 0) {
            return true;
        } else {
            return false;
        }
    }

    static function GetNewFolderOrderNumber($RoleId)
    {
        $ordersArray = array();
        $ordersArray = array();
        $Assignment = DB::table('folders')
            ->where('role_id', '=', $RoleId)
            ->where('deleted_at', '=', null)
            ->get();
        if (sizeof($Assignment) > 0) {
            foreach ($Assignment as $assgmnt) {
                array_push($ordersArray, intval($assgmnt->order_no));
            }
            return max($ordersArray) + 1;
        } else {
            return 1;
        }
    }

    static function GetNewOrderNumber($RoleId, $FolderId)
    {
        $ordersArray = array();
        $Assignment = DB::table('training_rooms')
            ->where('role_id', '=', $RoleId)
            ->where('folder_id', '=', $FolderId)
            ->where('deleted_at', '=', null)
            ->get();
        if (sizeof($Assignment) > 0) {
            foreach ($Assignment as $assgmnt) {
                array_push($ordersArray, intval($assgmnt->order_no));
            }
            return max($ordersArray) + 1;
        } else {
            return 1;
        }
    }

    static function GetUserDetails($UserId)
    {
        $UserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', '=', $UserId)
            ->select('users.email', 'user_details.*')
            ->get();
        return $UserDetails;
    }

    static function GetPlayerFromLead($LeadId)
    {
        $LeadDetails = DB::table('leads')
            ->join('lead_details', 'leads.id', '=', 'lead_details.lead_id')
            ->where('leads.id', '=', $LeadId)
            ->select('lead_details.*')
            ->get();
        $Player = DB::table('user_details')
            ->where('user_details.firstName', '=', $LeadDetails[0]->playerFirstName)
            ->where('user_details.lastName', '=', $LeadDetails[0]->playerLastName)
            ->get();
        return $Player;
    }

    static function InsertUserPower($UserId, $Kpi, $LeadFunnel, $Reports)
    {
        $Affected = UserPower::create([
            'user_id' => $UserId,
            'kpi' => $Kpi,
            'lead_funnel' => $LeadFunnel,
            'reports' => $Reports,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        return $Affected;
    }

    static function InsertTrainingRoom($RoleId, $UserId)
    {
        $TrainingRoomFolders = DB::table('folders')
            ->where('role_id', $RoleId)
            ->where('deleted_at', '=', null)
            ->orderBy('order_no', 'ASC')
            ->get();

        foreach ($TrainingRoomFolders as $folder) {
            $Affected6 = TrainingAssignmentFolder::create([
                'user_id' => $UserId,
                'folder_id' => $folder->id,
                'completion_rate' => 0,
                'created_at' => Carbon::now(),
            ]);

            $TrainingRoom = DB::table('training_rooms')
                ->where('role_id', $RoleId)
                ->where('folder_id', $folder->id)
                ->where('deleted_at', '=', null)
                ->orderBy('order_no', 'ASC')
                ->get();

            foreach ($TrainingRoom as $room) {
                TrainingAssignment::create([
                    'user_id' => $UserId,
                    'assignment_type' => $room->type,
                    'training_assignment_folder_id' => $Affected6->id,
                    'assignment_id' => $room->id,
                    'status' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }
}
