<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $page = "configuration";
        return view('dashboard.configuration.category.index', compact('page'));
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
            $fetch_data = DB::table('categories')
                ->where('deleted_at', '=', null)
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('categories')
                ->where('deleted_at', '=', null)
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        } else {
            $fetch_data = DB::table('categories')
                ->where('deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('title', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('categories')
                ->where('deleted_at', '=', null)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('title', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        }

        $data = array();
        $SrNO = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $sub_array = array();
            $sub_array['id'] = $SrNO;
            $sub_array['title'] = wordwrap($item->title, 150, '<br>');
            $sub_array['symbol'] = $item->symbol;
            if ($item->start_age != "" && $item->end_age != "") {
                $sub_array['start_age'] = $item->start_age . ' - ' . $item->end_age;
            } else {
                $sub_array['start_age'] = "";
            }
            if ($item->status == 1) {
                $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeCategoryStatus(this.checked, this.value);" checked>';
            } else {
                $sub_array['status'] = '<input type="checkbox" class="iswitch iswitch-primary" value="' . $item->id . '" onchange="ChangeCategoryStatus(this.checked, this.value);">';
            }
            $Action = "<span>";
            $Action .= '<button class="btn btn-primary btn-sm" id="edit||' . $item->id . '||' . base64_encode($item->title) . '||'. base64_encode($item->symbol) . '||'. base64_encode($item->description) . '||'. base64_encode($item->start_age) . '||'. base64_encode($item->end_age) .'" onclick="EditCategory(this.id);" data-toggle="tooltip" title="View Category"><i class="fas fa-eye"></i></button>';
            $Action .= '<button class="btn btn-danger btn-sm" id="delete||' . $item->id . '||' . base64_encode($item->title) . '" onclick="DeleteCategory(this.id);" data-toggle="tooltip" title="Delete Category"><i class="fas fa-trash"></i></button>';
            $Action .= "<span>";
            $sub_array['action'] = $Action;
            $data[] = $sub_array;
            $SrNO++;
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
        $Affected = Categories::create([
            'title' => $request->post('addCategoryTitle'),
            'symbol' => $request->post('addCategorySymbol'),
            'start_age' => $request->post('addCategoryStartAge'),
            'end_age' => $request->post('addCategoryEndAge'),
            'description' => $request->post('addCategoryDescription'),
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('configuration.categories')->with('success', 'Category created successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('configuration.categories')->with('error', 'An unhandled error occurred');
        }
    }

    function delete(Request $request)
    {
        DB::beginTransaction();
        $Affected = DB::table('categories')
            ->where('id', '=', $request->post('id'))
            ->update([
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('configuration.categories')->with('success', 'Category deleted successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('configuration.categories')->with('error', 'An unhandled error occurred');
        }
    }

    function update(Request $request){
        DB::beginTransaction();
        $Affected = DB::table('categories')
            ->where('id', '=', $request->post('id'))
            ->update([
                'title' => $request->post('editCategoryTitle'),
                'symbol' => $request->post('editCategorySymbol'),
                'start_age' => $request->post('editCategoryStartAge'),
                'end_age' => $request->post('editCategoryEndAge'),
                'description' => $request->post('editCategoryDescription'),
                'updated_at' => Carbon::now()
            ]);
        if ($Affected) {
            DB::commit();
            return redirect()->route('configuration.categories')->with('success', 'Category updated successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('configuration.categories')->with('error', 'An unhandled error occurred');
        }
    }

    function updateStatus(Request $request){
        $Status = 0;
        if($request->post('Checked') == 'true'){
            $Status = 1;
        } else {
            $Status = 0;
        }
        DB::beginTransaction();
        $Affected = DB::table('categories')
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
