<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Announcement;
use App\Models\ReadAnnouncement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page = "users";
        $Role = Session::get('user_role');
        return view('dashboard.announcements.index', compact('page', 'Role'));
    }

    public function add()
    {
        $page = "users";
        $Role = Session::get('user_role');
        return view('dashboard.announcements.add', compact('page', 'Role'));
    }

    public function store(Request $request)
    {
        $AnnouncementType = $request['type'];
        $AnnouncementMessage = $request['message'];
        $ExpirationDateTime = $request['expiration_date_time'];
        $ExpirationDateTime = Carbon::parse($ExpirationDateTime)->format('Y-m-d H:i:s');

        DB::beginTransaction();
        DB::table('announcements')
            ->update([
                'status' => 0,
                'updated_at' => Carbon::now()
            ]);

        $affected = Announcement::create([
            'type' => $AnnouncementType,
            'message' => $AnnouncementMessage,
            'expiration' => $ExpirationDateTime,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if ($affected) {
            DB::commit();
            return redirect(route('announcements'))->with('message', 'Announcement has been added successfully.');
        } else {
            DB::rollback();
            return redirect(route('announcements'))->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function load(Request $request)
    {
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;
        if ($searchTerm == '') {
            $fetch_data = DB::table('announcements')
                ->where('announcements.deleted_at', '=', null)
                ->select('announcements.*')
                ->orderBy('created_at', 'DESC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('announcements')
                ->where('announcements.deleted_at', '=', null)
                ->select('announcements.*')
                ->orderBy('created_at', 'DESC')
                ->count();
        } else {
            $fetch_data = DB::table('announcements')
                ->where('announcements.deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('announcements.message', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('announcements.*')
                ->orderBy('created_at', 'DESC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('announcements')
                ->where('announcements.deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('announcements.message', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('announcements.*')
                ->orderBy('created_at', 'DESC')
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $AnnouncementType = "Website";
            $status = '<span class="badge badge-success">Active</span>';
            $active_ban = '<button class="btn btn-danger" id="deactive_' . $item->id . '" onclick="deactiveAnnouncement(this.id);" data-toggle="tooltip" title="Deactive Announcement"><i class="fas fa-ban"></i></button>';
            if ($item->type == 2) {
                $AnnouncementType = "CRM";
            }
            if ($item->status == 0) {
                $status = '<span class="badge badge-danger">Deactive</span>';
                $active_ban = '<button class="btn btn-success" id="active_' . $item->id . '" onclick="activeAnnouncement(this.id);" data-toggle="tooltip" title="Active Announcement"><i class="fas fa-check"></i></button>';
            }
            $sub_array = array();
            $sub_array['sr_no'] = $SrNo;
            $Message = "";
            if (strlen($item->message) > 65){
              $Message = substr($item->message, 0, 65) . '...';
            } else {
              $Message = $item->message;
            }
            $sub_array['message'] = $Message;
            $sub_array['expiration'] = '<span>' . Carbon::parse($item->expiration)->format('m/d/Y') . '<br><br>' . Carbon::parse($item->expiration)->format('g:i a') . '</span>';
            $sub_array['status'] = $status;
            $sub_array['action'] = $active_ban . '<button class="btn btn-danger" id="delete_' . $item->id . '" onclick="deleteAnnouncement(this.id);" data-toggle="tooltip" title="Delete Announcement"><i class="fas fa-trash"></i></button><button class="btn btn-primary" id="edit_' . $item->id . '" onclick="editAnnouncement(this.id);" data-toggle="tooltip" title="Edit Announcement"><i class="fas fa-eye"></i></button>';

            $SrNo++;
            $data[] = $sub_array;
        }

        $json_data = array(
            "draw" => intval($request->post('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function active(Request $request)
    {
        $id = $request->post('AnnouncementId');
        $check = DB::table('announcements')
            ->where('status', '=', 1)
            ->where('deleted_at', '=', null)
            ->count();

        if ($check > 0) {
            echo "Failed";
        } else {
            DB::beginTransaction();
            $affected = DB::table('announcements')
                ->where('id', $id)
                ->update([
                    'status' => 1,
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

    public function deactive(Request $request)
    {
        $id = $request->post('AnnouncementId');
        DB::beginTransaction();
        $affected = DB::table('announcements')
            ->where('id', $id)
            ->update([
                'status' => 0,
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

    public function edit($AnnouncementId)
    {
        $page = "users";
        $Role = Session::get('user_role');
        $Announcement = DB::table('announcements')->where('id', $AnnouncementId)->get();
        return view('dashboard.announcements.edit', compact('page', 'Role', 'Announcement', 'AnnouncementId'));
    }

    public function update(Request $request)
    {
        $AnnouncementId = $request['id'];
        $AnnouncementMessage = $request['message'];
        $ExpirationDateTime = $request['expiration_date_time'];
        $ExpirationDateTime = Carbon::parse($ExpirationDateTime)->format('Y-m-d H:i:s');

        DB::beginTransaction();
        $affected = DB::table('announcements')
            ->where('id', '=', $AnnouncementId)
            ->update([
                'message' => $AnnouncementMessage,
                'expiration' => $ExpirationDateTime,
                'updated_at' => Carbon::now()
            ]);

        if ($affected) {
            DB::commit();
            return redirect(route('announcements'))->with('message', 'Announcement updated successfully.');
        } else {
            DB::rollback();
            return redirect(route('announcements'))->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function delete(Request $request)
    {
        $AnnouncementId = $request['id'];
        DB::beginTransaction();
        $affected = DB::table('announcements')
            ->where('id', '=', $AnnouncementId)
            ->update([
                'deleted_at' => Carbon::now()
            ]);

        if ($affected) {
            DB::commit();
            return redirect(route('announcements'))->with('message', 'Announcement deleted successfully.');
        } else {
            DB::rollback();
            return redirect(route('announcements'))->with('error', 'Error! An unhandled exception occurred');
        }
    }

    // User who read announcement
    public function read(Request $request)
    {
        $AnnouncementId = $request['AnnouncementId'];
        // Check if this user have already read this announcement or not
        $ReadAnnouncementDetails = DB::table('read_announcements')
            ->where('announcement_id', '=', $AnnouncementId)
            ->where('user_id', '=', Auth::id())
            ->count();

        if ($ReadAnnouncementDetails == 0) {
          DB::beginTransaction();
          $affected = ReadAnnouncement::create([
            'announcement_id' => $AnnouncementId,
            'user_id' => Auth::id(),
            'created_at' => Carbon::now(),
          ]);

          if ($affected) {
              DB::commit();
              echo "Success";
          } else {
              DB::rollback();
              echo "Failed";
          }
        } else {
          echo "Success";
        }
    }

    public function viewDetails($AnnouncementId) {
      $page = "users";
      $Role = Session::get('user_role');
      return view('admin.announcement.details', compact('page', 'Role', 'AnnouncementId'));
    }

    public function loadAllAnnouncementDetails(Request $request)
    {
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];
        $AnnouncementId = $request->post("AnnouncementId");

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;
        if ($searchTerm == '') {
            $fetch_data = DB::table('read_announcements')
                ->join('profiles', 'read_announcements.user_id', '=', 'profiles.user_id')
                ->where("read_announcements.announcement_id", $AnnouncementId)
                ->select('read_announcements.*', 'profiles.firstname', 'profiles.middlename', 'profiles.lastname')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('read_announcements')
                ->join('profiles', 'read_announcements.user_id', '=', 'profiles.user_id')
                ->where("read_announcements.announcement_id", $AnnouncementId)
                ->select('read_announcements.*', 'profiles.firstname', 'profiles.middlename', 'profiles.lastname')
                ->count();
        } else {
            $fetch_data = DB::table('read_announcements')
                ->join('profiles', 'read_announcements.user_id', '=', 'profiles.user_id')
                ->where("read_announcements.announcement_id", $AnnouncementId)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('profiles.firstname', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('profiles.middlename', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('profiles.lastname', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('read_announcements.*', 'profiles.firstname', 'profiles.middlename', 'profiles.lastname')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('read_announcements')
                ->join('profiles', 'read_announcements.user_id', '=', 'profiles.user_id')
                ->where("read_announcements.announcement_id", $AnnouncementId)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('profiles.firstname', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('profiles.middlename', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('profiles.lastname', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('read_announcements.*', 'profiles.firstname', 'profiles.middlename', 'profiles.lastname')
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $FullName = $item->firstname . " " . $item->middlename . " " . $item->lastname;
            $sub_array = array();
            $sub_array['sr_no'] = $SrNo;
            $sub_array['user'] = $FullName;
            $SrNo++;
            $data[] = $sub_array;
        }

        $json_data = array(
            "draw" => intval($request->post('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        );

        echo json_encode($json_data);
    }
}
