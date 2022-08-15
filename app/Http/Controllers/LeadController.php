<?php

namespace App\Http\Controllers;

use App\Helpers\SiteHelper;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\LeadDetails;
use App\Models\HistoryNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page = "leads";
        $Role = Session::get("user_role");
        return view('dashboard.leads.index', compact('page','Role'));
    }

    public function add()
    {
        $page = "leads";
        $Role = Session::get("user_role");
        $States = DB::table('states')->get();
        $Locations = DB::table('player_locations')
            ->where('status', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();
        $FreeClasses = DB::table('classes')
            ->where('is_free', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();
        return view('dashboard.leads.add', compact('page','Role','States','Locations', 'FreeClasses'));
    }

    public function store(Request $request)
    {
        $LeadId = $request['LeadId'];
        $ParentFirstName = $request['ParentFirstName'];
        $ParentLastName = $request['ParentLastName'];
        $ParentPhone1 = $request['ParentPhone1'];
        $ParentPhone2 = $request['ParentPhone2'];
        $ParentEmail = $request['ParentEmail'];
        $State = $request['State'];
        $City = $request['City'];
        $Street = $request['Street'];
        $Zipcode = $request['Zipcode'];
        $Location = $request['Location'];
        $LocationZipcode = $request['LocationZipcode'];
        $Message = $request['Message'];

        $data = array();
        if (($ParentFirstName != "" || $ParentLastName != "") && $ParentPhone1 != ""){
            if ($LeadId == "") {
                $LeadNumber = rand(1000000, 9999999);
                DB::beginTransaction();
                $Affected = null;
                $Affected1 = null;
                $Affected = Lead::create([
                    'lead_number' => $LeadNumber,
                    'parentFirstName' => $ParentFirstName,
                    'parentLastName' => $ParentLastName,
                    'parentPhone' => $ParentPhone1,
                    'parentPhone2' => $ParentPhone2,
                    'parentEmail' => $ParentEmail,
                    'state' => $State,
                    'city' => $City,
                    'street' => $Street,
                    'zipcode' => $Zipcode,
                    'message' => $Message,
                    'created_by' => Auth::id(),
                    'created_at' => Carbon::now(),
                ]);

                if ($Affected) {
                    DB::commit();
                    $data['lead_id'] = $Affected->id;
                    $data['status'] = 'success';
                } else {
                    DB::rollback();
                    $data['lead_id'] = '';
                    $data['status'] = 'error';
                }
            } else {
                DB::beginTransaction();
                $Affected = null;
                $Affected1 = null;
                $Affected = DB::table('leads')
                    ->where('id', $LeadId)
                    ->update([
                        'parentFirstName' => $ParentFirstName,
                        'parentLastName' => $ParentLastName,
                        'parentPhone' => $ParentPhone1,
                        'parentPhone2' => $ParentPhone2,
                        'parentEmail' => $ParentEmail,
                        'state' => $State,
                        'city' => $City,
                        'street' => $Street,
                        'zipcode' => $Zipcode,
                        'message' => $Message,
                        'updated_at' => Carbon::now(),
                    ]);

                if ($Affected) {
                    DB::commit();
                    $data['lead_id'] = $LeadId;
                    $data['status'] = 'success';
                } else {
                    DB::rollback();
                    $data['lead_id'] = $LeadId;
                    $data['status'] = 'error';
                }
            }
        } else{
            $data['lead_id'] = '';
            $data['status'] = 'error';
        }
        echo json_encode($data);
    }

    public function update(Request $request)
    {
        $LeadId = $request['lead_id'];
        $ParentFirstName = $request['parentFirstName'];
        $ParentLastName = $request['parentLastName'];
        $ParentPhone1 = $request['parentPhone'];
        $ParentPhone2 = $request['parentPhone2'];
        $ParentEmail = $request['parentEmail'];
        $State = $request['state'];
        $City = $request['city'];
        $Street = $request['street'];
        $Zipcode = $request['zipcode'];
        $Location = $request['location'];
        $LocationZipcode = $request['locationZipcode'];
        $Message = $request['message'];
        $GetRegisterOrScheduleFreeClass = $request['getregister_or_schedulefreeclass'];
        $FreeClass = null;
        $FreeClassTiming = null;
        $FreeClassDay = "";
        $FreeClassTime = "";

        // if free class is selected and free class time is given then get day and time
        if ($GetRegisterOrScheduleFreeClass == 2) {
            $FreeClass = $request['free_class'];
            $FreeClassTiming = $request['free_class_time'];
            if ($FreeClass != "" && $FreeClassTiming != "") {
                $ClassTimingDetails = DB::table('class_timings')
                                      ->where('id', $FreeClassTiming)
                                      ->get();
                $FreeClassDay = "";
                if ($ClassTimingDetails[0]->day == 1) {
                    $FreeClassDay = "Monday";
                } elseif ($ClassTimingDetails[0]->day == 2) {
                    $FreeClassDay = "Tuesday";
                } elseif ($ClassTimingDetails[0]->day == 3) {
                    $FreeClassDay = "Wednesday";
                } elseif ($ClassTimingDetails[0]->day == 4) {
                    $FreeClassDay = "Thrusday";
                } elseif ($ClassTimingDetails[0]->day == 5) {
                    $FreeClassDay = "Friday";
                } elseif ($ClassTimingDetails[0]->day == 6) {
                    $FreeClassDay = "Saturday";
                } elseif ($ClassTimingDetails[0]->day == 7) {
                    $FreeClassDay = "Sunday";
                }
                $FreeClassTime = $ClassTimingDetails[0]->time;
            }
        }

        if ($LeadId != "") {
            DB::beginTransaction();
            $Affected = null;
            $Affected = DB::table('leads')
                        ->where('id', $LeadId)
                        ->update([
                            'parentFirstName' => $ParentFirstName,
                            'parentLastName' => $ParentLastName,
                            'parentPhone' => $ParentPhone1,
                            'parentPhone2' => $ParentPhone2,
                            'parentEmail' => $ParentEmail,
                            'state' => $State,
                            'city' => $City,
                            'street' => $Street,
                            'zipcode' => $Zipcode,
                            'location' => $Location,
                            'locationZipcode' => $LocationZipcode,
                            'message' => $Message,
                            'getregister_or_schedulefreeclass' => $GetRegisterOrScheduleFreeClass,
                            'free_class' => $FreeClass,
                            'free_class_time_id' => $FreeClassTiming,
                            'free_class_day' => $FreeClassDay,
                            'free_class_time' => $FreeClassTime,
                            'updated_at' => Carbon::now(),
                        ]);

            if ($request->has('playerInformation')) {
                foreach ($request->post('playerInformation') as $index => $player) {
                    if (($player['playerFirstName'] != "" || $player['playerLastName'] != "") && $player['playerDOB'] != "" && $player['playerRelationship'] != "") {
                        $startDate = Carbon::parse($player['playerDOB']);
                        $endDate = Carbon::now();
                        $playerAge = $startDate->diffInYears($endDate);
                        $Affected1 = LeadDetails::create([
                            'lead_id' => $LeadId,
                            'playerFirstName' => $player['playerFirstName'],
                            'playerLastName' => $player['playerLastName'],
                            'playerDOB' => Carbon::parse($player['playerDOB'])->format("Y-m-d"),
                            'playerAge' => $playerAge,
                            'playerGender' => $player['playerGender'],
                            'playerRelationship' => $player['playerRelationship'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('leads')->with('success', 'Lead added successfully');
        } else {
            return redirect()->route('leads')->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function GetLeadStatusColor($lead_status)
    {
        if ($lead_status == 1) {
            return '<span class="badge badge-success" style="background-color:#ed7e44;color:white;">Lead In</span>';
        } elseif ($lead_status == 2) {
            return '<span class="badge badge-danger" style="background-color:#fec354;color:white;">Incomplete</span>';
        } elseif ($lead_status == 3) {
            return '<span class="badge badge-warning" style="background-color:#bf903d;color:#ffffff;">Follow Up</span>';
        } elseif ($lead_status == 4) {
            return '<span class="badge badge-primary" style="background-color:#4371c3;color:white;">Assigned to Location</span>';
        } elseif ($lead_status == 5) {
            return '<span class="badge badge-warning" style="background-color:#595959;color:white;">Invitation Free Class</span>';
        } elseif ($lead_status == 6) {
            return '<span class="badge badge-warning" style="background-color:#724da2;color:white;">Scheduled for Class</span>';
        } elseif ($lead_status == 7) {
            return '<span class="badge badge-secondary" style="background-color:#093feb;color:#ffffff;">Attended Class</span>';
        } elseif ($lead_status == 8) {
            return '<span class="badge badge-success" style="background-color:#33cc33;color:black;">Registered</span>';
        } elseif ($lead_status == 9) {
            return '<span class="badge badge-success" style="background-color:#0d0d0d;color:white;">Set Up Account</span>';
        } elseif ($lead_status == 10) {
            return '<span class="badge badge-success" style="background-color:#e13fd5;color:white;">Waiver</span>';
        } elseif ($lead_status == 11) {
            return '<span class="badge badge-success" style="background-color:#92d050;color:black;">Active</span>';
        } elseif ($lead_status == 12) {
            return '<span class="badge badge-success" style="background-color:#FEBA07; color:black;">Waiting List</span>';
        }
    }

    public function GetLeadStatusName($lead_status)
    {
        if ($lead_status == 1) {
            return 'Lead In';
        } elseif ($lead_status == 2) {
            return 'Incomplete';
        } elseif ($lead_status == 3) {
            return 'Follow Up';
        } elseif ($lead_status == 4) {
            return 'Assigned to Location';
        } elseif ($lead_status == 5) {
            return 'Invitation Free Class';
        } elseif ($lead_status == 6) {
            return 'Scheduled for Class';
        } elseif ($lead_status == 7) {
            return 'Attended Class';
        } elseif ($lead_status == 8) {
            return 'Registered';
        } elseif ($lead_status == 9) {
            return 'Set Up Account';
        } elseif ($lead_status == 10) {
            return 'Waiver';
        } elseif ($lead_status == 11) {
            return 'Active';
        } elseif ($lead_status == 12) {
            return 'Waiting List';
        }
    }

    function load(Request $request)
    {
        $Role = Session::get('user_role');
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];

        $columnIndex = $request->post('order')[0]['column']; // Column index
        $columnName = $request->post('columns')[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->post('order')[0]['dir']; // asc or desc

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;

        if ($searchTerm == '') {
            $fetch_data = DB::table('leads')
                ->join('user_details', 'leads.created_by', '=', 'user_details.user_id')
                ->where('leads.deleted_at', '=', null)
                ->select('leads.*', 'user_details.firstName AS FirstName', 'user_details.lastName AS LastName')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('leads')
                ->join('user_details', 'leads.created_by', '=', 'user_details.user_id')
                ->where('leads.deleted_at', '=', null)
                ->select('leads.*', 'user_details.firstName AS FirstName', 'user_details.lastName AS LastName')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        } else {
            $fetch_data = DB::table('leads')
                ->join('user_details', 'leads.created_by', '=', 'user_details.user_id')
                ->where('leads.deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('leads.lead_number', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.parentFirstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.parentLastName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.parentPhone', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.state', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.city', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.street', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.zipcode', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.created_at', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('leads.*', 'user_details.firstName AS FirstName', 'user_details.lastName AS LastName')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('leads')
                ->join('user_details', 'leads.created_by', '=', 'user_details.user_id')
                ->where('leads.deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('leads.lead_number', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.parentFirstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.parentLastName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.parentPhone', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.state', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.city', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.street', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.zipcode', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.created_at', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('leads.*', 'user_details.firstName AS FirstName', 'user_details.lastName AS LastName')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $Action = "";
            $HomeOwner = "";
            $Phone = "";
            $lead_status = "";
            $sub_array = array();
            if ($item->street != "") {
                $HomeOwner .= $item->street . ", ";
            }
            if ($item->city != "") {
                $HomeOwner .= $item->city . ", ";
            }
            if ($item->state != "") {
                $HomeOwner .= $item->state . " ";
            }
            if ($item->zipcode != "") {
                $HomeOwner .= $item->zipcode;
            }
            if ($item->parentPhone != "") {
                $Phone .= SiteHelper::ConvertPhoneNumberFormat($item->parentPhone);
            }
            $lead_status = '<span id="leadupdatestatus_' . $item->id . '" class="cursor-pointer" onclick="showLeadUpdateStatus(this.id);">' . $this->GetLeadStatusColor($item->lead_status) . '</span>';
            $sub_array['created_at'] = $item->created_at;
            $sub_array['checkbox'] = '<input type="checkbox" class="checkAllBox allLeadsCheckBox" name="checkAllBox[]" value="' . $item->id . '" onchange="CheckIndividualLeadCheckbox();" />';
            $sub_array['id'] = '<span style="color: #000 !important;">' . $SrNo . '</span>';
            $sub_array['affiliate'] = '<span style="color: #000 !important;">' . wordwrap("<strong>" . $item->lead_number . "</strong><br>" . $item->FirstName . ' ' . $item->LastName . "<br>" . Carbon::parse($item->created_at)->format('m/d/Y - g:i a'), 30, '<br>') . '</span>';
            $sub_array['parent'] = '<span style="color: #000 !important;">' . wordwrap("<strong>" . $item->parentFirstName . ' ' . $item->parentLastName . "</strong><br>" . $HomeOwner. "<br><strong>" . $Phone, 40, '</strong><br>') . '</span>';
            $sub_array['status'] = $lead_status;
            $Action .= "<span>";
            $Action .= '<button type="button" class="btn btn-primary btn-sm" id="leadhistory||' . $item->id . '" onclick="showLeadComments(this.id);" data-toggle="tooltip" title="Lead Comments"><i class="fas fa-sticky-note"></i></button>';
            $Action .= '<button type="button" class="btn btn-primary btn-sm" id="edit||' . $item->id . '" onclick="EditLead(this.id);" data-toggle="tooltip" title="View Lead"><i class="fas fa-eye"></i></button>';
            $Action .= "<span>";
            $sub_array['action'] = $Action;
            $data[] = $sub_array;
            $SrNo++;
        }

        $json_data = array(
            "draw" => intval($request->post('draw')),
            "iTotalRecords" => $recordsTotal,
            "iTotalDisplayRecords" => $recordsFiltered,
            "aaData" => $data
        );

        echo json_encode($json_data);
    }

    public function loadLeadComments(Request $request)
    {
        $LeadId = $request['LeadId'];
        $user_id = Auth::id();
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];

        $columnIndex = $request->post('order')[0]['column']; // Column index
        $columnName = $request->post('columns')[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->post('order')[0]['dir']; // asc or desc

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;
        if ($searchTerm == '') {
            $fetch_data = DB::table('history_notes')
                ->join('user_details', 'user_details.user_id', '=', 'history_notes.user_id')
                ->where('history_notes.deleted_at', '=', null)
                ->where('history_notes.lead_id', '=', $LeadId)
                ->select('history_notes.*', 'user_details.firstName', 'user_details.lastName')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('history_notes')
                ->join('user_details', 'user_details.user_id', '=', 'history_notes.user_id')
                ->where('history_notes.deleted_at', '=', null)
                ->where('history_notes.lead_id', '=', $LeadId)
                ->select('history_notes.*', 'user_details.firstName', 'user_details.lastName')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        } else {
            $fetch_data = DB::table('history_notes')
                ->join('user_details', 'user_details.user_id', '=', 'history_notes.user_id')
                ->where('history_notes.deleted_at', '=', null)
                ->where('history_notes.lead_id', '=', $LeadId)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('history_notes.history_note', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('history_notes.created_at', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('history_notes.*', 'user_details.firstName', 'user_details.lastName')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('history_notes')
                ->join('user_details', 'user_details.user_id', '=', 'history_notes.user_id')
                ->where('history_notes.deleted_at', '=', null)
                ->where('history_notes.lead_id', '=', $LeadId)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('history_notes.history_note', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('history_notes.created_at', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('history_notes.*', 'user_details.firstName', 'user_details.lastName')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $sub_array = array();
            $sub_array['created_at'] = $item->created_at;
            $sub_array['id'] = $SrNo;
            $sub_array['user_id'] = '<span>' . $item->firstName . " " . $item->lastName . '<br><br>' . Carbon::parse($item->created_at)->format('m/d/Y') . '<br><br>' . Carbon::parse($item->created_at)->format('g:i A');
            $sub_array['history_note'] = '<span>' . wordwrap($item->history_note, 40, "<br>") . '</span>';
            $SrNo++;
            $data[] = $sub_array;
        }

        $json_data = array(
            "draw" => intval($request->post('draw')),
            "iTotalRecords" => $recordsTotal,
            "iTotalDisplayRecords" => $recordsFiltered,
            "aaData" => $data
        );

        echo json_encode($json_data);
    }

    public function edit($LeadId)
    {
        $page = "leads";
        $Role = Session::get("user_role");
        $States = DB::table('states')->get();
        $Locations = DB::table('player_locations')
            ->where('status', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();
        $FreeClasses = DB::table('classes')
            ->where('is_free', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();
        $Lead = DB::table('leads')
            ->where('id', '=', $LeadId)
            ->where('deleted_at', '=', null)
            ->get();
        $LeadDetails = DB::table('lead_details')
            ->where('lead_id', '=', $LeadId)
            ->get();
        $FreeClassTimings = array();
        $DaysIncluded = array();
        $DaysExcluded = array();

        if ($LeadDetails[0]->free_class != "" && $LeadDetails[0]->free_class_date != "") {
            // Get days excluded
            $ClassTimings = DB::table('class_timings')
                ->where('class_id', $LeadDetails[0]->free_class)
                ->get();

            foreach ($ClassTimings as $index => $timing)
            {
                if ($timing->day == 1){
                    array_push($DaysIncluded, 1);
                } elseif ($timing->day == 2){
                    array_push($DaysIncluded, 2);
                } elseif ($timing->day == 3){
                    array_push($DaysIncluded, 3);
                } elseif ($timing->day == 4){
                    array_push($DaysIncluded, 4);
                } elseif ($timing->day == 5){
                    array_push($DaysIncluded, 5);
                } elseif ($timing->day == 6){
                    array_push($DaysIncluded, 6);
                } elseif ($timing->day == 7){
                    array_push($DaysIncluded, 0);
                }
            }

            for ($i=0; $i < 7; $i++)
            {
                if (!in_array($i, $DaysIncluded)){
                    array_push($DaysExcluded, $i);
                }
            }

            // Get class timings
            $timestamp = strtotime($LeadDetails[0]->free_class_date);
            $FreeClassDay = date('D', $timestamp);
            $Day = "";
            if ($FreeClassDay == "Mon"){
                $Day = 1;
            } elseif ($FreeClassDay == "Tue"){
                $Day = 2;
            } elseif ($FreeClassDay == "Wed"){
                $Day = 3;
            } elseif ($FreeClassDay == "Thu"){
                $Day = 4;
            } elseif ($FreeClassDay == "Fri"){
                $Day = 5;
            } elseif ($FreeClassDay == "Sat"){
                $Day = 6;
            } elseif ($FreeClassDay == "Sun"){
                $Day = 7;
            }

            $FreeClassTimings = DB::table('class_timings')
                ->where('class_id', '=', $LeadDetails[0]->free_class)
                ->where('day', $Day)
                ->get();
        }
        $DaysExcluded = json_encode($DaysExcluded);
        $Cities = DB::table('locations')
            ->where('state_name', '=', $Lead[0]->state)
            ->orderBy("city", "ASC")
            ->get()
            ->unique("city");
        return view('dashboard.leads.edit', compact('page','Role','States','Locations', 'FreeClasses', 'Lead', 'LeadDetails', 'FreeClassTimings', 'DaysExcluded', 'Cities'));
    }

    public function delete(Request $request)
    {
        $Leads = $request->post('checkAllBox');
        DB::beginTransaction();
        $Affected = null;
        foreach ($Leads as $key => $lead_id) {
            $Affected = DB::table('leads')
                ->where('id', $lead_id)
                ->update([
                    'updated_at' => Carbon::now(),
                    'deleted_at' => Carbon::now()
                ]);
        }
        if ($Affected) {
            DB::commit();
            return redirect()->route('leads')->with('success', 'Leads has been deleted successfully');
        } else {
            DB::rollback();
            return redirect()->route('leads')->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function updateLeadStatus(Request $request)
    {
        $lead_id = $request['lead_id'];
        $lead_status = $request['lead_status'];
        $attended_class_note = $request['attended_class_note'];
        $followup_time = $request['followup_time'];

        // Lead Details
        $lead_details = DB::table('leads')
                        ->where('id', '=', $lead_id)
                        ->get();

        $OldLeadStatus = $this->GetLeadStatusName($lead_details[0]->lead_status);
        $CurrentLeadStatus = $this->GetLeadStatusName($lead_status);
        $StatusChangeHistoryNote = "Changed the status of lead from " . $OldLeadStatus . " to " . $CurrentLeadStatus;

        DB::beginTransaction();
        $Affected = null;
        $Affected1 = null;
        $Affected2 = null;
        $Affected = DB::table('leads')
                    ->where('id', '=', $lead_id)
                    ->update([
                        'lead_status' => $lead_status,
                        'updated_at' => Carbon::now()
                    ]);

        $Affected1 = HistoryNote::create([
                        'user_id' => Auth::id(),
                        'lead_id' => $lead_id,
                        'history_note' => $StatusChangeHistoryNote,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                     ]);

        if ($lead_status == 2 || $lead_status == 4 || $lead_status == 5 || $lead_status == 6 || $lead_status == 8 || $lead_status == 9 || $lead_status == 10 || $lead_status == 11) {
            if ($Affected && $Affected1) {
                DB::commit();
                echo "Success";
            } else {
                DB::rollback();
                echo "Error";
            }
        }
        elseif ($lead_status == 3) {
            if ($followup_time != "") {
                $Affected2 = HistoryNote::create([
                              'user_id' => Auth::id(),
                              'lead_id' => $lead_id,
                              'history_note' => "Follow Up Time: " . Carbon::parse($followup_time)->format('m/d/Y - g:i a'),
                              'created_at' => Carbon::now(),
                              'updated_at' => Carbon::now()
                            ]);
            }

            if ($Affected && $Affected1 && $Affected2) {
                DB::commit();
                echo "Success";
            } else {
                DB::rollback();
                echo "Error";
            }
        }
        elseif ($lead_status == 7) {
          if ($attended_class_note != "") {
              $Affected2 = HistoryNote::create([
                            'user_id' => Auth::id(),
                            'lead_id' => $lead_id,
                            'history_note' => "Why they did not register: " . $attended_class_note,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                          ]);
          }

          if ($Affected && $Affected1 && $Affected2) {
              DB::commit();
              echo "Success";
          } else {
              DB::rollback();
              echo "Error";
          }
        }
    }

    public function saveComment(Request $request)
    {
        DB::beginTransaction();
        $Affected = HistoryNote::create([
                      'user_id' => Auth::id(),
                      'lead_id' => $request['LeadId'],
                      'history_note' => $request['Comment'],
                      'created_at' => Carbon::now(),
                      'updated_at' => Carbon::now()
                    ]);
        if ($Affected) {
            DB::commit();
            echo "Success";
        } else {
            DB::rollback();
            echo "Error";
        }
    }

    public function save(Request $request)
    {
        $LeadId = $request['lead_id'];
        $ParentFirstName = $request['parentFirstName'];
        $ParentLastName = $request['parentLastName'];
        $ParentPhone1 = $request['parentPhone'];
        $ParentPhone2 = $request['parentPhone2'];
        $ParentEmail = $request['parentEmail'];
        $State = $request['state'];
        $City = $request['city'];
        $Street = $request['street'];
        $Zipcode = $request['zipcode'];
        $GetRegisterOrScheduleFreeClass = $request['getregister_or_schedulefreeclass'];
        $PlayerDOB = null;
        $PlayerAge = null;
        $FreeClass = null;
        $FreeClassDate = null;
        $FreeClassTime = null;
        if ($request['playerDOB'] != ""){
            $PlayerDOB = Carbon::parse($request['playerDOB'])->format("Y-m-d");
            $startDate = Carbon::parse($request['playerDOB']);
            $endDate = Carbon::now();
            $PlayerAge = $startDate->diffInYears($endDate);
        }

        // if free class is selected and free class time is given then get day and time
        if ($GetRegisterOrScheduleFreeClass == 2) {
            if ($request['free_class'] != "" && $request['free_class_date'] != "" && $request['free_class_time'] != "") {
                $FreeClass = $request['free_class'];
                $FreeClassDate = Carbon::parse($request['free_class_date'])->format('Y-m-d');
                $FreeClassTime = $request['free_class_time'];
            }
        }

        if ($LeadId != "") {
            DB::beginTransaction();
            $Affected = DB::table('leads')
                        ->where('id', $LeadId)
                        ->update([
                            'parentFirstName' => $ParentFirstName,
                            'parentLastName' => $ParentLastName,
                            'parentPhone' => $ParentPhone1,
                            'parentPhone2' => $ParentPhone2,
                            'parentEmail' => $ParentEmail,
                            'state' => $State,
                            'city' => $City,
                            'street' => $Street,
                            'zipcode' => $Zipcode,
                            'getregister_or_schedulefreeclass' => $GetRegisterOrScheduleFreeClass,
                            'updated_at' => Carbon::now(),
                        ]);

            $Affected1 = DB::table('lead_details')
                ->where('lead_id', $LeadId)
                ->update([
                    'lead_id' => $LeadId,
                    'playerFirstName' => $request['playerFirstName'],
                    'playerLastName' => $request['playerLastName'],
                    'playerDOB' => $PlayerDOB,
                    'playerEmail' => $request['playerEmail'],
                    'playerAge' => $PlayerAge,
                    'playerGender' => $request['playerGender'],
                    'playerRelationship' => $request['playerRelationship'],
                    'location' => $request['location'],
                    'locationZipcode' => $request['locationZipcode'],
                    'message' => $request['message'],
                    'free_class' => $FreeClass,
                    'free_class_date' => $FreeClassDate,
                    'free_class_time' => $FreeClassTime,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

            DB::commit();
            return redirect()->route('leads')->with('success', 'Lead updated successfully');
        } else {
            return redirect()->route('leads')->with('error', 'Error! An unhandled exception occurred');
        }
    }
}
