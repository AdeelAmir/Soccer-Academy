<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ClassTiming;
use App\Models\ClassAssign;
use App\Models\Attendence;
use App\Models\UserEvaluation;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ClassesController extends Controller
{
    var $ClassIdLength = 4;

    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $page = "classes";
        $Role = Session::get("user_role");
        $classes = array();
        $players = array();
        $locations = array();
        if ($Role == 4) {
            $class_array = array();
            $classes = DB::table('classes')
                ->where('classes.deleted_at', '=', null)
                ->where('classes.coach', '=', Auth::id())
                ->select('classes.id', 'classes.title')
                ->get();
            foreach ($classes as $key => $value) {
                array_push($class_array, $value->id);
            }

            $players = DB::table('class_assigns')
                ->join('users', 'class_assigns.player_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->leftJoin('player_positions', 'user_details.athletesPosition', '=', 'player_positions.id')
                ->whereIn('class_assigns.class_id', $class_array)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('users.id', 'ASC')
                ->get();
            // locations
            $CoachDetails = DB::table("user_details")
                ->where("user_id", Auth::id())
                ->get();

            $_CoachLocations = $CoachDetails[0]->coachLocations;
            $CoachLocations = array();
            if ($_CoachLocations != "") {
                $CoachLocations = explode(",", $_CoachLocations);
            }
            $locations = DB::table('player_locations')
                ->whereIn('id', $CoachLocations)
                ->select('player_locations.*')
                ->get();
        }
        return view('dashboard.classes.index', compact("page", "Role", "players", "classes", "locations"));
    }

    function load(Request $request)
    {
        $Role = Session::get('user_role');
        $Location = json_decode($request->post('Location'));
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];

        $columnIndex = $request->post('order')[0]['column']; // Column index
        $columnName = $request->post('columns')[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->post('order')[0]['dir']; // asc or desc

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;
        if ($Role == 1 || $Role == 2) {
            if ($searchTerm == '') {
                $fetch_data = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('classes.class_id', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('classes.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('categories.symbol', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.state', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.zipcode', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('classes.class_id', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('classes.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('categories.symbol', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.state', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.zipcode', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        } elseif ($Role == 3) {
            $ManagerLocations = SiteHelper::GetManagerLocation(Auth::id());
            if ($searchTerm == '') {
                $fetch_data = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->whereIn('classes.location', $ManagerLocations)
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->whereIn('classes.location', $ManagerLocations)
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->whereIn('classes.location', $ManagerLocations)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('classes.class_id', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('classes.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('categories.symbol', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.state', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.zipcode', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->whereIn('classes.location', $ManagerLocations)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('classes.class_id', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('classes.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('categories.symbol', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.state', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.zipcode', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        } elseif ($Role == 4) {
            if ($searchTerm == '') {
                $fetch_data = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->where('classes.coach', '=', Auth::id())
                    ->where(function ($query) use ($Location) {
                        if ($Location != "" && sizeof($Location) > 0) {
                            $query->whereIn('classes.location', $Location);
                        }
                    })
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->where('classes.coach', '=', Auth::id())
                    ->where(function ($query) use ($Location) {
                        if ($Location != "" && sizeof($Location) > 0) {
                            $query->whereIn('classes.location', $Location);
                        }
                    })
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->where('classes.coach', '=', Auth::id())
                    ->where(function ($query) use ($Location) {
                        if ($Location != "" && sizeof($Location) > 0) {
                            $query->whereIn('classes.location', $Location);
                        }
                    })
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('classes.class_id', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('classes.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('categories.symbol', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.state', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.zipcode', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('classes')
                    ->leftJoin('categories', 'classes.category', '=', 'categories.id')
                    ->leftJoin('player_locations', 'classes.location', '=', 'player_locations.id')
                    ->leftJoin('user_details', 'classes.coach', '=', 'user_details.user_id')
                    ->where('classes.deleted_at', '=', null)
                    ->where('classes.coach', '=', Auth::id())
                    ->where(function ($query) use ($Location) {
                        if ($Location != "" && sizeof($Location) > 0) {
                            $query->whereIn('classes.location', $Location);
                        }
                    })
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('classes.class_id', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('classes.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('categories.symbol', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.state', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('player_locations.zipcode', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('classes.*', 'categories.symbol AS CategorySymbol', 'classes.coach AS CoachTitle', 'player_locations.state', 'player_locations.zipcode', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'player_locations.name AS LocationName')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $CoachName = "";
            if ($item->middleName != "") {
                $CoachName = $item->firstName . " " . $item->middleName . " " . $item->lastName;
            } else {
                $CoachName = $item->firstName . " " . $item->lastName;
            }
            $sub_array = array();
            $CategorySymbol = '<b>' . $item->CategorySymbol . '</b>';
            $sub_array['created_at'] = $item->created_at;
            $sub_array['id'] = $SrNo;
            $sub_array['category'] = '<span style="color: #000 !important;">' . wordwrap("<strong>" . $item->class_id . "</strong><br>" . $item->title . "<br>" . $CoachName, 50, '<br>') . '</span>';
            $sub_array['package'] = '<span style="color: #000 !important;">' . wordwrap("" . $CategorySymbol . "", 50, '<br>') . '</span>';
            $sub_array['location'] = '<span style="color: #000 !important;">' . wordwrap($item->LocationName . "", 50, '<br>') . ' (' . $this->getTotalClassPlayers($item->id) . ')</span>';
            $Action = "";
            if ($Role == 1) {
                /*Admin*/
                if ($item->status == 1) {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeClassStatus(this.checked, this.value);" checked>';
                } else {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeClassStatus(this.checked, this.value);">';
                }
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="attendence||' . $item->id . '" onclick="ViewClassAttendence(this.id);" data-toggle="tooltip" title="Class Attendance"><i class="fa fa-clock-o"></i></button>';
                $Action .= '<button class="btn btn-primary btn-sm" id="assignPlayer||' . $item->id . '" onclick="ViewClassPlayers(this.id);" data-toggle="tooltip" title="Add Players"><i class="fas fa-running"></i></button>';
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="evaluation||' . $item->id . '" onclick="ClassEvaluation(this.id);" data-toggle="tooltip" title="Evaluation"><i class="fa fa-question-circle" aria-hidden="true"></i></button>';
                $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '" onclick="EditClass(this.id);" data-toggle="tooltip" title="View Class"><i class="fas fa-eye"></i></button>';
                $Action .= '<button class="btn btn-danger btn-sm" id="delete||' . $item->id . '" onclick="DeleteClass(this.id);" data-toggle="tooltip" title="Delete Class"><i class="fas fa-trash"></i></button>';
                $Action .= "<span>";
            } elseif ($Role == 2) {
                /*Global Manager*/
                if ($item->status == 1) {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeClassStatus(this.checked, this.value);" checked>';
                } else {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeClassStatus(this.checked, this.value);">';
                }
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="attendence||' . $item->id . '" onclick="ViewClassAttendence(this.id);" data-toggle="tooltip" title="Class Attendance"><i class="fa fa-clock-o"></i></button>';
                $Action .= '<button class="btn btn-primary btn-sm" id="assignPlayer||' . $item->id . '" onclick="ViewClassPlayers(this.id);" data-toggle="tooltip" title="Add Players"><i class="fas fa-running"></i></button>';
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="evaluation||' . $item->id . '" onclick="ClassEvaluation(this.id);" data-toggle="tooltip" title="Evaluation"><i class="fa fa-question-circle" aria-hidden="true"></i></button>';
                $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '" onclick="EditClass(this.id);" data-toggle="tooltip" title="View Class"><i class="fas fa-eye"></i></button>';
                $Action .= "<span>";
            } elseif ($Role == 3) {
                /*Manager*/
                if ($item->status == 1) {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeClassStatus(this.checked, this.value);" checked>';
                } else {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeClassStatus(this.checked, this.value);">';
                }
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="attendence||' . $item->id . '" onclick="ViewClassAttendence(this.id);" data-toggle="tooltip" title="Class Attendance"><i class="fa fa-clock-o"></i></button>';
                $Action .= '<button class="btn btn-primary btn-sm" id="assignPlayer||' . $item->id . '" onclick="ViewClassPlayers(this.id);" data-toggle="tooltip" title="Add Players"><i class="fas fa-running"></i></button>';
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="evaluation||' . $item->id . '" onclick="ClassEvaluation(this.id);" data-toggle="tooltip" title="Evaluation"><i class="fa fa-question-circle" aria-hidden="true"></i></button>';
                $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '" onclick="EditClass(this.id);" data-toggle="tooltip" title="View Class"><i class="fas fa-eye"></i></button>';
                $Action .= "<span>";
            } elseif ($Role == 4) {
                /*Coach*/
                if ($item->status == 1) {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeClassStatus(this.checked, this.value);" disabled checked>';
                } else {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeClassStatus(this.checked, this.value);" disabled>';
                }
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="attendence||' . $item->id . '" onclick="ViewClassAttendence(this.id);" data-toggle="tooltip" title="Class Attendance"><i class="fa fa-clock-o"></i></button>';
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="evaluation||' . $item->id . '" onclick="ClassEvaluation(this.id);" data-toggle="tooltip" title="Evaluation"><i class="fa fa-question-circle" aria-hidden="true"></i></button>';
                $Action .= "<span>";
            }
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

    function getTotalClassPlayers($ClassId)
    {
        $TotalNormalPlayers = DB::table("class_assigns")
            ->where('class_id', $ClassId)
            ->where('type', 1)
            ->get();

        $TotalGuessPlayers = DB::table("class_assigns")
            ->where('class_id', $ClassId)
            ->where('type', 2)
            ->where('start_date', '<=', date('Y-m-d'))
            ->where('end_date', '>=', date('Y-m-d'))
            ->get();

        $TotalPlayers = count($TotalNormalPlayers) + count($TotalGuessPlayers);
        return $TotalPlayers;
    }

    function add()
    {
        $page = "classes";
        $Role = Session::get("user_role");
        $Categories = DB::table('categories')
            ->where('deleted_at', '=', null)
            ->get();
        $Levels = DB::table('levels')
            ->where('deleted_at', '=', null)
            ->get();
        $MagicNumbers = DB::table('magic_numbers')
            ->where('id', '=', 1)
            ->get();
        $Locations = array();
        if ($Role == 1 || $Role == 2) {
            $Locations = DB::table('player_locations')
                ->where('status', '=', 1)
                ->where('deleted_at', '=', null)
                ->get();
        } elseif ($Role == 3) {
            $ManagerLocations = SiteHelper::GetManagerLocation(Auth::id());
            $Locations = DB::table('player_locations')
                ->where('status', '=', 1)
                ->where('deleted_at', '=', null)
                ->whereIn('id', $ManagerLocations)
                ->get();
        }
        $Coaches = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 4)
            ->where('users.status', '=', 1)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();
        return view('dashboard.classes.add', compact('page', 'Categories', 'Levels', 'MagicNumbers', 'Locations', 'Coaches'));
    }

    function store(Request $request)
    {
        DB::beginTransaction();
        $Affected = Classes::create([
            'title' => $request->post('title'),
            'class_id' => $this->GenerateClassId(),
            'coach' => $request->post('coach'),
            'category' => $request->post('category'),
            'levels' => $request->post('level') != '' ? implode(',', $request->post('level')) : null,
            'location' => $request->post('location'),
            'is_free' => $request->has('is_free') == true ? 1 : 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if ($request->has('timing')) {
            foreach ($request->post('timing') as $index => $timing) {
                if ($timing['days'] != "" && $timing['time'] != "") {
                    $Affected1 = ClassTiming::create([
                        'class_id' => $Affected->id,
                        'day' => $timing['days'],
                        'time' => Carbon::parse($timing['time'])->format('H:i:s'),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }

        if ($Affected) {
            DB::commit();
            return redirect()->route('classes')->with('success', 'Class created successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('classes')->with('error', 'An unhandled error occurred');
        }
    }

    function delete(Request $request)
    {
        DB::beginTransaction();
        $Affected = DB::table('classes')
            ->where('id', '=', $request->post('id'))
            ->update([
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('classes')->with('success', 'Class deleted successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('classes')->with('error', 'An unhandled error occurred');
        }
    }

    function edit($ClassId)
    {
        $page = "classes";
        $Role = Session::get("user_role");
        $ClassId = base64_decode($ClassId);
        $Class = DB::table('classes')
            ->where('id', '=', $ClassId)
            ->get();
        $ClassTimings = DB::table('class_timings')
            ->where('class_id', '=', $ClassId)
            ->get();
        $Categories = DB::table('categories')
            ->where('deleted_at', '=', null)
            ->get();
        $Levels = DB::table('levels')
            ->where('deleted_at', '=', null)
            ->get();
        $MagicNumbers = DB::table('magic_numbers')
            ->where('id', '=', 1)
            ->get();
        $Locations = array();
        if ($Role == 1 || $Role == 2) {
            $Locations = DB::table('player_locations')
                ->where('status', '=', 1)
                ->where('deleted_at', '=', null)
                ->get();
        } elseif ($Role == 3) {
            $ManagerLocations = SiteHelper::GetManagerLocation(Auth::id());
            $Locations = DB::table('player_locations')
                ->where('status', '=', 1)
                ->where('deleted_at', '=', null)
                ->whereIn('id', $ManagerLocations)
                ->get();
        }
        $Coaches = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 4)
            ->where('users.status', '=', 1)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();
        return view('dashboard.classes.edit', compact('page', 'Class', 'ClassTimings', 'Categories', 'Levels', 'MagicNumbers', 'Locations', 'Coaches'));
    }

    function update(Request $request)
    {
        DB::beginTransaction();
        DB::table('class_timings')->where('class_id', $request->post('id'))->delete();
        $Affected = DB::table('classes')
            ->where('id', '=', $request->post('id'))
            ->update([
                'title' => $request->post('title'),
                'coach' => $request->post('coach'),
                'category' => $request->post('category'),
                'levels' => $request->post('level') != '' ? implode(',', $request->post('level')) : null,
                'location' => $request->post('location'),
                'is_free' => $request->has('is_free') == true ? 1 : 0,
                'updated_at' => Carbon::now()
            ]);

        if ($request->has('timing')) {
            foreach ($request->post('timing') as $index => $timing) {
                if ($timing['days'] != "" && $timing['time'] != "") {
                    $Affected1 = ClassTiming::create([
                        'class_id' => $request->post('id'),
                        'day' => $timing['days'],
                        'time' => Carbon::parse($timing['time'])->format('H:i:s'),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }

        if ($Affected) {
            DB::commit();
            return redirect()->route('classes')->with('success', 'Class updated successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('classes')->with('error', 'An unhandled error occurred');
        }
    }

    private function GetLevelsString($Levels)
    {
        $Levels = explode(',', $Levels);
        $LevelsString = "";
        foreach ($Levels as $index => $item) {
            $Level = DB::table('levels')
                ->where('id', '=', $item)
                ->get();
            if ($index == 0) {
                $LevelsString = $Level[0]->symbol;
            } else {
                $LevelsString .= ", " . $Level[0]->symbol;
            }
        }
        return $LevelsString;
    }

    private function GetDaysString($Days)
    {
        $Days = explode(',', $Days);
        $DaysString = "";
        foreach ($Days as $index => $item) {
            $Day = "";
            if ($item == 1) {
                $Day = "M";
            } elseif ($item == 2) {
                $Day = "T";
            } elseif ($item == 3) {
                $Day = "W";
            } elseif ($item == 4) {
                $Day = "R";
            } elseif ($item == 5) {
                $Day = "F";
            } elseif ($item == 6) {
                $Day = "S";
            } elseif ($item == 7) {
                $Day = "U";
            }
            if ($index == 0) {
                $DaysString = $Day;
            } else {
                $DaysString .= ", " . $Day;
            }
        }
        return '<b>' . $DaysString . '</b>';
    }

    private function GenerateClassId()
    {
        $Class = DB::table('classes')
            ->orderBy('id', 'DESC')
            ->get();
        $ClassId = "";
        if (sizeof($Class) > 0) {
            $ClassId = str_pad((intval($Class[0]->class_id) + 1), $this->ClassIdLength, 0, STR_PAD_LEFT);
        } else {
            $ClassId = str_pad(1, $this->ClassIdLength, 0, STR_PAD_LEFT);
        }
        return $ClassId;
    }

    function updateStatus(Request $request)
    {
        $Status = 0;
        if ($request->post('Checked') == 'true') {
            $Status = 1;
        } else {
            $Status = 0;
        }
        DB::beginTransaction();
        $Affected = DB::table('classes')
            ->where('id', '=', $request->post('id'))
            ->update([
                'status' => $Status,
                'updated_at' => Carbon::now()
            ]);
        if ($Affected) {
            DB::commit();
            echo 'Success';
        } else {
            DB::rollBack();
            echo 'Failed';
        }
        exit();
    }

    /* CLASS ATTENDENCE - START */
    function attendence($ClassId)
    {
        $page = "classes";
        $Role = Session::get("user_role");
        $_ClassId = base64_decode($ClassId);
        $first_day_this_month = date('01-m-Y');
        $last_day_this_month = date('t-m-Y');
        $TotalDays = Carbon::parse($first_day_this_month)->diffInDays($last_day_this_month);
        $TotalDays = $TotalDays + 1;
        $CurrentMonth = date('F');
        $CurrentMonthYear = date('F Y');
        // Class Details
        $ClassDetails = DB::table('classes')
            ->where('id', $_ClassId)
            ->get();
        // Player Attendence
        $PlayerAttendence = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('class_assigns', 'users.id', '=', 'class_assigns.player_id')
            ->where('users.role_id', 6)
            ->where('users.deleted_at', null)
            ->where('class_assigns.class_id', $_ClassId)
            ->select('users.id', 'users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();
        // Attendence Record
        $AttendenceRecord = DB::table('attendences')
            ->where('attendences.class_id', $_ClassId)
            ->whereBetween('attendences.attendence_date', [Carbon::parse($first_day_this_month)->format("Y-m-d"), Carbon::parse($last_day_this_month)->addDays(1)->format("Y-m-d")])
            ->select('attendences.*')
            ->get();
        return view('dashboard.classes.attendence.index', compact("page", "Role", "ClassId", "_ClassId", "CurrentMonth", "CurrentMonthYear", "ClassDetails", "first_day_this_month", "last_day_this_month", "TotalDays", "PlayerAttendence", "AttendenceRecord"));
    }

    function getClassAttendence(Request $request)
    {
        $page = "classes";
        $Role = Session::get("user_role");
        $ClassId = $request['class_id'];
        $_ClassId = base64_decode($ClassId);
        $CurrentMonth = $request['month'];
        $CurrentYear = date('Y');
        $CurrentMonthYear = $CurrentMonth . ' ' . $CurrentYear;
        $MonthYear = strtotime($CurrentMonthYear);
        $first_second = date('Y-m-01', $MonthYear);
        $first_day_this_month = date("Y-m-01", strtotime($first_second));
        $last_day_this_month = date("Y-m-t", strtotime($first_second));
        $TotalDays = Carbon::parse($first_day_this_month)->diffInDays($last_day_this_month);
        $TotalDays = $TotalDays + 1;
        // Class Details
        $ClassDetails = DB::table('classes')
            ->where('id', $_ClassId)
            ->get();
        // Player Attendence
        $PlayerAttendence = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('class_assigns', 'users.id', '=', 'class_assigns.player_id')
            ->where('users.role_id', 6)
            ->where('users.deleted_at', null)
            ->where('class_assigns.class_id', $_ClassId)
            ->select('users.id', 'users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();
        // Attendence Record
        $AttendenceRecord = DB::table('attendences')
            ->where('attendences.class_id', $_ClassId)
            ->whereBetween('attendences.attendence_date', [Carbon::parse($first_day_this_month)->format("Y-m-d"), Carbon::parse($last_day_this_month)->addDays(1)->format("Y-m-d")])
            ->select('attendences.*')
            ->get();
        return view('dashboard.classes.attendence.index', compact("page", "Role", "ClassId", "_ClassId", "CurrentMonth", "CurrentMonthYear", "ClassDetails", "first_day_this_month", "last_day_this_month", "TotalDays", "PlayerAttendence", "AttendenceRecord"));
    }

    function AddAttendence($ClassId)
    {
        $page = "classes";
        $Role = Session::get("user_role");
        $_ClassId = base64_decode($ClassId);
        $AttendenceDate = date('m/d/Y');
        // Class Details
        $ClassDetails = DB::table('classes')
            ->join('player_locations', 'classes.location', '=', 'player_locations.id')
            ->where('classes.id', $_ClassId)
            ->select('classes.*', 'player_locations.name as LocationName')
            ->get();

        // Fetch Class Attendence Record
        $ClassPlayers = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('class_assigns', 'users.id', '=', 'class_assigns.player_id')
            ->where('users.role_id', 6)
            ->where('users.deleted_at', null)
            ->where('class_assigns.class_id', $_ClassId)
            ->select('class_assigns.type', 'class_assigns.start_date', 'class_assigns.end_date', 'users.id', 'users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();

        $PlayerAttendence = array();
        foreach ($ClassPlayers as $key => $value) {
            if ($value->type == 2) {
                $start_date = strtotime($value->start_date);
                $end_date = strtotime($value->end_date);
                $current_date = date('Y-m-d');
                $current_date = strtotime($current_date);
                if (($start_date <= $current_date) && ($end_date >= $current_date)) {
                    array_push($PlayerAttendence, $value);
                }
            } else {
                array_push($PlayerAttendence, $value);
            }
        }

        $AttendenceRecord = DB::table('attendences')
            ->where('attendences.class_id', $_ClassId)
            ->where('attendences.attendence_date', date('Y-m-d'))
            ->select('attendences.*')
            ->get();

        return view('dashboard.classes.attendence.add', compact("page", "Role", "ClassId", "_ClassId", "ClassDetails", "AttendenceDate", "PlayerAttendence", "AttendenceRecord"));
    }

    function EditAttendence(Request $request)
    {
        $page = "classes";
        $Role = Session::get("user_role");
        $ClassId = $request['class_id'];
        $_ClassId = base64_decode($ClassId);
        $EditAttendenceDate = $request['attendence_date'];
        $AttendenceDate = Carbon::createFromFormat('m/d/Y', $EditAttendenceDate)->format('d-m-Y');
        $_AttendenceDate = Carbon::createFromFormat('m/d/Y', $EditAttendenceDate)->format('Y-m-d');
        // Class Details
        $ClassDetails = DB::table('classes')
            ->join('player_locations', 'classes.location', '=', 'player_locations.id')
            ->where('classes.id', $_ClassId)
            ->select('classes.*', 'player_locations.name as LocationName')
            ->get();

        // Fetch Class Attendence Record
        $ClassPlayers = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('class_assigns', 'users.id', '=', 'class_assigns.player_id')
            ->where('users.role_id', 6)
            ->where('users.deleted_at', null)
            ->where('class_assigns.class_id', $_ClassId)
            ->select('class_assigns.type', 'class_assigns.start_date', 'class_assigns.end_date', 'users.id', 'users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();

        $PlayerAttendence = array();
        foreach ($ClassPlayers as $key => $value) {
            if ($value->type == 2) {
                $start_date = strtotime($value->start_date);
                $end_date = strtotime($value->end_date);
                $current_date = $_AttendenceDate;
                $current_date = strtotime($current_date);
                if (($start_date <= $current_date) && ($end_date >= $current_date)) {
                    array_push($PlayerAttendence, $value);
                }
            } else {
                array_push($PlayerAttendence, $value);
            }
        }

        $AttendenceRecord = DB::table('attendences')
            ->where('attendences.class_id', $_ClassId)
            ->where('attendences.attendence_date', Carbon::parse($AttendenceDate)->format('Y-m-d'))
            ->select('attendences.*')
            ->get();

        $AttendenceDate = Carbon::parse($AttendenceDate)->format('m/d/Y');
        return view('dashboard.classes.attendence.add', compact("page", "Role", "ClassId", "_ClassId", "ClassDetails", "AttendenceDate", "PlayerAttendence", "AttendenceRecord"));
    }

    public function UpdateAttendence(Request $request)
    {
        $ClassId = $request['class_id'];
        $_ClassId = base64_decode($ClassId);
        $EditAttendenceDate = $request['attendence_date'];
        $AttendenceDate = Carbon::createFromFormat('m/d/Y', $EditAttendenceDate)->format('d-m-Y');
        $_AttendenceDate = Carbon::createFromFormat('m/d/Y', $EditAttendenceDate)->format('Y-m-d');

        // Delete old attendence record of same date
        DB::table('attendences')
            ->where('attendences.class_id', $_ClassId)
            ->where('attendences.attendence_date', Carbon::parse($AttendenceDate)->format('Y-m-d'))
            ->select('attendences.*')
            ->delete();

        // Fetch Class Attendence Record
        $ClassPlayers = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('class_assigns', 'users.id', '=', 'class_assigns.player_id')
            ->where('users.role_id', 6)
            ->where('users.deleted_at', null)
            ->where('class_assigns.class_id', $_ClassId)
            ->select('class_assigns.type', 'class_assigns.start_date', 'class_assigns.end_date', 'users.id', 'users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();

        $PlayerAttendence = array();
        foreach ($ClassPlayers as $key => $value) {
            if ($value->type == 2) {
                $start_date = strtotime($value->start_date);
                $end_date = strtotime($value->end_date);
                $current_date = $_AttendenceDate;
                $current_date = strtotime($current_date);
                if (($start_date <= $current_date) && ($end_date >= $current_date)) {
                    array_push($PlayerAttendence, $value);
                }
            } else {
                array_push($PlayerAttendence, $value);
            }
        }
        $counter = count($PlayerAttendence);

        DB::beginTransaction();
        $Affected = null;
        for ($i = 0; $i < $counter; $i++) {
            $Affected = Attendence::create([
                'class_id' => $_ClassId,
                'player_id' => $request['userid_' . $i],
                'attendence_date' => Carbon::parse($AttendenceDate)->format('Y-m-d'),
                'status' => $request['attendence_status_' . $i],
                'remarks' => $request['remarks_' . $i],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        if ($Affected) {
            DB::commit();
            return redirect()->route('classes.attendence', ['id' => $ClassId])->with('success', 'Attendence updated successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('classes.attendence', ['id' => $ClassId])->with('error', 'An unhandled error occurred');
        }
    }
    /* CLASS ATTENDENCE - END */

    /* ASSIGN CLASS PLAYERS - START */
    function GetClassPlayerList(Request $request)
    {
        $Players = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 6)
            ->where('users.status', '=', 1)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();

        $ClassPlayers = DB::table('class_assigns')
            ->where('class_id', $request['id'])
            ->where('type', $request['type'])
            ->get();

        $options = "";
        $Status = 0;
        foreach ($Players as $key => $player) {
            $PlayerName = "";
            if ($player->middleName != "") {
                $PlayerName = $player->firstName . " " . $player->middleName . " " . $player->lastName;
            } else {
                $PlayerName = $player->firstName . " " . $player->lastName;
            }
            $Status = 0;
            foreach ($ClassPlayers as $index => $value) {
                if ($player->id == $value->player_id) {
                    $Status = 1;
                }
            }

            if ($request['type'] == 1) {
                if ($Status == 1) {
                    $options .= "<option value='" . $player->id . "' selected>" . $PlayerName . "</option>";
                } else {
                    $options .= "<option value='" . $player->id . "'>" . $PlayerName . "</option>";
                }
            } elseif ($request['type'] == 2) {
                if ($Status == 1) {
                    // $options .= "<option value='". $player->id ."' selected>". $PlayerName ."</option>";
                } else {
                    $options .= "<option value='" . $player->id . "'>" . $PlayerName . "</option>";
                }
            }
        }

        return json_encode($options);
    }

    function GetEvaluationClassPlayerList(Request $request)
    {
        $EvaluationDate = Carbon::createFromFormat('m/d/Y', $request['EvaluationDate'])->format('d-m-Y');
        $ClassPlayers = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('class_assigns', 'users.id', '=', 'class_assigns.player_id')
            ->where('users.role_id', 6)
            ->where('users.deleted_at', null)
            ->where('class_assigns.class_id', $request['ClassId'])
            ->select('class_assigns.type', 'class_assigns.start_date', 'class_assigns.end_date', 'users.id', 'users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();

        $PlayerAttendence = array();
        foreach ($ClassPlayers as $key => $value) {
            if ($value->type == 2) {
                $start_date = strtotime($value->start_date);
                $end_date = strtotime($value->end_date);
                $current_date = $EvaluationDate;
                $current_date = strtotime($current_date);
                if (($start_date <= $current_date) && ($end_date >= $current_date)) {
                    array_push($PlayerAttendence, $value);
                }
            } else {
                array_push($PlayerAttendence, $value);
            }
        }

        $options = "";
        $options .= "<option value=''>Select</option>";
        foreach ($PlayerAttendence as $key => $player) {
            $PlayerName = "";
            if ($player->middleName != "") {
                $PlayerName = $player->firstName . " " . $player->middleName . " " . $player->lastName;
            } else {
                $PlayerName = $player->firstName . " " . $player->lastName;
            }

            $options .= "<option value='" . $player->id . "'>" . $PlayerName . "</option>";
        }

        return json_encode($options);
    }

    function UpdateClassPlayers(Request $request)
    {
        $ClassId = $request['id'];
        $Players = $request['assign_class_player'];
        $Type = $request['player_type'];
        $StartDate = null;
        $EndDate = null;
        if ($request['start_date'] != "") {
            $StartDate = Carbon::parse($request['start_date'])->format('Y-m-d');
        }
        if ($request['end_date'] != "") {
            $EndDate = Carbon::parse($request['end_date'])->format('Y-m-d');
        }

        DB::beginTransaction();
        $Affected = null;
        if ($Type == 1) {
            DB::table('class_assigns')->where('class_id', $ClassId)->where('type', $Type)->delete();
        }

        foreach ($Players as $key => $value) {
            $Affected = ClassAssign::create([
                'class_id' => $ClassId,
                'player_id' => $value,
                'type' => $Type,
                'start_date' => $StartDate,
                'end_date' => $EndDate,
                'created_at' => Carbon::now(),
            ]);
        }
        if ($Affected) {
            DB::commit();
            return redirect()->route('classes')->with('success', 'Class updated successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('classes')->with('error', 'An unhandled error occurred');
        }
    }
    /* ASSIGN CLASS PLAYERS - END */

    /* CLASS EVALUATION - START */
    function openClassEvaluation($ClassId)
    {
        $page = "classes";
        $Role = Session::get("user_role");
        $ClassDetails = DB::table('classes')->where('id', $ClassId)->get();
        return view('dashboard.classes.evaluation.index', compact("page", "Role", "ClassId", "ClassDetails"));
    }

    function loadClassPlayers(Request $request)
    {
        $Role = Session::get('user_role');
        $ClassId = $request['id'];
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
            $fetch_data = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('user_evaluations.class_id', '=', $ClassId)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('user_evaluations.class_id', '=', $ClassId)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        } else {
            $fetch_data = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('user_evaluations.class_id', '=', $ClassId)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('user_evaluations.class_id', '=', $ClassId)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $PlayerName = $item->firstName . " " . $item->lastName;
            $sub_array = array();
            $sub_array['id'] = $SrNo;
            $sub_array['report_no'] = $item->report_no;
            $sub_array['player'] = '<span>' . wordwrap($PlayerName, 25, '<br>') . '</span>';
            $sub_array['evaluation_date'] = Carbon::parse($item->evaluation_date)->format('m/d/Y');
            if ($item->grade == "E") {
                $sub_array['grade'] = '<span class="cursor-pointer" data-toggle="tooltip" title="Excellent">' . $item->grade . '</span>';
            } elseif ($item->grade == "S") {
                $sub_array['grade'] = '<span class="cursor-pointer" data-toggle="tooltip" title="Satisfactory">' . $item->grade . '</span>';
            } elseif ($item->grade == "N") {
                $sub_array['grade'] = '<span class="cursor-pointer" data-toggle="tooltip" title="Need Work">' . $item->grade . '</span>';
            } elseif ($item->grade == "U") {
                $sub_array['grade'] = '<span class="cursor-pointer" data-toggle="tooltip" title="Under Performance">' . $item->grade . '</span>';
            } elseif ($item->grade == "NC") {
                $sub_array['grade'] = '<span class="cursor-pointer" data-toggle="tooltip" title="Not Covered">' . $item->grade . '</span>';
            }
            // $ReportPDF = asset('public/storage/user-evaluations/' . $item->report_pdf);
            $sub_array['report_pdf'] = '<a href="' . route('classes.evaluation.pdf', [$item->id]) . '"><i class="fa fa-download" aria-hidden="true"></i></a>';
            $Action = "";
            $Action = "<span>";
            $Action .= '<button class="btn btn-primary btn-sm" id="editPlayerEvaluation||' . $item->id . '" onclick="EditClassPlayerEvaluation(this.id);" data-toggle="tooltip" title="Player Evaluation"><i class="fa fa-eye"></i></button>';
            $Action .= '<button class="btn btn-danger btn-sm" id="deletePlayerEvaluation||' . $item->id . '" onclick="DeleteClassPlayerEvaluation(this.id);" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></button>';
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

    public function addClassPlayerEvaluation(Request $request)
    {
        $page = "classes";
        $Role = Session::get("user_role");
        $ClassId = $request['class_id'];
        $PlayerId = $request['evaluation_player'];
        $EvaluationDate = $request['evaluation_date'];

        // Check if user evaluation exists in record or not.
        $CheckUserEvaluation = DB::table('user_evaluations')
            ->where('user_id', $PlayerId)
            ->where('class_id', $ClassId)
            ->where('evaluation_date', Carbon::parse($EvaluationDate)->format('Y-m-d'))
            ->count();

        if ($CheckUserEvaluation > 0) {
            return redirect()->route('classes.evaluation', [$ClassId])->with('error', 'Evaluation of the player is already marked at this date');
        }

        // User Details
        $PlayerDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $PlayerId)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();

        // Class Details
        $ClassDetails = DB::table('classes')
            ->join('player_locations', 'classes.location', '=', 'player_locations.id')
            ->where('classes.id', $ClassId)
            ->select('classes.*', 'player_locations.name as LocationName')
            ->get();

        return view('dashboard.classes.evaluation.evaluation-add', compact('page', 'Role', 'ClassId', 'PlayerId', 'EvaluationDate', 'PlayerDetails', 'ClassDetails'));
    }

    public function storeClassPlayerEvaluation(Request $request)
    {
        $report_no = "00001";
        $UserEvaluations = DB::table('user_evaluations')->orderBy("id", "DESC")->limit(1)->get();
        if (count($UserEvaluations) > 0) {
            $report_no = $UserEvaluations[0]->report_no;
            $report_no = str_pad($report_no + 1, 5, 0, STR_PAD_LEFT);
        }
        $ReportPDFName = "MSA_evaluation_report_" . $report_no . ".pdf";
        $ClassId = $request['class_id'];
        $PlayerId = $request['player_id'];
        $EvaluationDate = $request['evaluation_date'];
        $RESPECTIVE = 0;
        $ATTENTION = 0;
        $CONCENTRATION = 0;
        $LEADERSHIP = 0;
        $ENERGETIC = 0;
        $DISCIPLINE = 0;
        $RUNNING = 0;
        $PASSING_RECEIVING = 0;
        $KICKING = 0;
        $BALLCONTROL = 0;
        $SHOOTING = 0;
        $BALANCE = 0;
        $TOTALMARKS = 0;
        $OBTAINEDMARKS = 0;
        $EVALUATION_PERCENTAGE = 0;
        $EVALUATION_GRADE = "";

        // RESPECTIVE
        if ($request['respective'] == "-") {
            $RESPECTIVE = $request['respective'];
        } else {
            $RESPECTIVE = round($request['respective'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $RESPECTIVE;
        }

        // ATTENTION
        if ($request['attention'] == "-") {
            $ATTENTION = $request['attention'];
        } else {
            $ATTENTION = round($request['attention'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $ATTENTION;
        }

        // CONCENTRATION
        if ($request['concentration'] == "-") {
            $CONCENTRATION = $request['concentration'];
        } else {
            $CONCENTRATION = round($request['concentration'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $CONCENTRATION;
        }

        // LEADERSHIP
        if ($request['leadership'] == "-") {
            $LEADERSHIP = $request['leadership'];
        } else {
            $LEADERSHIP = round($request['leadership'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $LEADERSHIP;
        }

        // ENERGETIC
        if ($request['energetic'] == "-") {
            $ENERGETIC = $request['energetic'];
        } else {
            $ENERGETIC = round($request['energetic'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $ENERGETIC;
        }

        // DISCIPLINE
        if ($request['discipline'] == "-") {
            $DISCIPLINE = $request['discipline'];
        } else {
            $DISCIPLINE = round($request['discipline'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $DISCIPLINE;
        }

        // RUNNING
        if ($request['running'] == "-") {
            $RUNNING = $request['running'];
        } else {
            $RUNNING = round($request['running'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $RUNNING;
        }

        // PASSING_RECEIVING
        if ($request['passing_receiving'] == "-") {
            $PASSING_RECEIVING = $request['passing_receiving'];
        } else {
            $PASSING_RECEIVING = round($request['passing_receiving'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $PASSING_RECEIVING;
        }

        // KICKING
        if ($request['kicking'] == "-") {
            $KICKING = $request['kicking'];
        } else {
            $KICKING = round($request['kicking'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $KICKING;
        }

        // BALLCONTROL
        if ($request['ball_control'] == "-") {
            $BALLCONTROL = $request['ball_control'];
        } else {
            $BALLCONTROL = round($request['ball_control'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $BALLCONTROL;
        }

        // SHOOTING
        if ($request['shooting'] == "-") {
            $SHOOTING = $request['shooting'];
        } else {
            $SHOOTING = round($request['shooting'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $SHOOTING;
        }

        // BALANCE
        if ($request['balance'] == "-") {
            $BALANCE = $request['balance'];
        } else {
            $BALANCE = round($request['balance'], 1);
            $TOTALMARKS += 5;
            $OBTAINEDMARKS += $BALANCE;
        }

        // CALCULATE EVALUATION GRADE
        if ($TOTALMARKS > 0) {
            $EVALUATION_PERCENTAGE = (($OBTAINEDMARKS / $TOTALMARKS) * 100);

            if ($EVALUATION_PERCENTAGE >= 90 && $EVALUATION_PERCENTAGE <= 100) {
                $EVALUATION_GRADE = "E";
            } elseif ($EVALUATION_PERCENTAGE >= 75 && $EVALUATION_PERCENTAGE <= 89) {
                $EVALUATION_GRADE = "S";
            } elseif ($EVALUATION_PERCENTAGE >= 65 && $EVALUATION_PERCENTAGE <= 74) {
                $EVALUATION_GRADE = "N";
            } elseif ($EVALUATION_PERCENTAGE >= 0 && $EVALUATION_PERCENTAGE <= 64) {
                $EVALUATION_GRADE = "U";
            }
        } else {
            $EVALUATION_GRADE = "NC";
        }

        DB::beginTransaction();
        $Affected = UserEvaluation::create([
            'report_no' => $report_no,
            'user_id' => $PlayerId,
            'class_id' => $ClassId,
            'respective' => $RESPECTIVE,
            'attention' => $ATTENTION,
            'concentration' => $CONCENTRATION,
            'leadership' => $LEADERSHIP,
            'energetic' => $ENERGETIC,
            'discipline' => $DISCIPLINE,
            'running' => $RUNNING,
            'passing_receiving' => $PASSING_RECEIVING,
            'kicking' => $KICKING,
            'ball_control' => $BALLCONTROL,
            'shooting' => $SHOOTING,
            'balance' => $BALANCE,
            'evaluation_date' => Carbon::parse($EvaluationDate)->format('Y-m-d'),
            'total_marks' => $TOTALMARKS,
            'obtained_marks' => $OBTAINEDMARKS,
            'grade' => $EVALUATION_GRADE,
            'report_pdf' => $ReportPDFName,
            'created_by' => Auth::id(),
            'created_at' => Carbon::now(),
        ]);

        if ($Affected) {
            DB::commit();
            return redirect()->route('classes.evaluation', [$ClassId])->with('success', 'Player evaluation report has been updated successfully');
        } else {
            DB::rollback();
            return redirect()->route('classes.evaluation', [$ClassId])->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function classPlayerEvaluationPDF($EvaluationId)
    {
        $EvaluationDetails = DB::table('user_evaluations')
            ->where('id', $EvaluationId)
            ->get();

        $ReportPDFName = "MSA_evaluation_report_" . $EvaluationDetails[0]->report_no . ".pdf";
        $PlayerName = "";
        $LoggedInUserName = "";
        $LoggedInUserRole = "";
        $PlayerDetails = $this->GetReportUserDetails($EvaluationDetails[0]->user_id);
        $ClassDetails = $this->GetReportClassDetails($EvaluationDetails[0]->class_id);

        // Player Name
        if ($PlayerDetails[0]->middleName != "") {
            $PlayerName = $PlayerDetails[0]->firstName . " " . $PlayerDetails[0]->middleName . " " . $PlayerDetails[0]->lastName;
        } else {
            $PlayerName = $PlayerDetails[0]->firstName . " " . $PlayerDetails[0]->lastName;
        }

        // Get Current Login User Role Name and User Details
        $LoggedInUserDetails = $this->GetLoggedInUserDetails();
        $LoggedInUserRole = $LoggedInUserDetails[0]->RoleTitle;

        // Logged In User Name
        if ($LoggedInUserDetails[0]->middleName != "") {
            $LoggedInUserName = $LoggedInUserDetails[0]->firstName . " " . $LoggedInUserDetails[0]->middleName . " " . $LoggedInUserDetails[0]->lastName;
        } else {
            $LoggedInUserName = $LoggedInUserDetails[0]->firstName . " " . $LoggedInUserDetails[0]->lastName;
        }

        $data = array(
            'player_name' => $PlayerName,
            'player_id' => $PlayerDetails[0]->userId,
            'loggedin_user_role' => $LoggedInUserRole,
            'loggedin_user_name' => $LoggedInUserName,
            'report_no' => $EvaluationDetails[0]->report_no,
            'date' => date('m/d/Y'),
            'category' => $PlayerDetails[0]->CategoryTitle,
            'respective' => $EvaluationDetails[0]->respective,
            'attention' => $EvaluationDetails[0]->attention,
            'concentration' => $EvaluationDetails[0]->concentration,
            'leadership' => $EvaluationDetails[0]->leadership,
            'energetic' => $EvaluationDetails[0]->energetic,
            'discipline' => $EvaluationDetails[0]->discipline,
            'running' => $EvaluationDetails[0]->running,
            'passing_receiving' => $EvaluationDetails[0]->passing_receiving,
            'kicking' => $EvaluationDetails[0]->kicking,
            'ball_control' => $EvaluationDetails[0]->ball_control,
            'shooting' => $EvaluationDetails[0]->shooting,
            'balance' => $EvaluationDetails[0]->balance,
        );

        return $this->GenerateEvaluationReportPDF($data, $ReportPDFName);
    }

    public function editClassPlayerEvaluation($EvaluationId)
    {
        $page = "classes";
        $Role = Session::get("user_role");
        $EvaluationDetails = DB::table('user_evaluations')->where('id', $EvaluationId)->get();
        $ClassId = $EvaluationDetails[0]->class_id;
        $PlayerId = $EvaluationDetails[0]->user_id;
        $EvaluationDate = $EvaluationDetails[0]->evaluation_date;

        // User Details
        $PlayerDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $PlayerId)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();

        // Class Details
        $ClassDetails = DB::table('classes')
            ->where('id', $ClassId)
            ->select('classes.title')
            ->get();

        return view('dashboard.classes.evaluation.evaluation-edit', compact('page', 'Role', 'ClassId', 'PlayerId', 'EvaluationDate', 'PlayerDetails', 'ClassDetails', 'EvaluationDetails'));
    }

    public function updateClassPlayerEvaluation(Request $request)
    {
        $report_no = $request['report_no'];
        $ReportPDFName = "MSA_evaluation_report_" . $report_no . ".pdf";
        $ClassId = $request['class_id'];
        $PlayerId = $request['player_id'];
        $EvaluationDate = $request['evaluation_date'];
        $RESPECTIVE = 0;
        $ATTENTION = 0;
        $CONCENTRATION = 0;
        $LEADERSHIP = 0;
        $ENERGETIC = 0;
        $DISCIPLINE = 0;
        $RUNNING = 0;
        $PASSING_RECEIVING = 0;
        $KICKING = 0;
        $BALLCONTROL = 0;
        $SHOOTING = 0;
        $BALANCE = 0;
        $TOTALMARKS = 0;
        $OBTAINEDMARKS = 0;
        $EVALUATION_PERCENTAGE = 0;
        $EVALUATION_GRADE = "";

        // RESPECTIVE
        if ($request['respective'] == "-") {
            $RESPECTIVE = $request['respective'];
        } else {
            $RESPECTIVE = $request['respective'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $RESPECTIVE;
        }

        // ATTENTION
        if ($request['attention'] == "-") {
            $ATTENTION = $request['attention'];
        } else {
            $ATTENTION = $request['attention'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $ATTENTION;
        }

        // CONCENTRATION
        if ($request['concentration'] == "-") {
            $CONCENTRATION = $request['concentration'];
        } else {
            $CONCENTRATION = $request['concentration'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $CONCENTRATION;
        }

        // LEADERSHIP
        if ($request['leadership'] == "-") {
            $LEADERSHIP = $request['leadership'];
        } else {
            $LEADERSHIP = $request['leadership'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $LEADERSHIP;
        }

        // ENERGETIC
        if ($request['energetic'] == "-") {
            $ENERGETIC = $request['energetic'];
        } else {
            $ENERGETIC = $request['energetic'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $ENERGETIC;
        }

        // DISCIPLINE
        if ($request['discipline'] == "-") {
            $DISCIPLINE = $request['discipline'];
        } else {
            $DISCIPLINE = $request['discipline'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $DISCIPLINE;
        }

        // RUNNING
        if ($request['running'] == "-") {
            $RUNNING = $request['running'];
        } else {
            $RUNNING = $request['running'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $RUNNING;
        }

        // PASSING_RECEIVING
        if ($request['passing_receiving'] == "-") {
            $PASSING_RECEIVING = $request['passing_receiving'];
        } else {
            $PASSING_RECEIVING = $request['passing_receiving'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $PASSING_RECEIVING;
        }

        // KICKING
        if ($request['kicking'] == "-") {
            $KICKING = $request['kicking'];
        } else {
            $KICKING = $request['kicking'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $KICKING;
        }

        // BALLCONTROL
        if ($request['ball_control'] == "-") {
            $BALLCONTROL = $request['ball_control'];
        } else {
            $BALLCONTROL = $request['ball_control'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $BALLCONTROL;
        }

        // SHOOTING
        if ($request['shooting'] == "-") {
            $SHOOTING = $request['shooting'];
        } else {
            $SHOOTING = $request['shooting'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $SHOOTING;
        }

        // BALANCE
        if ($request['balance'] == "-") {
            $BALANCE = $request['balance'];
        } else {
            $BALANCE = $request['balance'];
            $TOTALMARKS += 100;
            $OBTAINEDMARKS += $BALANCE;
        }

        // CALCULATE EVALUATION GRADE
        if ($TOTALMARKS > 0) {
            $EVALUATION_PERCENTAGE = (($OBTAINEDMARKS / $TOTALMARKS) * 100);

            if ($EVALUATION_PERCENTAGE >= 90 && $EVALUATION_PERCENTAGE <= 100) {
                $EVALUATION_GRADE = "E";
            } elseif ($EVALUATION_PERCENTAGE >= 75 && $EVALUATION_PERCENTAGE <= 89) {
                $EVALUATION_GRADE = "S";
            } elseif ($EVALUATION_PERCENTAGE >= 65 && $EVALUATION_PERCENTAGE <= 74) {
                $EVALUATION_GRADE = "N";
            } elseif ($EVALUATION_PERCENTAGE >= 0 && $EVALUATION_PERCENTAGE <= 64) {
                $EVALUATION_GRADE = "U";
            }
        } else {
            $EVALUATION_GRADE = "NC";
        }

        DB::beginTransaction();
        $Affected = $Affected2 = DB::table('user_evaluations')
            ->where('id', '=', $request->post('id'))
            ->update([
                'respective' => $RESPECTIVE,
                'attention' => $ATTENTION,
                'concentration' => $CONCENTRATION,
                'leadership' => $LEADERSHIP,
                'energetic' => $ENERGETIC,
                'discipline' => $DISCIPLINE,
                'running' => $RUNNING,
                'passing_receiving' => $PASSING_RECEIVING,
                'kicking' => $KICKING,
                'ball_control' => $BALLCONTROL,
                'shooting' => $SHOOTING,
                'balance' => $BALANCE,
                'evaluation_date' => Carbon::parse($EvaluationDate)->format('Y-m-d'),
                'total_marks' => $TOTALMARKS,
                'obtained_marks' => $OBTAINEDMARKS,
                'grade' => $EVALUATION_GRADE,
                'report_pdf' => $ReportPDFName,
                'updated_at' => Carbon::now(),
            ]);

        if ($Affected) {
            DB::commit();
            return redirect()->route('classes.evaluation', [$ClassId])->with('success', 'Player evaluation report has been updated successfully');
        } else {
            DB::rollback();
            return redirect()->route('classes.evaluation', [$ClassId])->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function GenerateEvaluationReportPDF($data, $report_name)
    {
        $pdf = PDF::loadView('dashboard.classes.evaluation.evaluation-report-pdf', $data);
        return $pdf->download($report_name);
    }

    public function deleteClassPlayerEvaluation(Request $request)
    {
        $EvaluationId = $request['id'];
        $EvaluationDetails = DB::table('user_evaluations')->where('id', $EvaluationId)->get();
        // Unlink old report
        $Path = public_path('storage/user-evaluations') . '/' . $EvaluationDetails[0]->report_pdf;
        if (file_exists($Path)) {
            unlink($Path);
        }
        DB::beginTransaction();
        $Affected = DB::table('user_evaluations')->where('id', $EvaluationId)->delete();
        if ($Affected) {
            DB::commit();
            return redirect()->back()->with('success', 'Evaluation report deleted successfully');
        } else {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error! An unhandled exception occurred');
        }
    }

    function GetReportUserDetails($UserId)
    {
        $UserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('categories', 'user_details.athletesCategory', '=', 'categories.id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 6)
            ->where('users.status', '=', 1)
            ->where('users.id', '=', $UserId)
            ->select('users.id', 'users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.street', 'user_details.city', 'user_details.state', 'user_details.zipcode', 'categories.title as CategoryTitle')
            ->get();

        return $UserDetails;
    }

    function GetReportClassDetails($ClassId)
    {
        $ClassDetails = DB::table('classes')
            ->join('player_locations', 'classes.location', '=', 'player_locations.id')
            ->where('classes.id', '=', $ClassId)
            ->select('player_locations.name')
            ->get();

        return $ClassDetails;
    }

    function GetLoggedInUserDetails()
    {
        $UserDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.deleted_at', '=', null)
            ->where('users.status', '=', 1)
            ->where('users.id', '=', Auth::id())
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.street', 'user_details.city', 'user_details.state', 'user_details.zipcode', 'roles.title as RoleTitle')
            ->get();

        return $UserDetails;
    }
    /* CLASS EVALUATION - END */

    /* PARENT REPORTS - START */
    function openParentReports()
    {
        $page = "parent-reports";
        $Role = Session::get("user_role");
        return view('dashboard.classes.evaluation.parentReport', compact("page", "Role"));
    }
    /* PARENT REPORTS - END */

    /* Coach Classes - Start */
    public function addPlayerAnnouncement(Request $request)
    {
        $AnnouncementType = $request['type'];
        $AnnouncementFor = $request['announcement_for'];
        $Classes = $request['classes'];
        $Players = $request['player'];
        $AnnouncementMessage = $request['message'];
        $ExpirationDateTime = $request['expiration_date_time'];
        $ExpirationDateTime = Carbon::parse($ExpirationDateTime)->format('Y-m-d H:i:s');

        DB::beginTransaction();
        if ($AnnouncementFor == 1) {
            foreach ($Classes as $key => $value) {
                $class_players = DB::table('class_assigns')
                    ->join('users', 'class_assigns.player_id', '=', 'users.id')
                    ->join('user_details', 'users.id', '=', 'user_details.user_id')
                    ->leftJoin('player_positions', 'user_details.athletesPosition', '=', 'player_positions.id')
                    ->where('class_assigns.class_id', '=', $value)
                    ->where('users.deleted_at', '=', null)
                    ->where('users.status', '=', 1)
                    ->where('users.role_id', '=', 6)
                    ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                    ->orderBy('users.id', 'ASC')
                    ->get();

                foreach ($class_players as $index => $player) {
                    $affected = Announcement::create([
                        'user_id' => $player->id,
                        'type' => $AnnouncementType,
                        'message' => $AnnouncementMessage,
                        'expiration' => $ExpirationDateTime,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        } elseif ($AnnouncementFor == 2) {
            foreach ($Players as $key => $value) {
                $affected = Announcement::create([
                    'user_id' => $value,
                    'type' => $AnnouncementType,
                    'message' => $AnnouncementMessage,
                    'expiration' => $ExpirationDateTime,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        if ($affected) {
            DB::commit();
            return redirect(route('classes'))->with('message', 'Announcement has been added successfully.');
        } else {
            DB::rollback();
            return redirect(route('classes'))->with('error', 'Error! An unhandled exception occurred');
        }
    }
    /* Coach Classes - End */
}
