<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $page = "configuration";
        return view('dashboard.configuration.level.index', compact('page'));
    }

    function load(Request $request)
    {
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
            $fetch_data = DB::table('levels')
                ->where('deleted_at', '=', null)
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('levels')
                ->where('deleted_at', '=', null)
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        } else {
            $fetch_data = DB::table('levels')
                ->where('deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('title', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('price', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('levels')
                ->where('deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('title', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('price', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        }

        $data = array();
        $rNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $sub_array = array();
            $sub_array['id'] = $rNo;
            $sub_array['title'] = wordwrap($item->title, 50, '<br>');
            $sub_array['symbol'] = $item->symbol;
            $Action = "<span>";
            $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '||' . base64_encode($item->title) . '||' . base64_encode($item->price) . '||' . base64_encode($item->description) . '||' . base64_encode($item->symbol) . '" onclick="EditLevel(this.id);" data-toggle="tooltip" title="View Level"><i class="fas fa-eye"></i></button>';
            $Action .= '<button class="btn btn-danger btn-sm" id="delete||' . $item->id . '||' . base64_encode($item->title) . '" onclick="DeleteLevel(this.id);" data-toggle="tooltip" title="Delete Level"><i class="fas fa-trash"></i></button>';
            $Action .= "<span>";
            $sub_array['action'] = $Action;
            $rNo++;
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

    function store(Request $request)
    {
        DB::beginTransaction();
        $Affected = Level::create([
            'title' => $request->post('addLevelTitle'),
            'price' => $request->post('addLevelPrice'),
            'description' => $request->post('addLevelDescription'),
            'symbol' => $request->post('addLevelSymbol'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('configuration.level')->with('success', 'Level created successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('configuration.level')->with('error', 'An unhandled error occurred');
        }
    }

    function delete(Request $request)
    {
        DB::beginTransaction();
        $Affected = DB::table('levels')
            ->where('id', '=', $request->post('id'))
            ->update([
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('configuration.level')->with('success', 'Level deleted successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('configuration.level')->with('error', 'An unhandled error occurred');
        }
    }

    function update(Request $request){
        DB::beginTransaction();
        $Affected = DB::table('levels')
            ->where('id', '=', $request->post('id'))
            ->update([
                'title' => $request->post('editLevelTitle'),
                'price' => $request->post('editLevelPrice'),
                'description' => $request->post('editLevelDescription'),
                'symbol' => $request->post('editLevelSymbol'),
                'updated_at' => Carbon::now()
            ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('configuration.level')->with('success', 'Level updated successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('configuration.level')->with('error', 'An unhandled error occurred');
        }
    }
}
