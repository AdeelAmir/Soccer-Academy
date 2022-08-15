<?php

namespace App\Http\Controllers;

use App\Models\PlayerPositions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerPositionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $page = "configuration";
        return view('dashboard.configuration.position.index', compact('page'));
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
            $fetch_data = DB::table('player_positions')
                ->where('deleted_at', '=', null)
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('player_positions')
                ->where('deleted_at', '=', null)
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        } else {
            $fetch_data = DB::table('player_positions')
                ->where('deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('title', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('player_positions')
                ->where('deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('title', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $sub_array = array();
            $sub_array['id'] = $SrNo;
            $sub_array['title'] = $item->title;
            $sub_array['symbol'] = $item->symbol;
            $Action = "<span>";
            $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '||' . base64_encode($item->title) . '||' . base64_encode($item->symbol) . '||'. base64_encode($item->description) .'" onclick="EditPosition(this.id);" data-toggle="tooltip" title="View Position"><i class="fas fa-eye"></i></button>';
            $Action .= '<button class="btn btn-danger btn-sm" id="delete||' . $item->id . '||' . base64_encode($item->title) . '" onclick="DeletePosition(this.id);" data-toggle="tooltip" title="Delete Position"><i class="fas fa-trash"></i></button>';
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

    function store(Request $request)
    {
        DB::beginTransaction();
        $Affected = PlayerPositions::create([
            'title' => $request->post('addPositionTitle'),
            'symbol' => $request->post('addPositionSymbol'),
            'description' => $request->post('addPositionDescription'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('configuration.player-position')->with('success', 'Player Position created successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('configuration.player-position')->with('error', 'An unhandled error occurred');
        }
    }

    function delete(Request $request)
    {
        DB::beginTransaction();
        $Affected = DB::table('player_positions')
            ->where('id', '=', $request->post('id'))
            ->update([
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('configuration.player-position')->with('success', 'Player Position deleted successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('configuration.player-position')->with('error', 'An unhandled error occurred');
        }
    }

    function update(Request $request){
        DB::beginTransaction();
        $Affected = DB::table('player_positions')
            ->where('id', '=', $request->post('id'))
            ->update([
                'title' => $request->post('editPositionTitle'),
                'symbol' => $request->post('editPositionSymbol'),
                'description' => $request->post('editPositionDescription'),
                'updated_at' => Carbon::now()
            ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('configuration.player-position')->with('success', 'Player Position updated successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('configuration.player-position')->with('error', 'An unhandled error occurred');
        }
    }
}
