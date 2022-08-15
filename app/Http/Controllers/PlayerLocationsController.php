<?php

namespace App\Http\Controllers;

use App\Models\PlayerLocations;
use App\Models\LocationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;

class PlayerLocationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $page = "location";
        $Role = Session::get('user_role');
        $page = "location";
        return view('dashboard.locations.index', compact('page', 'Role'));
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
        if ($Role == 1 || $Role == 2) {
            if ($searchTerm == '') {
                $fetch_data = DB::table('player_locations')
                    ->where('deleted_at', '=', null)
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('player_locations')
                    ->where('deleted_at', '=', null)
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('player_locations')
                    ->where('deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('name', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('street', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('state', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('zipcode', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('player_locations')
                    ->where('deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('name', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('street', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('state', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('zipcode', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        } elseif ($Role == 3) {
            $ManagerLocations = SiteHelper::GetManagerLocation(Auth::id());
            if ($searchTerm == '') {
                $fetch_data = DB::table('player_locations')
                    ->where('deleted_at', '=', null)
                    ->whereIn('id', $ManagerLocations)
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('player_locations')
                    ->where('deleted_at', '=', null)
                    ->whereIn('id', $ManagerLocations)
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('player_locations')
                    ->where('deleted_at', '=', null)
                    ->whereIn('id', $ManagerLocations)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('name', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('street', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('state', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('zipcode', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('player_locations')
                    ->where('deleted_at', '=', null)
                    ->whereIn('id', $ManagerLocations)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('name', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('street', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('state', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('zipcode', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $Action = "";
            $sub_array = array();
            $sub_array['created_at'] = $item->created_at;
            $sub_array['id'] = $SrNo;
            $sub_array['header'] = '<span style="color: #000 !important;">' . wordwrap("" . $item->name . "<br><strong>" . $item->city . "</strong><br>" . $item->zipcode, 50, '<br>') . '</span>';
            $sub_array['player'] = $this->getTotalLocationPlayers($item->id);
            if ($Role == 1) {
                /*Admin*/
                if ($item->status == 1) {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeLocationStatus(this.checked, this.value);" checked>';
                } else {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeLocationStatus(this.checked, this.value);">';
                }
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '" onclick="EditLocation(this.id);" data-toggle="tooltip" title="View Location"><i class="fas fa-eye"></i></button>';
                $Action .= '<button class="btn btn-danger btn-sm" id="delete||' . $item->id . '" onclick="DeleteLocation(this.id);" data-toggle="tooltip" title="Delete Location"><i class="fas fa-trash"></i></button>';
                $Action .= "<span>";
            } elseif ($Role == 2) {
                /*Global Manager*/
                if ($item->status == 1) {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeLocationStatus(this.checked, this.value);" checked>';
                } else {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeLocationStatus(this.checked, this.value);">';
                }
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '" onclick="EditLocation(this.id);" data-toggle="tooltip" title="View Location"><i class="fas fa-eye"></i></button>';
                $Action .= "<span>";
            } elseif ($Role == 3) {
                /*Manager*/
                if ($item->status == 1) {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeLocationStatus(this.checked, this.value);" checked disabled>';
                } else {
                    $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeLocationStatus(this.checked, this.value);" disabled>';
                }
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '" onclick="EditLocation(this.id);" data-toggle="tooltip" title="View Location"><i class="fas fa-eye"></i></button>';
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

    function getTotalLocationPlayers($locationId)
    {
        $TotalPlayers = 0;
        $TotalNormalPlayers = 0;
        $TotalGuessPlayers = 0;

        $Classes = DB::table("classes")
            ->where("location", $locationId)
            ->where('deleted_at', null)
            ->get();

        foreach ($Classes as $key => $value) {
            $NormalPlayers = DB::table('class_assigns')
                ->where('class_id', $value->id)
                ->where('type', 1)
                ->get();

            $GuessPlayers = DB::table('class_assigns')
                ->where('class_id', $value->id)
                ->where('type', 2)
                ->where('start_date', '<=', date('Y-m-d'))
                ->where('end_date', '>=', date('Y-m-d'))
                ->get();

            $TotalPlayers += count($NormalPlayers) + count($GuessPlayers);
        }
        return $TotalPlayers;
    }

    function add()
    {
        $page = "location";
        $Categories = DB::table('categories')
            ->where('deleted_at', '=', null)
            ->select('categories.id', 'categories.title')
            ->get();
        $Levels = DB::table('levels')
            ->where('deleted_at', '=', null)
            ->select('levels.id', 'levels.title')
            ->get();
        $States = DB::table('states')
            ->get();
        $Categories = json_encode($Categories);
        $Levels = json_encode($Levels);
        return view('dashboard.locations.add', compact('page', 'States', 'Categories', 'Levels'));
    }

    function store(Request $request)
    {
        $City = "";
        if ($request->post('state') != "") {
            // get city from zip code if city is not selected
            if ($request->post('city') != "") {
                $City = $request->post('city');
            } else {
                $City = SiteHelper::GetCityFromZipCode($request->post('zipcode'));
            }
        }

        DB::beginTransaction();
        $Affected = PlayerLocations::create([
            'name' => $request->post('name'),
            'street' => $request->post('street'),
            'city' => $City,
            'state' => $request->post('state'),
            'zipcode' => $request->post('zipcode'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if (isset($request['level']) && isset($request['category'])) {
            $Levels = json_decode($request->post('level'));
            $Categories = json_decode($request->post('category'));

            for ($i = 0; $i < count($Levels); $i++) {
                $_Level = $Levels[$i];
                $_Category = $Categories[$i];
                if ($_Level != "" && $_Category != "") {
                    $Affected1 = LocationLevel::create([
                        'location_id' => $Affected->id,
                        'level' => $_Level,
                        'category' => implode(',', $_Category),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }

        if ($Affected) {
            DB::commit();
            return redirect()->route('locations')->with('success', 'Location created successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('locations')->with('error', 'An unhandled error occurred');
        }
    }

    function delete(Request $request)
    {
        DB::beginTransaction();
        $Affected = DB::table('player_locations')
            ->where('id', '=', $request->post('id'))
            ->update([
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('locations')->with('success', 'Location deleted successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('locations')->with('error', 'An unhandled error occurred');
        }
    }

    function edit($LocationId)
    {
        $page = "location";
        $LocationId = base64_decode($LocationId);
        $LocationManager = SiteHelper::GetLocationManager($LocationId);
        $Location = DB::table('player_locations')
            ->where('id', '=', $LocationId)
            ->get();
        $LocationLevels = DB::table('location_levels')
            ->where('location_id', '=', $LocationId)
            ->get();
        $Categories = DB::table('categories')
            ->where('deleted_at', '=', null)
            ->select('categories.id', 'categories.title')
            ->get();
        $Levels = DB::table('levels')
            ->where('deleted_at', '=', null)
            ->select('levels.id', 'levels.title')
            ->get();
        $cities = DB::table('locations')
            ->where('state_name', '=', $Location[0]->state)
            ->orderBy("city", "ASC")
            ->get()
            ->unique("city");
        $States = DB::table('states')
            ->get();
        $_Levels = json_encode($Levels);
        $_Categories = json_encode($Categories);
        return view('dashboard.locations.edit', compact('page', 'LocationManager', 'States', 'Location', 'LocationLevels', 'cities', 'Levels', 'Categories'));
    }

    function update(Request $request)
    {
        $City = "";
        if ($request->post('state') != "") {
            // get city from zip code if city is not selected
            if ($request->post('city') != "") {
                $City = $request->post('city');
            } else {
                $City = SiteHelper::GetCityFromZipCode($request->post('zipcode'));
            }
        }

        DB::beginTransaction();
        $Affected = DB::table('player_locations')
            ->where('id', '=', $request->post('id'))
            ->update([
                'name' => $request->post('name'),
                'street' => $request->post('street'),
                'city' => $City,
                'state' => $request->post('state'),
                'zipcode' => $request->post('zipcode'),
                'status' => $request->has('status') ? $request->post('status') : 0,
                'updated_at' => Carbon::now()
            ]);

        // Delete old location levels
        DB::table('location_levels')->where('location_id', '=', $request->post('id'))->delete();

        if (isset($request['level']) && isset($request['category'])) {
            $Levels = json_decode($request->post('level'));
            $Categories = json_decode($request->post('category'));

            for ($i = 0; $i < count($Levels); $i++) {
                $_Level = $Levels[$i];
                $_Category = $Categories[$i];
                if ($_Level != "" && $_Category != "") {
                    $Affected1 = LocationLevel::create([
                        'location_id' => $request->post('id'),
                        'level' => $_Level,
                        'category' => implode(',', $_Category),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }

        if ($Affected) {
            DB::commit();
            return redirect()->route('locations')->with('success', 'Location updated successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('locations')->with('error', 'An unhandled error occurred');
        }
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
        $Affected = DB::table('player_locations')
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
}
