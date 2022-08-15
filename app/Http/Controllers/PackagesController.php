<?php

namespace App\Http\Controllers;

use App\Models\PackageFeeStructure;
use App\Models\PackageItems;
use App\Models\Packages;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;

class PackagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $page = "billing";
        $Role = Session::get('user_role');
        return view('dashboard.billing.packages.index', compact("page", "Role"));
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
            $fetch_data = DB::table('packages')
                ->where('packages.deleted_at', '=', null)
                ->select('packages.*')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('packages')
                ->where('packages.deleted_at', '=', null)
                ->select('packages.*')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        } else {
            $fetch_data = DB::table('packages')
                ->where('packages.deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('packages.title', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('packages.*')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('packages')
                ->where('packages.deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('packages.title', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('packages.*')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        }

        $data = array();
        $rNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $sub_array = array();
            $sub_array['created_at'] = $item->created_at;
            $sub_array['id'] = $rNo;
            $sub_array['title'] = $item->title;
            $sub_array['limit'] = $item->limit;
            $sub_array['invitation'] = $item->invitation;
            $sub_array['start_date'] = Carbon::parse($item->start_date)->format('m/d/Y');
            $sub_array['end_date'] = Carbon::parse($item->end_date)->format('m/d/Y');
            $Action = "";
            if ($Role == 1) {
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '" onclick="EditPackage(this.id);" data-toggle="tooltip" title="Edit Package"><i class="fas fa-eye"></i></button>';
                $Action .= '<button class="btn btn-danger btn-sm" id="delete||' . $item->id . '" onclick="DeletePackage(this.id);" data-toggle="tooltip" title="Delete Package"><i class="fas fa-trash"></i></button>';
                $Action .= "<span>";
            } elseif ($Role == 2 || $Role == 3) {
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '" onclick="EditPackage(this.id);" data-toggle="tooltip" title="Edit Package"><i class="fas fa-eye"></i></button>';
                $Action .= "<span>";
            }
            $sub_array['action'] = $Action;
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

    function add()
    {
        $page = "billing";
        $Role = Session::get("user_role");
        $Categories = DB::table('categories')
            ->where('deleted_at', '=', null)
            ->get();
        $Levels = DB::table('levels')
            ->where('deleted_at', '=', null)
            ->get();
        $Classes = DB::table('classes')
            ->where('deleted_at', '=', null)
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
        return view('dashboard.billing.packages.add', compact('page', 'Categories', 'Levels', 'Locations'));
    }

    function store(Request $request)
    {
        DB::beginTransaction();
        $Affected = Packages::create([
            'title' => $request->post('title'),
            'limit' => $request->post('limit'),
            'invitation' => $request->has('invitation') == true ? "Yes" : "No",
            'start_date' => Carbon::parse($request->post('startDate'))->format('Y-m-d'),
            'end_date' => Carbon::parse($request->post('endDate'))->format('Y-m-d'),
            'level' => $request->post('level'),
            'category' => implode(',', $request->post('category')),
            'location' => implode(",", $request->post('location')),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $PackageId = $Affected->id;

        if ($request['item'] != "") {
            foreach ($request['item'] as $index => $item) {
                $ItemTitle = $item['item'];
                $ItemPrice = $item['price'];
                if ($item['item'] != '' && $item['price'] != '') {
                    $Affected2 = PackageItems::create([
                        'package' => $PackageId,
                        'item' => $ItemTitle,
                        'type' => null,
                        'price' => $ItemPrice,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }

        foreach ($request->post('hiddenFeesType') as $index => $item) {
            PackageFeeStructure::create([
                'package' => $PackageId,
                'fee_Type' => $item,
                'registration_fee' => $request->post('registration_fee')[$index],
                'holding_fee' => $request->post('holding_fee')[$index],
                'late_payment_fee' => $request->post('late_payment_fee')[$index],
                'termination_fee' => $request->post('termination_fee')[$index],
                'reactivation_fee' => $request->post('reactivation_fee')[$index],
                'monthly_fee_1day' => $request->post('monthly_fee_1day')[$index],
                'monthly_fee_2day' => $request->post('monthly_fee_2day')[$index],
                'monthly_fee_3day' => $request->post('monthly_fee_3day')[$index],
                'monthly_fee_4day' => $request->post('monthly_fee_4day')[$index],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        if ($Affected) {
            DB::commit();
            return redirect()->route('packages')->with('success', 'Package created successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('packages')->with('error', 'An unhandled error occurred');
        }
    }

    function delete(Request $request)
    {
        DB::beginTransaction();
        $Affected = DB::table('packages')
            ->where('id', '=', $request->post('id'))
            ->update([
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('packages')->with('success', 'Package deleted successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('packages')->with('error', 'An unhandled error occurred');
        }
    }

    function edit($PackageId)
    {
        $page = "billing";
        $Role = Session::get("user_role");
        $PackageId = base64_decode($PackageId);
        $Package = DB::table('packages')
            ->where('id', '=', $PackageId)
            ->get();
        $Categories = DB::table('categories')
            ->where('deleted_at', '=', null)
            ->get();
        $Levels = DB::table('levels')
            ->where('deleted_at', '=', null)
            ->get();
        if (sizeof($Package) == 0) {
            return redirect()->route('packages');
        }
        $PackageItems = DB::table('package_items')
            ->where('package', '=', $PackageId)
            ->get();
        $PackageFeeStructures = DB::table('package_fee_structures')
            ->where('package', '=', $PackageId)
            ->get();
        $Classes = DB::table('classes')
            ->where('deleted_at', '=', null)
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
        return view('dashboard.billing.packages.edit', compact('page', 'Classes', 'Package', 'PackageItems', 'Categories', 'Levels', 'PackageFeeStructures', 'Locations'));
    }

    function update(Request $request)
    {
        $ClassId = null;
        if ($request->post('class') != '') {
            $ClassId = $request->post('class');
        }
        DB::beginTransaction();
        $Affected = DB::table('packages')
            ->where('id', '=', $request->post('id'))
            ->update([
                'title' => $request->post('title'),
                'limit' => $request->post('limit'),
                'invitation' => $request->has('invitation') == true ? "Yes" : "No",
                'start_date' => Carbon::parse($request->post('startDate'))->format('Y-m-d'),
                'end_date' => Carbon::parse($request->post('endDate'))->format('Y-m-d'),
                'level' => $request->post('level'),
                'category' => implode(',', $request->post('category')),
                'location' => implode(',', $request->post('location')),
                'updated_at' => Carbon::now()
            ]);
        DB::table('package_items')
            ->where('package', '=', $request->post('id'))
            ->delete();
        if ($request['item'] != "") {
            foreach ($request['item'] as $index => $item) {
                $ItemTitle = $item['item'];
                $ItemPrice = $item['price'];
                if ($item['item'] != '' && $item['price'] != '') {
                    $Affected2 = PackageItems::create([
                        'package' => $request->post('id'),
                        'item' => $ItemTitle,
                        'type' => null,
                        'price' => $ItemPrice,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }

        foreach ($request->post('hiddenFeesType') as $index => $item) {
            DB::table('package_fee_structures')
                ->where('package', '=', $request->post('id'))
                ->where('fee_Type', '=', $item)
                ->update([
                    'registration_fee' => $request->post('registration_fee')[$index],
                    'holding_fee' => $request->post('holding_fee')[$index],
                    'late_payment_fee' => $request->post('late_payment_fee')[$index],
                    'termination_fee' => $request->post('termination_fee')[$index],
                    'reactivation_fee' => $request->post('reactivation_fee')[$index],
                    'monthly_fee_1day' => $request->post('monthly_fee_1day')[$index],
                    'monthly_fee_2day' => $request->post('monthly_fee_2day')[$index],
                    'monthly_fee_3day' => $request->post('monthly_fee_3day')[$index],
                    'monthly_fee_4day' => $request->post('monthly_fee_4day')[$index],
                    'updated_at' => Carbon::now()
                ]);
        }

        if ($Affected) {
            DB::commit();
            return redirect()->route('packages')->with('success', 'Package updated successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('packages')->with('error', 'An unhandled error occurred');
        }
    }
}
