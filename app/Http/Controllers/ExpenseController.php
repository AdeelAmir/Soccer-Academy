<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function AdminAllExpense()
    {
        $page = "billing";
        $Role = Session::get('user_role');
        return view('dashboard.billing.expenses.index', compact('page', 'Role'));
    }

    public function LoadAdminAllExpense(Request $request)
    {
        $Role = Session::get('user_role');
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];
        $StartDate = $request->post('StartDate');
        $EndDate = $request->post('EndDate');

        $columnIndex = $request->post('order')[0]['column']; // Column index
        $columnName = $request->post('columns')[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->post('order')[0]['dir']; // asc or desc

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;

        if ($Role == 1 || $Role == 2) {
            if ($searchTerm == '') {
                $fetch_data = DB::table('expenses')
                    ->leftJoin('player_locations', 'expenses.location', '=', 'player_locations.id')
                    ->where('expenses.deleted_at', '=', null)
                    ->where(function ($query) use ($StartDate, $EndDate) {
                        if ($StartDate != "" && $EndDate != "") {
                            $query->whereBetween('expenses.expense_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                        }
                    })
                    ->select('expenses.*', 'player_locations.name AS LocationTitle')
                    ->orderBy('expenses.expense_date', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('expenses')
                    ->leftJoin('player_locations', 'expenses.location', '=', 'player_locations.id')
                    ->where('expenses.deleted_at', '=', null)
                    ->where(function ($query) use ($StartDate, $EndDate) {
                        if ($StartDate != "" && $EndDate != "") {
                            $query->whereBetween('expenses.expense_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                        }
                    })
                    ->select('expenses.*', 'player_locations.name AS LocationTitle')
                    ->orderBy('expenses.expense_date', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('expenses')
                    ->leftJoin('player_locations', 'expenses.location', '=', 'player_locations.id')
                    ->where(function ($query) {
                        $query->where([
                            ['expenses.deleted_at', '=', null]
                        ]);
                    })
                    ->where(function ($query) use ($StartDate, $EndDate) {
                        if ($StartDate != "" && $EndDate != "") {
                            $query->whereBetween('expenses.expense_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                        }
                    })
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('expenses.id', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.description', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.total', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.expense_date', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.vendor', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.location', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.currency', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.note', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('expenses.*', 'player_locations.name AS LocationTitle')
                    ->orderBy('expenses.expense_date', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('expenses')
                    ->leftJoin('player_locations', 'expenses.location', '=', 'player_locations.id')
                    ->where(function ($query) {
                        $query->where([
                            ['expenses.deleted_at', '=', null],
                        ]);
                    })
                    ->where(function ($query) use ($StartDate, $EndDate) {
                        if ($StartDate != "" && $EndDate != "") {
                            $query->whereBetween('expenses.expense_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                        }
                    })
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('expenses.id', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.description', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.total', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.expense_date', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.vendor', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.location', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.currency', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.note', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('expenses.*', 'player_locations.name AS LocationTitle')
                    ->orderBy('expenses.expense_date', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        } elseif ($Role == 3) {
            // Manager Section
            $ManagerLocations = SiteHelper::GetManagerLocation(Auth::id());
            if ($searchTerm == '') {
                $fetch_data = DB::table('expenses')
                    ->leftJoin('player_locations', 'expenses.location', '=', 'player_locations.id')
                    ->where('expenses.deleted_at', '=', null)
                    ->whereIn('expenses.location', $ManagerLocations)
                    ->where(function ($query) use ($StartDate, $EndDate) {
                        if ($StartDate != "" && $EndDate != "") {
                            $query->whereBetween('expenses.expense_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                        }
                    })
                    ->select('expenses.*', 'player_locations.name AS LocationTitle')
                    ->orderBy('expenses.expense_date', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('expenses')
                    ->leftJoin('player_locations', 'expenses.location', '=', 'player_locations.id')
                    ->where('expenses.deleted_at', '=', null)
                    ->whereIn('expenses.location', $ManagerLocations)
                    ->where(function ($query) use ($StartDate, $EndDate) {
                        if ($StartDate != "" && $EndDate != "") {
                            $query->whereBetween('expenses.expense_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                        }
                    })
                    ->select('expenses.*', 'player_locations.name AS LocationTitle')
                    ->orderBy('expenses.expense_date', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('expenses')
                    ->leftJoin('player_locations', 'expenses.location', '=', 'player_locations.id')
                    ->where(function ($query) {
                        $query->where([
                            ['expenses.deleted_at', '=', null]
                        ]);
                    })
                    ->whereIn('expenses.location', $ManagerLocations)
                    ->where(function ($query) use ($StartDate, $EndDate) {
                        if ($StartDate != "" && $EndDate != "") {
                            $query->whereBetween('expenses.expense_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                        }
                    })
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('expenses.id', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.description', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.total', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.expense_date', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.vendor', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.location', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.currency', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.note', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('expenses.*', 'player_locations.name AS LocationTitle')
                    ->orderBy('expenses.expense_date', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('expenses')
                    ->leftJoin('player_locations', 'expenses.location', '=', 'player_locations.id')
                    ->where(function ($query) {
                        $query->where([
                            ['expenses.deleted_at', '=', null],
                        ]);
                    })
                    ->whereIn('expenses.location', $ManagerLocations)
                    ->where(function ($query) use ($StartDate, $EndDate) {
                        if ($StartDate != "" && $EndDate != "") {
                            $query->whereBetween('expenses.expense_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                        }
                    })
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('expenses.id', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.description', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.total', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.expense_date', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.vendor', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.location', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.currency', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('expenses.note', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('expenses.*', 'player_locations.name AS LocationTitle')
                    ->orderBy('expenses.expense_date', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        }

        $data = array();
        $SrNo = $start + 1;
        $active_ban = "";
        foreach ($fetch_data as $row => $item) {
            $sub_array = array();
            $sub_array['id'] = $SrNo;
            if (strlen($item->description) > 15) {
                $sub_array['description'] = substr($item->description, 0, 15) . '...';
            } else {
                $sub_array['description'] = $item->description;
            }
            if ($item->currency == "USD") {
                $sub_array['total'] = "$" . $item->total;
            } else {
                $sub_array['total'] = $item->other_currency_name . $item->total;
            }
            $sub_array['expense_date'] = Carbon::parse($item->expense_date)->format("m/d/Y");
            $sub_array['vendor'] = wordwrap($item->vendor, 10, '<br>');
            if ($item->location == 0) {
                $sub_array['location'] = wordwrap("General", 10, '<br>');
            } else {
                $sub_array['location'] = wordwrap($item->LocationTitle, 10, '<br>');
            }
            if (strlen($item->note) > 15) {
                $sub_array['note'] = substr($item->note, 0, 15) . '...';
            } else {
                $sub_array['note'] = $item->note;
            }
            $Action = "";
            if($Role == 1) {
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="edit_' . $item->id . '" onclick="EditExpense(this.id);" data-toggle="tooltip" title="View Expense"><i class="fas fa-eye"></i></button>';
                $Action .= '<button class="btn btn-danger btn-sm" id="delete_' . $item->id . '" onclick="DeleteExpense(this.id);" data-toggle="tooltip" title="Delete Expense"><i class="fas fa-trash"></i></button>';
                $Action .= "<span>";
            } elseif($Role == 2 || $Role == 3) {
                $Action = "<span>";
                $Action .= '<button class="btn btn-primary btn-sm" id="edit_' . $item->id . '" onclick="EditExpense(this.id);" data-toggle="tooltip" title="View Expense"><i class="fas fa-eye"></i></button>';
                $Action .= "<span>";
            }
            $sub_array['action'] = $Action;
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

    public function AdminAddNewExpense()
    {
        $page = "billing";
        $Role = Session::get('user_role');
        $maxDate = Carbon::now()->subYears(15);
        $maxDate = $maxDate->toDateString();
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
        return view('dashboard.billing.expenses.add', compact('page', 'maxDate', 'Role', 'Locations'));
    }

    public function AdminExpenseStore(Request $request)
    {
        $UserRole = Session::get('user_role');
        $Description = $request['description'];
        $Total = $request['total'];
        $Date = $request['expenseDate'];
        $Vendor = $request['vendor'];
        $Location = $request['location'];
        $Currency = $request['currency'];
        $OtherCurrencyName = null;
        $Rate = null;
        $Notes = $request['notes'];

        if ($Currency == "Others") {
            $OtherCurrencyName = $request['other_currency_name'];
            $Rate = $request['rate'];
        }

        $Expense = Expense::create([
            'description' => $Description,
            'total' => $Total,
            'expense_date' => Carbon::parse($Date)->format('Y-m-d'),
            'vendor' => $Vendor,
            'location' => $Location,
            'currency' => $Currency,
            'other_currency_name' => $OtherCurrencyName,
            'exchange_rate' => $Rate,
            'note' => $Notes,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        if ($Expense) {
            return redirect()->route('billing.expenses')->with('success', 'Expense added successfully');
        } else {
            return redirect()->route('billing.expenses')->with('error', 'An unhandled error occurred');
        }
    }

    public function AdminEditExpense($id)
    {
        $page = "billing";
        $Role = Session::get('user_role');
        $expense_id = base64_decode($id);
        $expense_details = DB::table('expenses')
            ->where('expenses.id', '=', $expense_id)
            ->where('expenses.deleted_at', '=', null)
            ->get();
        $maxDate = Carbon::now()->subYears(15);
        $maxDate = $maxDate->toDateString();
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

        return view('dashboard.billing.expenses.edit', compact('page', 'expense_id', 'expense_details', 'maxDate', 'Role', 'Locations'));
    }

    public function AdminUpdateExpense(Request $request)
    {
        $Expense_id = $request['id'];
        $Description = $request['description'];
        $Total = $request['total'];
        $Date = $request['date'];
        $Vendor = $request['vendor'];
        $Location = $request['location'];
        $Currency = $request['currency'];
        $OtherCurrencyName = null;
        $Rate = null;
        $Notes = $request['notes'];

        if ($Currency == "Others") {
            $OtherCurrencyName = $request['other_currency_name'];
            $Rate = $request['rate'];
        }

        DB::beginTransaction();
        $affected = DB::table('expenses')
            ->where('id', $Expense_id)
            ->update([
                'description' => $Description,
                'total' => $Total,
                'expense_date' => Carbon::parse($Date)->format('Y-m-d'),
                'vendor' => $Vendor,
                'location' => $Location,
                'currency' => $Currency,
                'other_currency_name' => $OtherCurrencyName,
                'exchange_rate' => $Rate,
                'note' => $Notes,
                'updated_at' => Carbon::now()
            ]);

        if ($affected) {
            DB::commit();
            return redirect()->route('billing.expenses')->with('success', 'Expense updated successfully');
        } else {
            DB::rollBack();
            return redirect()->route('billing.expenses')->with('error', 'An unhandled error occurred');
        }
    }

    public function AdminDeleteExpense(Request $request)
    {
        $Expense_id = $request['id'];
        DB::beginTransaction();
        $affected = DB::table('expenses')
            ->where('id', $Expense_id)
            ->update([
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ]);
        if ($affected) {
            DB::commit();
            return redirect()->route('billing.expenses')->with('success', 'Expense deleted successfully');
        } else {
            DB::rollBack();
            return redirect()->route('billing.expenses')->with('error', 'An unhandled error occurred');
        }
    }

    // PARENT EXPENSES
    public function ParentExpense()
    {
        $page = "parent-expenses";
        $Role = Session::get('user_role');
        return view('dashboard.billing.expenses.parentExpense', compact('page', 'Role'));
    }
}
