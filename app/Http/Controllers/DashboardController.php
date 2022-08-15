<?php

namespace App\Http\Controllers;

use App\Helpers\SiteHelper;
use App\Models\Coupons;
use App\Models\OrderInvoices;
use App\Models\Orders;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\StripeClient;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $page = "dashboard";
        $Role = Session::get("user_role");
        $FirstDateofYear = date('Y-m-d', strtotime('first day of january this year'));
        $LastDateofYear = date('Y-m-d', strtotime('last day of december this year'));

        // Announcement
        $Announcement = DB::table('announcements')
            ->where('announcements.deleted_at', '=', null)
            ->where('announcements.type', '=', 2)
            ->where('announcements.status', '=', 1)
            ->select('announcements.*')
            ->get();

        if ($Role == 1 || $Role == 2 || $Role == 3) {
            // Total Coaches
            $TotalCoaches = DB::table('users')
                ->where('users.deleted_at', '=', null)
                ->where('users.role_id', '=', 4)
                ->where('users.status', '=', 1)
                ->select('users.id')
                ->count();
            // Total Parents
            $TotalParents = DB::table('users')
                ->where('users.deleted_at', '=', null)
                ->where('users.role_id', '=', 5)
                ->where('users.status', '=', 1)
                ->select('users.id')
                ->count();
            // Total Athletes
            $TotalAthletes = DB::table('users')
                ->where('users.deleted_at', '=', null)
                ->where('users.role_id', '=', 6)
                ->where('users.status', '=', 1)
                ->select('users.id')
                ->count();
            // Hold
            $TotalHold = DB::table('transactions')
                ->where(function ($query) {
                    $query->orWhere('comments', '=', 'Holding Fee');
                    $query->orWhere('comments', '=', 'Holding and late Payment Fee');
                })
                ->where('status', '=', 2)
                ->sum('amount_paid');
            // Cancel
            $TotalCancel = DB::table('transactions')
                ->where('status', 3)
                ->sum('total_amount');
            $TotalDueFee = DB::table('transactions')
                ->where('status', 1)
                ->sum('total_amount');

            /* Finance (Earning,Expense) - Donut Graph - Start */
            $Data = $this->CalculateEarningExpenseGraphValues();
            $FinanceTypes = $Data[0];
            $FinanceAmounts = $Data[1];
            /* Finance (Earning,Expense) - Donut Graph - End */

            /* Current month earnings calculation - Line Graph - Start */
            $TotalEarnings = 0;
            $FirstDayOfThisMonth = Carbon::parse("first day of this month");
            $Data = $this->CalculateEarningGraphValues($FirstDayOfThisMonth);
            $MembershipAmounts = $Data[0];
            $InvoicesAmounts = $Data[1];
            foreach ($MembershipAmounts as $key => $value) {
                $TotalEarnings += $value;
            }
            foreach ($InvoicesAmounts as $key => $value) {
                $TotalEarnings += $value;
            }

            /* Current month earnings calculation - Line Graph - End */

            /* Current year expenses calculation - Bar Graph - Start */
            $TotalExpenses = 0;
            $Data = $this->CalculateExpenseGraphValues();
            $ExpenseAmounts = $Data[0];
            foreach ($ExpenseAmounts as $key => $value) {
                $TotalExpenses += $value;
            }
            /* Current year expenses calculation - Bar Graph - End */

            /* Player category - Donut Graph - Start */
            $Data = $this->CalculatePlayerCategoryGraphValues();
            $CategoryAmounts = $Data[0];
            $CategoryPlayerAmounts = $Data[1];
            /* Player category - Donut Graph - End */

            /*For Managers*/
            if ($Role == 3) {
                $ManagerLocations = SiteHelper::GetManagerLocation(Auth::id());
                $ManagerClasses = DB::table('classes')
                    ->where('classes.deleted_at', '=', null)
                    ->whereIn('classes.location', $ManagerLocations)
                    ->get();
                $class_array = array();
                foreach ($ManagerClasses as $key => $value) {
                    array_push($class_array, $value->id);
                }

                // Total Students
                $TotalAthletes = DB::table('class_assigns')
                    ->whereIn('class_assigns.class_id', $class_array)
                    ->count();

                // Total Parents
                $_TotalPlayers = DB::table('class_assigns')
                    ->whereIn('class_assigns.class_id', $class_array)
                    ->select('player_id')
                    ->distinct('player_id')
                    ->get();
                $player_array = array();
                foreach ($_TotalPlayers as $key => $value) {
                    array_push($player_array, $value->player_id);
                }
                $TotalParents = DB::table('users')
                    ->where('users.deleted_at', '=', null)
                    ->where('users.status', '=', 1)
                    ->whereIn('users.id', $player_array)
                    ->distinct('users.parent_id')
                    ->count();

                // Total Coaches
                $TotalCoaches = DB::table('users')
                    ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
                    ->where('users.deleted_at', '=', null)
                    ->where('users.role_id', '=', 4)
                    ->where('users.status', '=', 1)
                    ->where(function ($query) use ($ManagerLocations) {
                        if(sizeof($ManagerLocations) > 0) {
                            foreach ($ManagerLocations as $managerLocation) {
                                $query->orWhereRaw('FIND_IN_SET(?, user_details.coachLocations) > 0', array($managerLocation));
                            }
                        }
                    })
                    ->select('users.id')
                    ->count();
            }

            return view('dashboard.index', compact('page', 'Role', 'Announcement', 'TotalCoaches', 'TotalParents', 'TotalAthletes', 'TotalHold', 'TotalCancel', 'TotalDueFee', 'FinanceTypes', 'FinanceAmounts', 'TotalEarnings', 'MembershipAmounts', 'InvoicesAmounts', 'TotalExpenses', 'ExpenseAmounts', 'CategoryAmounts', 'CategoryPlayerAmounts'));
        } elseif ($Role == 4) {
            // ========= COACH DASHBOARD =========
            $Coach_TotalStudents = 0;
            $Coach_TotalReports = 0;
            $Coach_TotalNewPlayers = 0;
            $Coach_TotalLocation = 0;
            $class_array = array();
            $Classes = DB::table('classes')
                ->where('classes.deleted_at', '=', null)
                ->where('classes.coach', '=', Auth::id())
                ->select('classes.id')
                ->get();
            foreach ($Classes as $key => $value) {
                array_push($class_array, $value->id);
            }
            // Total Students
            $Coach_TotalStudents = DB::table('class_assigns')
                ->whereIn('class_assigns.class_id', $class_array)
                ->count();
            // Coach Total New Players
            $Coach_TotalNewPlayers = DB::table('class_assigns')
                ->whereIn('class_assigns.class_id', $class_array)
                ->count();
            // Total Reports
            $Coach_TotalReports = DB::table('user_evaluations')
                ->where('user_evaluations.created_by', '=', Auth::id())
                ->count();
            // Total Location
            $CoachDetails = DB::table('user_details')
                ->where('user_id', '=', Auth::id())
                ->get();

            $CoachLocation = $CoachDetails[0]->coachLocations;
            if ($CoachLocation != "") {
                $CoachLocation = explode(",", $CoachLocation);
                $Coach_TotalLocation = count($CoachLocation);
            }

            // Categories Donut Chart
            $category_list = array();
            $category_player_list = array();

            $categories = DB::table("categories")
                ->where('deleted_at', null)
                ->get();

            foreach ($categories as $key => $value) {
                array_push($category_list, $value->title);
                $TotalCategoryPlayers = DB::table('users')
                    ->join('user_details', 'users.id', '=', 'user_details.user_id')
                    ->where('users.role_id', 6)
                    ->where('users.status', 1)
                    ->where('users.deleted_at', null)
                    ->where('user_details.athletesCategory', $value->id)
                    ->select('users.id')
                    ->count();
                array_push($category_player_list, $TotalCategoryPlayers);
            }
            $category_list = json_encode($category_list);
            $category_player_list = json_encode($category_player_list);

            return view('dashboard.index', compact('page', 'Role', 'Announcement', 'Coach_TotalStudents', 'Coach_TotalReports', 'Coach_TotalNewPlayers', 'Coach_TotalLocation', 'category_list', 'category_player_list'));
        } elseif ($Role == 5) {
            // ========= PARENTS DASHBOARD =========
            $Parent_DueFees = 0;
            $Parent_Notifications = 0;
            $Parent_Evaluations = 0;
            $Parent_Expenses = 0;

            // get parents
            $ParentArray = array();
            $ChildrenArray = array();
            array_push($ParentArray, Auth::id());

            if (auth::user()->parent_id != 1) {
                $OtherParents = DB::table("users")->where('id', Auth::user()->parent_id)->where('role_id', 5)->get();
                foreach ($OtherParents as $key => $value) {
                    array_push($ParentArray, $value->id);
                }
            }

            $OtherParents = DB::table("users")->where('parent_id', Auth::id())->where('role_id', 5)->get();
            foreach ($OtherParents as $key => $value) {
                array_push($ParentArray, $value->id);
            }

            // get total children
            foreach ($ParentArray as $key => $value) {
                $Children = DB::table("users")->where('parent_id', $value)->where('role_id', 6)->get();
                foreach ($Children as $index => $child) {
                    array_push($ChildrenArray, $child->id);
                }
            }

            // Due Fees (Sum of due invoices and membership fee)
            $Parent_DueFees = DB::table("transactions")->whereIn('bill_to', $ParentArray)->where('status', 1)->sum('amount_paid');

            // Total Evaluation Reports Results
            $Parent_Evaluations = DB::table("user_evaluations")->whereIn('user_id', $ChildrenArray)->count();

            // Total Expenses
            $Parent_Expenses = DB::table("transactions")->whereIn('bill_to', $ParentArray)->where('status', 2)->sum('amount_paid');

            // MY Kids
            $MyKids = DB::table("users")
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->whereIn('users.id', $ChildrenArray)
                ->select('users.id', 'users.userId', 'users.created_at', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.gender', 'user_details.athletesTrainingDays', 'user_details.profile_pic')
                ->get();

            return view('dashboard.index', compact('page', 'Role', 'Announcement', 'Parent_DueFees', 'Parent_Notifications', 'Parent_Evaluations', 'Parent_Expenses', 'MyKids'));
        } elseif ($Role == 6) {
            // ========= PLAYER DASHBOARD =========
            $PlayerProfile = DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->leftJoin('levels', 'user_details.athletesLevel', '=', 'levels.id')
                ->leftJoin('categories', 'user_details.athletesCategory', '=', 'categories.id')
                ->leftJoin('player_positions', 'user_details.athletesPosition', '=', 'player_positions.id')
                ->where('users.id', Auth::id())
                ->select('users.userId', 'user_details.*', 'levels.title AS PlayerLevel', 'categories.title AS PlayerCategory', 'player_positions.title AS PlayerPosition')
                ->get();

            // Total Evaluation Reports Results
            $Evaluations = DB::table("user_evaluations")->where('user_id', '=', Auth::id())->count();
            // Attendance Percent
            $TotalAttendedDays = DB::table("attendences")
                ->where('player_id', '=', Auth::id())
                ->count();
            $TotalPresent = DB::table("attendences")
                ->where('player_id', '=', Auth::id())
                ->where('status', '=', 'P')
                ->count();
            $TotalLate = DB::table("attendences")
                ->where('player_id', '=', Auth::id())
                ->where('status', '=', 'L')
                ->count();
            $TotalAbsent = DB::table("attendences")
                ->where('player_id', '=', Auth::id())
                ->where('status', '=', 'A')
                ->count();
            $AttendancePercent = 0;
            if($TotalAttendedDays != 0) {
                $AttendancePercent = round(($TotalPresent + $TotalLate / $TotalAttendedDays) * 100, 2);
            }
            return view('dashboard.index', compact('page', 'Role', 'Announcement', 'PlayerProfile', 'Evaluations', 'AttendancePercent', 'TotalAttendedDays', 'TotalPresent', 'TotalLate', 'TotalAbsent'));
        }
    }

    /* Training Room - Start */
    function TraineeDashboard()
    {
        $TrainingAssignment = DB::table('training_assignment_folders')
            ->join('folders', 'training_assignment_folders.folder_id', '=', 'folders.id')
            ->where('training_assignment_folders.user_id', '=', Auth::id())
            ->where('training_assignment_folders.completion_rate', '<', 100)
            ->where('folders.required', '=', 1)
            ->count();

        if ($TrainingAssignment > 0) {
            return redirect(url('/training'));
        } else {
            return $this->index();
        }
    }

    function Training()
    {
        $page = "training_room";
        $Role = Session::get('user_role');

        // Calculate user training room progress and update the completion rate
        $TotalAssignments = 0;
        $TotalCompleted = 0;
        $TrainingRoomFolder = DB::table('training_assignment_folders')
            ->where('user_id', Auth::id())
            ->where('deleted_at', null)
            ->get();

        foreach ($TrainingRoomFolder as $key => $folder) {
            $TotalTrainingAssignments = DB::table('training_assignments')
                ->where('user_id', Auth::id())
                ->where('training_assignment_folder_id', $folder->id)
                ->count();
            $TotalCompletedAssignments = DB::table('training_assignments')
                ->where('user_id', Auth::id())
                ->where('training_assignment_folder_id', $folder->id)
                ->where('status', 1)
                ->count();
            $CompletionRate = 0;
            if ($TotalTrainingAssignments > 0) {
                $CompletionRate = (($TotalCompletedAssignments / $TotalTrainingAssignments) * 100);
            }

            // Update completing rate
            DB::table('training_assignment_folders')
                ->where('user_id', Auth::id())
                ->where('id', $folder->id)
                ->update([
                    'completion_rate' => $CompletionRate,
                    'updated_at' => Carbon::now()
                ]);
        }
        return view('dashboard.training-room.training-folder', compact('page', 'Role'));
    }

    function TrainingCourse($CourseId)
    {
        $page = "training_room";
        $Role = Session::get('user_role');

        // Course Details
        $CourseDetails = DB::table('training_assignment_folders')
            ->join('folders', 'training_assignment_folders.folder_id', '=', 'folders.id')
            ->where('training_assignment_folders.id', '=', $CourseId)
            ->select('training_assignment_folders.*', 'folders.name AS FolderName', 'folders.picture', 'folders.required')
            ->get();

        $CourseName = $CourseDetails[0]->FolderName;
        $CourseCompletionRate = $CourseDetails[0]->completion_rate;

        return view('dashboard.training-room.training', compact('page', 'Role', 'CourseId', 'CourseName', 'CourseCompletionRate'));
    }
    /* Training Room - End */

    /* Earnings calculation - Start */
    function LoadEarningLineGraphData(Request $request)
    {
        $TotalEarnings = 0;
        $Type = $request->post('Type');
        $StartDateOFMonth = Carbon::parse($Type)->startOfMonth();
        $Data = $this->CalculateEarningGraphValues($StartDateOFMonth);
        $MembershipAmounts = $Data[0];
        $InvoicesAmounts = $Data[1];
        foreach ($MembershipAmounts as $key => $value) {
            $TotalEarnings += $value;
        }
        foreach ($InvoicesAmounts as $key => $value) {
            $TotalEarnings += $value;
        }
        echo json_encode(array($MembershipAmounts, $InvoicesAmounts, $TotalEarnings));
        exit();
    }

    function CalculateEarningGraphValues($FirstDayOfMonth)
    {
        $Data = array();
        $MembershipAmounts = array();
        $InvoicesAmounts = array();
        // 1 - 5
        $One = Carbon::parse($FirstDayOfMonth);
        $Five = Carbon::parse($FirstDayOfMonth)->addDays(4);
        $MembershipAmounts[] = $this->CalculateMembershipAmount($One, $Five);
        $InvoicesAmounts[] = $this->CalculateInvoicesAmount($One, $Five);
        // 6 - 10
        $Six = Carbon::parse($Five)->addDays(1);
        $Ten = Carbon::parse($Six)->addDays(4);
        $MembershipAmounts[] = $this->CalculateMembershipAmount($Six, $Ten);
        $InvoicesAmounts[] = $this->CalculateInvoicesAmount($Six, $Ten);
        // 11 - 15
        $Eleven = Carbon::parse($Ten)->addDays(1);
        $Fifteen = Carbon::parse($Eleven)->addDays(4);
        $MembershipAmounts[] = $this->CalculateMembershipAmount($Eleven, $Fifteen);
        $InvoicesAmounts[] = $this->CalculateInvoicesAmount($Eleven, $Fifteen);
        // 16 - 20
        $Sixteen = Carbon::parse($Fifteen)->addDays(1);
        $Twenty = Carbon::parse($Sixteen)->addDays(4);
        $MembershipAmounts[] = $this->CalculateMembershipAmount($Sixteen, $Twenty);
        $InvoicesAmounts[] = $this->CalculateInvoicesAmount($Sixteen, $Twenty);
        // 21 - 25
        $TwentyOne = Carbon::parse($Twenty)->addDays(1);
        $TwentyFive = Carbon::parse($TwentyOne)->addDays(4);
        $MembershipAmounts[] = $this->CalculateMembershipAmount($TwentyOne, $TwentyFive);
        $InvoicesAmounts[] = $this->CalculateInvoicesAmount($TwentyOne, $TwentyFive);
        // 26 - Last
        $TwentySix = Carbon::parse($TwentyFive)->addDays(1);
        $LastDay = Carbon::parse($TwentyFive)->lastOfMonth();
        $MembershipAmounts[] = $this->CalculateMembershipAmount($TwentySix, $LastDay);
        $InvoicesAmounts[] = $this->CalculateInvoicesAmount($TwentySix, $LastDay);
        $Data[] = $MembershipAmounts;
        $Data[] = $InvoicesAmounts;
        return $Data;
    }

    function CalculateMembershipAmount(Carbon $Start, Carbon $End)
    {
        $Membership = DB::table('transactions')
            ->where('status', 2)
            ->where('order_id', '!=', null)
            ->whereBetween('created_at', [$Start->format("Y-m-d") . ' 00:00:00', $End->format("Y-m-d") . ' 23:59:59'])
            ->sum('amount_paid');
        return round($Membership);
    }

    function CalculateInvoicesAmount(Carbon $Start, Carbon $End)
    {
        $Invoices = DB::table('transactions')
            ->where('status', 2)
            ->where('invoice_id', '!=', null)
            ->whereBetween('created_at', [$Start->format("Y-m-d") . ' 00:00:00', $End->format("Y-m-d") . ' 23:59:59'])
            ->sum('amount_paid');
        return round($Invoices);
    }

    function CalculateEarningAmount(Carbon $Start, Carbon $End)
    {
        $Earnings = DB::table('transactions')
            ->where('status', 2)
            ->whereBetween('created_at', [$Start->format("Y-m-d") . ' 00:00:00', $End->format("Y-m-d") . ' 23:59:59'])
            ->sum('amount_paid');

        return round($Earnings);
    }

    /* Earnings calculation - End */

    /* Expenses calculation - Start */
    function CalculateExpenseGraphValues()
    {
        $Data = array();
        $ExpensesAmounts = array();
        // January
        $StartDateOFMonth = Carbon::parse("January")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("January")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // February
        $StartDateOFMonth = Carbon::parse("February")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("February")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // March
        $StartDateOFMonth = Carbon::parse("March")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("March")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // April
        $StartDateOFMonth = Carbon::parse("April")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("April")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // May
        $StartDateOFMonth = Carbon::parse("May")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("May")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // June
        $StartDateOFMonth = Carbon::parse("June")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("June")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // July
        $StartDateOFMonth = Carbon::parse("July")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("July")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // August
        $StartDateOFMonth = Carbon::parse("August")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("August")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // September
        $StartDateOFMonth = Carbon::parse("September")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("September")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // October
        $StartDateOFMonth = Carbon::parse("October")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("October")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // November
        $StartDateOFMonth = Carbon::parse("November")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("November")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        // December
        $StartDateOFMonth = Carbon::parse("December")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("December")->endOfMonth();
        $ExpensesAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);

        $Data[] = $ExpensesAmounts;
        return $Data;
    }

    function CalculateExpensesAmount(Carbon $Start, Carbon $End)
    {
        $ManagerLocations = array();
        if(Auth::user()->role_id == 3) {
            $ManagerLocations = SiteHelper::GetManagerLocation(Auth::id());
        }
        $Expense = DB::table('expenses')
            ->where('deleted_at', '=', null)
            ->where(function ($query) use ($ManagerLocations) {
                if(sizeof($ManagerLocations) > 0) {
                    $query->whereIn('expenses.location', $ManagerLocations);
                }
            })
            ->whereBetween('expenses.expense_date', [$Start->format("Y-m-d") . ' 00:00:00', $End->format("Y-m-d") . ' 23:59:59'])
            ->sum('expenses.total');
        return round($Expense);
    }
    /* Expenses calculation - End */

    /* Player category calculation - Start */
    function CalculatePlayerCategoryGraphValues()
    {
        $Data = array();
        $CategoryPlayers = array();
        $Categories = array();

        // Categories
        $CategoryList = DB::table('categories')
            ->where('deleted_at', null)
            ->get();

        // Players
        $Players = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 6)
            ->where('users.status', '=', 1)
            ->select('user_details.athletesCategory')
            ->get();

        if (Auth::user()->role_id == 3) {
            $ManagerLocations = SiteHelper::GetManagerLocation(Auth::id());
            $ManagerClasses = DB::table('classes')
                ->where('classes.deleted_at', '=', null)
                ->whereIn('classes.location', $ManagerLocations)
                ->get();
            $class_array = array();
            foreach ($ManagerClasses as $key => $value) {
                array_push($class_array, $value->id);
            }

            $_TotalPlayers = DB::table('class_assigns')
                ->whereIn('class_assigns.class_id', $class_array)
                ->select('player_id')
                ->distinct('player_id')
                ->get();
            $player_array = array();
            foreach ($_TotalPlayers as $key => $value) {
                array_push($player_array, $value->player_id);
            }

            // Players
            $Players = DB::table('users')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('users.deleted_at', '=', null)
                ->where('users.role_id', '=', 6)
                ->where('users.status', '=', 1)
                ->whereIn('users.id', $player_array)
                ->select('user_details.athletesCategory')
                ->get();
        }

        foreach ($CategoryList as $key => $category) {
            $Categories[] = $category->title;
            $TotalPlayers = 0;
            foreach ($Players as $player) {
                if ($player->athletesCategory == $category->id) {
                    $TotalPlayers++;
                }
            }
            $CategoryPlayers[] = $TotalPlayers;
        }

        $Data[] = $Categories;
        $Data[] = $CategoryPlayers;
        return $Data;
    }
    /* Player category calculation - End */

    /* Earning,Expense Pie chart calculation - Start */
    function CalculateEarningExpenseGraphValues()
    {
        $Data = array();
        $FinanceTypes = array("Earning", "Expense");
        $FinanceAmounts = array();
        $StartDateOFMonth = Carbon::parse("January")->startOfMonth();
        $EndDateOFMonth = Carbon::parse("December")->endOfMonth();

        // Earning of current year
        $FinanceAmounts[] = $this->CalculateEarningAmount($StartDateOFMonth, $EndDateOFMonth);
        // Expense of current year
        $FinanceAmounts[] = $this->CalculateExpensesAmount($StartDateOFMonth, $EndDateOFMonth);
        $Data[] = $FinanceTypes;
        $Data[] = $FinanceAmounts;
        return $Data;
    }

    /* Earning,Expense Pie chart calculation - End */

    function configuration()
    {
        $page = "configuration";
        return view('dashboard.configuration.index', compact('page'));
    }

    function billing()
    {
        $page = "billing";
        return view('dashboard.billing.index', compact('page'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

    public function profile()
    {
        $page = "";
        $id = Auth::user()->id;
        $UserDetails = DB::table('user_details')->where('user_id', '=', $id)->get();
        return view('dashboard.profile.index', compact('page', 'UserDetails'));
    }

    public function update(Request $request)
    {
        $FirstName = $request['firstName'];
        $MiddleName = $request['middleName'];
        $LastName = $request['lastName'];
        $Dob = $request['dob'];
        $Gender = $request['gender'];
        $UserId = Auth::user()->id;
        $ProfilePic = "";
        if (isset($request['profile_pic'])) {
            if ($request['old_profile_pic'] != "") {
                $path = public_path() . "/storage/user-profiles/" . $request['old_profile_pic'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $CurrentFile = $request['profile_pic'];
            $FileStoragePath = '/public/user-profiles/';
            $Extension = $CurrentFile->extension();
            $file = $CurrentFile->getClientOriginalName();
            $FileName = pathinfo($file, PATHINFO_FILENAME);
            $FileName = $FileName . '-' . date('Y-m-d') . mt_rand(100, 1000) . '.' . $Extension;
            $result = $CurrentFile->storeAs($FileStoragePath, $FileName);
            $ProfilePic = $FileName;
        } else {
            $ProfilePic = $request['profile_pic'];
        }

        DB::beginTransaction();
        $affected = DB::table('user_details')
            ->where('user_id', $UserId)
            ->update([
                'firstName' => $FirstName,
                'middleName' => $MiddleName,
                'lastName' => $LastName,
                'dob' => $Dob,
                'gender' => $Gender,
                'profile_pic' => $ProfilePic,
                'updated_at' => Carbon::now(),
            ]);

        DB::commit();
        return redirect()->route('dashboard.profile')->with('success', 'Data has been updated successfully');
        // if ($affected) {
        //     DB::commit();
        //     return redirect()->route('dashboard.profile')->with('success', 'Data has been updated successfully');
        // } else {
        //     DB::rollback();
        //     return redirect()->route('dashboard.profile')->with('error', 'Error! An unhandled exception occurred');
        // }
    }

    // PARENT DASHBOARD TABLE
    public function LoadParentExpenses(Request $request)
    {
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

        if ($searchTerm == '') {
            $fetch_data = DB::table('transactions')
                ->leftJoin('invoices', 'transactions.invoice_id', '=', 'invoices.id')
                ->leftJoin('orders', 'transactions.order_id', '=', 'orders.id')
                ->where('transactions.bill_to', Auth::id())
                ->whereIn('transactions.status', array(1, 2))
                ->where(function ($query) use ($StartDate, $EndDate) {
                    if ($StartDate != "" && $EndDate != "") {
                        $query->whereBetween('transactions.paid_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                    }
                })
                ->select('transactions.amount_paid', 'transactions.paid_date', 'transactions.status', 'transactions.type', 'transactions.total_amount', 'transactions.amount_paid', 'invoices.invoice_no', 'invoices.title AS InvoiceTitle', 'orders.order_id', 'orders.package_type AS OrderPackageType')
                ->orderBy('transactions.id', 'DESC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('transactions')
                ->leftJoin('invoices', 'transactions.invoice_id', '=', 'invoices.id')
                ->leftJoin('orders', 'transactions.order_id', '=', 'orders.id')
                ->where('transactions.bill_to', Auth::id())
                ->whereIn('transactions.status', array(1, 2))
                ->where(function ($query) use ($StartDate, $EndDate) {
                    if ($StartDate != "" && $EndDate != "") {
                        $query->whereBetween('transactions.paid_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                    }
                })
                ->select('transactions.amount_paid', 'transactions.paid_date', 'transactions.status', 'transactions.type', 'transactions.total_amount', 'transactions.amount_paid', 'invoices.invoice_no', 'invoices.title AS InvoiceTitle', 'orders.order_id', 'orders.package_type AS OrderPackageType')
                ->orderBy('transactions.id', 'DESC')
                ->count();
        } else {
            $fetch_data = DB::table('transactions')
                ->leftJoin('invoices', 'transactions.invoice_id', '=', 'invoices.id')
                ->leftJoin('orders', 'transactions.order_id', '=', 'orders.id')
                ->where('transactions.bill_to', Auth::id())
                ->whereIn('transactions.status', array(1, 2))
                ->where(function ($query) use ($StartDate, $EndDate) {
                    if ($StartDate != "" && $EndDate != "") {
                        $query->whereBetween('transactions.paid_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                    }
                })
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('transactions.amount_paid', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('transactions.paid_date', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('invoices.invoice_no', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('invoices.title', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('transactions.amount_paid', 'transactions.paid_date', 'transactions.status', 'transactions.type', 'transactions.total_amount', 'transactions.amount_paid', 'invoices.invoice_no', 'invoices.title AS InvoiceTitle', 'orders.order_id', 'orders.package_type AS OrderPackageType')
                ->orderBy('transactions.id', 'DESC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('transactions')
                ->leftJoin('invoices', 'transactions.invoice_id', '=', 'invoices.id')
                ->leftJoin('orders', 'transactions.order_id', '=', 'orders.id')
                ->where('transactions.bill_to', Auth::id())
                ->whereIn('transactions.status', array(1, 2))
                ->where(function ($query) use ($StartDate, $EndDate) {
                    if ($StartDate != "" && $EndDate != "") {
                        $query->whereBetween('transactions.paid_date', [Carbon::parse($StartDate)->format("Y-m-d"), Carbon::parse($EndDate)->addDays(1)->format("Y-m-d")]);
                    }
                })
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('transactions.amount_paid', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('transactions.paid_date', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('invoices.invoice_no', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('invoices.title', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('transactions.amount_paid', 'transactions.paid_date', 'transactions.status', 'transactions.type', 'transactions.total_amount', 'transactions.amount_paid', 'invoices.invoice_no', 'invoices.title AS InvoiceTitle', 'orders.order_id', 'orders.package_type AS OrderPackageType')
                ->orderBy('transactions.id', 'DESC')
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $Status = "";
            $ExpenseType = "";
            if ($item->status == 1) {
                $Status = '<span class="badge badge-danger">Pending</span>';
            } elseif ($item->status == 2) {
                $Status = '<span class="badge badge-success">Paid</span>';
            }

            $sub_array = array();
            $sub_array['sr_no'] = $SrNo;
            if ($item->type == 1) {
                $sub_array['id'] = $item->invoice_no;
                if (strlen($item->InvoiceTitle) > 20) {
                    $str = substr($item->InvoiceTitle, 0, 20) . '...';
                    $sub_array['expense'] = $str;
                } else {
                    $sub_array['expense'] = $item->InvoiceTitle;
                }
            } elseif ($item->type == 2) {
                $sub_array['id'] = $item->order_id;
                if ($item->OrderPackageType == "monthly") {
                    $sub_array['expense'] = "Monthly Fees";
                } elseif ($item->OrderPackageType == "semi") {
                    $sub_array['expense'] = "Semi Annual Fees";
                } elseif ($item->OrderPackageType == "annual") {
                    $sub_array['expense'] = "Annual Fees";
                }
            }
            $sub_array['amount'] = "$" . $item->total_amount;
            $sub_array['status'] = $Status;
            if ($item->paid_date != "") {
                $sub_array['date'] = Carbon::parse($item->paid_date)->format('m/d/Y');
            } else {
                $sub_array['date'] = "";
            }
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

    public function LoadParentEvaluationReport(Request $request)
    {
        // get parents
        $ParentArray = array();
        $ChildrenArray = array();
        array_push($ParentArray, Auth::id());

        if (auth::user()->parent_id != 1) {
            $OtherParents = DB::table("users")->where('id', Auth::user()->parent_id)->where('role_id', 5)->get();
            foreach ($OtherParents as $key => $value) {
                array_push($ParentArray, $value->id);
            }
        }

        $OtherParents = DB::table("users")->where('parent_id', Auth::id())->where('role_id', 5)->get();
        foreach ($OtherParents as $key => $value) {
            array_push($ParentArray, $value->id);
        }

        // get total children
        foreach ($ParentArray as $key => $value) {
            $Children = DB::table("users")->where('parent_id', $value)->where('role_id', 6)->get();
            foreach ($Children as $index => $child) {
                array_push($ChildrenArray, $child->id);
            }
        }

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
                ->whereIn('user_evaluations.user_id', $ChildrenArray)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->whereIn('user_evaluations.user_id', $ChildrenArray)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->count();
        } else {
            $fetch_data = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->whereIn('user_evaluations.user_id', $ChildrenArray)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('user_evaluations.report_no', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_evaluations.evaluation_date', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_evaluations.grade', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->whereIn('user_evaluations.user_id', $ChildrenArray)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('user_evaluations.report_no', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_evaluations.evaluation_date', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_evaluations.grade', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $Player = "";
            if ($item->middleName != "") {
                $Player = $item->firstName . " " . $item->middleName . " " . $item->lastName;
            } else {
                $Player = $item->firstName . " " . $item->lastName;
            }

            $sub_array = array();
            $sub_array['sr_no'] = $SrNo;
            $sub_array['id'] = $item->report_no;
            $sub_array['player'] = "<span>" . wordwrap($Player, 15, '<br>') . "</span>";
            $sub_array['grade'] = $item->grade;
            $sub_array['date'] = Carbon::parse($item->evaluation_date)->format('m/d/Y');
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

    public function LoadPlayerEvaluationReport(Request $request)
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
            $fetch_data = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('user_evaluations.user_id', '=', Auth::id())
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('user_evaluations.user_id', '=', Auth::id())
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->count();
        } else {
            $fetch_data = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('user_evaluations.user_id', '=', Auth::id())
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('user_evaluations.report_no', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_evaluations.evaluation_date', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_evaluations.grade', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('user_evaluations')
                ->join('users', 'user_evaluations.user_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->where('user_evaluations.user_id', '=', Auth::id())
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('user_evaluations.report_no', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_evaluations.evaluation_date', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_evaluations.grade', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('user_evaluations.report_no', 'user_evaluations.evaluation_date', 'user_evaluations.grade', 'user_evaluations.report_pdf', 'user_evaluations.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
                ->orderBy('user_evaluations.id', 'DESC')
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $Player = "";
            if ($item->middleName != "") {
                $Player = $item->firstName . " " . $item->middleName . " " . $item->lastName;
            } else {
                $Player = $item->firstName . " " . $item->lastName;
            }

            $sub_array = array();
            $sub_array['sr_no'] = $SrNo;
            $sub_array['id'] = $item->report_no;
            $sub_array['player'] = "<span>" . wordwrap($Player, 15, '<br>') . "</span>";
            $sub_array['grade'] = $item->grade;
            $sub_array['date'] = Carbon::parse($item->evaluation_date)->format('m/d/Y');
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

    /* Coach All Player Table - Start */
    public function LoadCoachAllPlayer(Request $request)
    {
        $class_array = array();
        $classes = DB::table('classes')
            ->where('classes.deleted_at', '=', null)
            ->where('classes.coach', '=', Auth::id())
            ->select('classes.id')
            ->get();
        foreach ($classes as $key => $value) {
            array_push($class_array, $value->id);
        }

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
            $fetch_data = DB::table('class_assigns')
                ->join('users', 'class_assigns.player_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->leftJoin('player_positions', 'user_details.athletesPosition', '=', 'player_positions.id')
                ->whereIn('class_assigns.class_id', $class_array)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->select('users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.profile_pic', 'user_details.gender', 'player_positions.title as position', 'user_details.athletesTrainingDays', 'users.created_at')
                ->orderBy('users.id', 'ASC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('class_assigns')
                ->join('users', 'class_assigns.player_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->leftJoin('player_positions', 'user_details.athletesPosition', '=', 'player_positions.id')
                ->whereIn('class_assigns.class_id', $class_array)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->select('users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.profile_pic', 'user_details.gender', 'player_positions.title as position', 'user_details.athletesTrainingDays', 'users.created_at')
                ->orderBy('users.id', 'ASC')
                ->count();
        } else {
            $fetch_data = DB::table('class_assigns')
                ->join('users', 'class_assigns.player_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->leftJoin('player_positions', 'user_details.athletesPosition', '=', 'player_positions.id')
                ->whereIn('class_assigns.class_id', $class_array)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.gender', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('player_positions.title', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.athletesTrainingDays', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('users.created_at', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.profile_pic', 'user_details.gender', 'player_positions.title as position', 'user_details.athletesTrainingDays', 'users.created_at')
                ->orderBy('users.id', 'ASC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('class_assigns')
                ->join('users', 'class_assigns.player_id', '=', 'users.id')
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->leftJoin('player_positions', 'user_details.athletesPosition', '=', 'player_positions.id')
                ->whereIn('class_assigns.class_id', $class_array)
                ->where('users.deleted_at', '=', null)
                ->where('users.status', '=', 1)
                ->where('users.role_id', '=', 6)
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.gender', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('player_positions.title', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.athletesTrainingDays', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('users.created_at', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('users.userId', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.profile_pic', 'user_details.gender', 'player_positions.title as position', 'user_details.athletesTrainingDays', 'users.created_at')
                ->orderBy('users.id', 'ASC')
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $Player = "";
            $ProfilePic = "";
            if ($item->middleName != "") {
                $Player = $item->firstName . " " . $item->middleName . " " . $item->lastName;
            } else {
                $Player = $item->firstName . " " . $item->lastName;
            }

            $sub_array = array();
            $sub_array['sr_no'] = $SrNo;
            $sub_array['id'] = $item->userId;
            if ($item->profile_pic != "") {
                $ProfilePic = asset('public/storage/user-profiles/' . $item->profile_pic);
            } else {
                $ProfilePic = asset('public/assets/images/user.png');
            }
            $sub_array['photo'] = "<img class='img-fluid' src='" . $ProfilePic . "' alt='Profile Picture' width='50' height='50' style='border-radius: 50%;' />";
            $sub_array['player'] = "<span>" . wordwrap($Player, 15, '<br>') . "</span>";
            $sub_array['gender'] = $item->gender;
            $sub_array['position'] = $item->position;
            $sub_array['training_days'] = $item->athletesTrainingDays;
            $sub_array['date'] = Carbon::parse($item->created_at)->format('m/d/Y');
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
    /* Coach All Player Table - End */

    /*Finish Registration*/
    function completeRegistration()
    {
        $page = "dashboard";
        $Role = Session::get("user_role");
        $ChildUser = DB::table('users')
            ->where('parent_id', '=', Auth::id())
            ->get();
        $LeadConversion = DB::table('lead_conversions')
            ->where('parent_id', '=', Auth::id())
            ->where('conversion_type', '=', 2)
            ->get();
        if (sizeof($LeadConversion) == 0) {
            return redirect()->route('dashboard')->with('error', 'Lead already converted');
        }
        if(sizeof($ChildUser) == 0) {
            return redirect()->route('dashboard')->with('error', 'No Player Found');
        }
        $UserDetails = DB::table('user_details')
            ->where('user_id', '=', $ChildUser[0]->id)
            ->get();
        $ParentUserDetails = DB::table('user_details')
            ->where('user_id', '=', Auth::id())
            ->get();
        $PlayerDob = Carbon::parse($UserDetails[0]->dob);
        $PlayerAge = $PlayerDob->age;
        $Category = DB::table('categories')
            ->where('start_age', '<=', $PlayerAge)
            ->where('end_age', '>=', $PlayerAge)
            ->get();
        if(sizeof($Category) == 0) {
            return redirect()->route('dashboard')->with('error', 'No Category Found');
        } else {
            $Package = DB::table('packages')
                ->join('package_fee_structures', 'packages.id', '=', 'package_fee_structures.package')
                ->where('deleted_at', '=', null)
                ->where('level', '=', 4)
                ->where('packages.start_date', '<=', Carbon::now())
                ->where('packages.end_date', '>=', Carbon::now())
                ->whereRaw('FIND_IN_SET(?, category)', array($Category[0]->id))
                ->whereRaw('packages.limit > packages.package_usage')
                ->get();
            if(sizeof($Package) == 0) {
                return redirect()->route('dashboard')->with('error', 'No Package Found');
            } else {
                $States = DB::table('states')->get();
                return view('dashboard.registration', compact('page', 'Role', 'Category', 'Package', 'ParentUserDetails', 'States', 'LeadConversion'));
            }
        }
    }

    function stripeOrderCreate(Request $request)
    {
        $OrderCheck = DB::table('orders')
            ->where('payment_intent_id', '=', $request->post('PaymentIntentId'))
            ->where('client_secret_id', '=', $request->post('ClientSecret'))
            ->get();

        if(sizeof($OrderCheck) > 0) {
            /*Update*/
            DB::table('orders')
                ->where('payment_intent_id', '=', $request->post('PaymentIntentId'))
                ->where('client_secret_id', '=', $request->post('ClientSecret'))
                ->update([
                    'lead_id' => $request->post('LeadId'),
                    'package_id' => $request->post('PackageId'),
                    'category_id' => $request->post('CategoryId'),
                    'selected_days' => $request->post('SelectedDays'),
                    'package_type' => $request->post('PackageType'),
                    'registration_fee' => floatval($request->post('RegistrationFee')),
                    'coupon_code_id' => $request->post('CouponCode'),
                    'coupon_amount' => $request->post('CouponAmount'),
                    'sub_fee' => floatval($request->post('SubPrice')),
                    'tax' => floatval($request->post('Tax')),
                    'processing' => floatval($request->post('ProcessingFee')),
                    'amount' => floatval(ltrim($request->post('Price'), '$')),
                    'phone' => $request->post('Phone'),
                    'state' => $request->post('State'),
                    'city' => $request->post('City'),
                    'street' => $request->post('Street'),
                    'zipcode' => $request->post('ZipCode'),
                    'status' => 0,
                    'updated_at' => Carbon::now()
                ]);
        } else {
            /* Create */
            $OrderId = '';
            $PreviousOrderId = DB::table('orders')
                ->max('id');
            if ($PreviousOrderId != 0) {
                $OrderId = str_pad($PreviousOrderId + 1, 8, '0', STR_PAD_LEFT);
            } else {
                $OrderId = '00000001';
            }
            $TotalInvoices = 0;
            $CreatedInvoices = 0;
            $Status = 1;
            if($request->post('PackageType') == 'monthly') {
                $TotalInvoices = 12;
                $CreatedInvoices = 1;
                $Status = 1; /*Active*/
            } elseif($request->post('PackageType') == 'semi') {
                $TotalInvoices = 6;
                $CreatedInvoices = 1;
                $Status = 1; /*Active*/
            } elseif($request->post('PackageType') == 'annual') {
                $TotalInvoices = 1;
                $CreatedInvoices = 1;
                $Status = 4; /*Completed*/
            }
            Orders::create([
                'order_id' => $OrderId,
                'payment_intent_id' => $request->post('PaymentIntentId'),
                'client_secret_id' => $request->post('ClientSecret'),
                'stripe_customer_id' => $request->post('StripeCustomerId'),
                'lead_id' => $request->post('LeadId'),
                'package_id' => $request->post('PackageId'),
                'category_id' => $request->post('CategoryId'),
                'selected_days' => $request->post('SelectedDays'),
                'package_type' => $request->post('PackageType'),
                'total_invoices' => $TotalInvoices,
                'created_invoices' => $CreatedInvoices,
                'registration_fee' => floatval($request->post('RegistrationFee')),
                'coupon_code_id' => $request->post('CouponCode'),
                'coupon_amount' => $request->post('CouponAmount'),
                'sub_fee' => floatval($request->post('SubPrice')),
                'tax' => floatval($request->post('Tax')),
                'processing' => floatval($request->post('ProcessingFee')),
                'amount' => floatval(ltrim($request->post('Price'), '$')),
                'phone' => $request->post('Phone'),
                'state' => $request->post('State'),
                'city' => $request->post('City'),
                'street' => $request->post('Street'),
                'zipcode' => $request->post('ZipCode'),
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        DB::table('leads')
            ->where('id', '=', $request->post('LeadId'))
            ->update([
                'subscribe' => $request->post('Subscribe')
            ]);
    }

    function stripeOrderFinish(Request $request)
    {
        $PaymentIntent = isset($_GET['payment_intent'])? $_GET['payment_intent'] : '';
        $ClientSecret = isset($_GET['payment_intent_client_secret'])? $_GET['payment_intent_client_secret'] : '';
        $RedirectionStatus = isset($_GET['redirect_status'])? $_GET['redirect_status'] : '';
        $ParentGender = null;
        if ($RedirectionStatus == 'succeeded') {
            /*Payment Successful*/
            DB::beginTransaction();
            $CheckoutData = DB::table('orders')
                ->where('payment_intent_id', '=', $PaymentIntent)
                ->where('client_secret_id', '=', $ClientSecret)
                ->get();
            if(sizeof($CheckoutData) > 0) {
                /*Success*/
                $Player = SiteHelper::GetPlayerFromLead($CheckoutData[0]->lead_id);
                $PlayerId = 0;
                if(sizeof($Player) > 0) {
                    $PlayerId = $Player[0]->user_id;
                }
                /*Change Order Status*/
                DB::table('orders')
                    ->where('id', '=', $CheckoutData[0]->id)
                    ->update([
                        'user_id' => Auth::id(),
                        'player_id' => $PlayerId,
                        'status' => 1
                    ]);

                /*Order Invoices*/
                $HomeController = new HomeController();
                OrderInvoices::create([
                    'order_id' => $CheckoutData[0]->id,
                    'invoice_id' => $HomeController->generateRandomString(8),
                    'invoice_date' => Carbon::now(),
                    /*'invoice_expiry' => Carbon::now()->addMonths(1),*/
                    'invoice_expiry' => Carbon::now()->endOfMonth(),
                    'tax' => $CheckoutData[0]->tax,
                    'processing' => $CheckoutData[0]->processing,
                    'amount' => $CheckoutData[0]->sub_fee,
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                /*Change Lead Status*/
                DB::table('leads')
                    ->where('id', '=', $CheckoutData[0]->lead_id)
                    ->update([
                        'lead_status' => 8
                    ]);

                /*Change Lead Conversion Status*/
                DB::table('lead_conversions')
                    ->where('lead_id', '=', $CheckoutData[0]->lead_id)
                    ->update([
                        'conversion_type' => 1,
                        'updated_at' => Carbon::now()
                    ]);

                Transaction::create([
                    'type' => 2,
                    'bill_to' => Auth::id(),
                    'order_id' => $CheckoutData[0]->id,
                    'total_amount' => $CheckoutData[0]->amount,
                    'amount_paid' => $CheckoutData[0]->amount,
                    'status' => 2,
                    'paid_date' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                /*Update Coupon Code Count if Used*/
                if($CheckoutData[0]->coupon_code_id != '') {
                    DB::table('coupons')
                        ->where('id', '=', $CheckoutData[0]->coupon_code_id)
                        ->update([
                            'coupon_usage' => (Coupons::find($CheckoutData[0]->coupon_code_id)->coupon_usage) + 1,
                            'updated_at' => Carbon::now()
                        ]);
                }

                DB::commit();
                return redirect()->route('dashboard')->with('success', 'Registration successful!');
            } else {
                /*Invalid Response*/
                return redirect()->route('dashboard')->with('error', 'Payment Error!');
            }
        } else {
            /*Payment Unsuccessful*/
            return redirect()->route('dashboard')->with('error', 'Payment unsuccessful!');
        }
    }

    function updateRegistration()
    {
        $page = "dashboard";
        $Role = Session::get("user_role");
        $FreeClasses = DB::table('classes')
            ->where('is_free', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();

        $LeadConversion = DB::table('lead_conversions')
            ->where('parent_id', '=', Auth::id())
            ->where('conversion_type', '=', 2)
            ->get();
        $States = DB::table('states')->get();
        return view('dashboard.registration-update', compact('page', 'Role', 'States', 'LeadConversion', 'FreeClasses'));
    }

    function updateLeadRegistration(Request $request)
    {
        $LeadId = $request['leadId'];
        $FreeClass = $request['free_class'];
        $FreeClassDate = null;
        $FreeClassTime = null;
        if ($FreeClass != "" && $request['free_class_date'] != "" && $request['free_class_time'] != "") {
            $FreeClassDate = Carbon::parse($request['free_class_date'])->format('Y-m-d');
            $FreeClassTime = $request['free_class_time'];
            DB::table('lead_details')
                ->where('lead_id', '=', $LeadId)
                ->update([
                    'free_class' => $FreeClass,
                    'free_class_date' => $FreeClassDate,
                    'free_class_time' => $FreeClassTime,
                    'updated_at' => Carbon::now()
                ]);
            return redirect()->route('dashboard')->with('success', 'Free class updated successfully');
        }
        return redirect()->route('dashboard')->with('error', 'An unhandled error occurred');
    }
    /*Finish Registration*/

    /*Transactions*/
    function AdminAllTransactions()
    {
        $page = "billing";
        $Role = Session::get('user_role');
        return view('dashboard.billing.transactions.index', compact('page', 'Role'));
    }

    function AdminAllTransactionsLoad(Request $request)
    {
        $OrderId = $request->post('OrderId');
        $Role = Session::get('user_role');
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];

        $columnIndex = $request->post('order')[0]['column']; // Column index
        $columnName = 'transactions.' . $request->post('columns')[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->post('order')[0]['dir']; // asc or desc

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;

        if ($searchTerm == '') {
            $fetch_data = DB::table('transactions')
                ->leftJoin('orders', 'transactions.order_id', '=', 'orders.id')
                ->leftJoin('invoices', 'transactions.invoice_id', '=', 'invoices.id')
                ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                ->leftJoin('user_details', 'transactions.bill_to', '=', 'user_details.user_id')
                ->whereIn('transactions.type', array(1,2))
                ->where(function ($query) use ($OrderId) {
                    if($OrderId != '') {
                        $query->where('transactions.order_id', '=', $OrderId);
                    }
                })
                ->select('transactions.*', 'user_details.firstName as first_name', 'user_details.middleName as middle_name', 'user_details.lastName as last_name', 'orders.payment_intent_id', 'leads.lead_number', 'user_details.user_id', 'invoices.invoice_no AS InvoiceNo', 'invoices.payment_intent_id AS InvoicePaymentIntent')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();

            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('transactions')
                ->leftJoin('orders', 'transactions.order_id', '=', 'orders.id')
                ->leftJoin('invoices', 'transactions.invoice_id', '=', 'invoices.id')
                ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                ->leftJoin('user_details', 'transactions.bill_to', '=', 'user_details.user_id')
                ->whereIn('transactions.type', array(1,2))
                ->where(function ($query) use ($OrderId) {
                    if($OrderId != '') {
                        $query->where('transactions.order_id', '=', $OrderId);
                    }
                })
                ->select('transactions.*', 'user_details.firstName as first_name', 'user_details.middleName as middle_name', 'user_details.lastName as last_name', 'orders.payment_intent_id', 'leads.lead_number', 'user_details.user_id', 'invoices.invoice_no AS InvoiceNo', 'invoices.payment_intent_id AS InvoicePaymentIntent')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        } else {
            $fetch_data = DB::table('transactions')
                ->leftJoin('orders', 'transactions.order_id', '=', 'orders.id')
                ->leftJoin('invoices', 'transactions.invoice_id', '=', 'invoices.id')
                ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                ->leftJoin('user_details', 'transactions.bill_to', '=', 'user_details.user_id')
                ->whereIn('transactions.type', array(1,2))
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('transactions.total_amount', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('transactions.amount_paid', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('orders.order_id', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.lead_number', 'LIKE', '%' . $searchTerm . '%');
                })
                ->where(function ($query) use ($OrderId) {
                    if($OrderId != '') {
                        $query->where('transactions.order_id', '=', $OrderId);
                    }
                })
                ->select('transactions.*', 'user_details.firstName as first_name', 'user_details.middleName as middle_name', 'user_details.lastName as last_name', 'orders.payment_intent_id', 'leads.lead_number', 'user_details.user_id', 'invoices.invoice_no AS InvoiceNo', 'invoices.payment_intent_id AS InvoicePaymentIntent')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('transactions')
                ->leftJoin('orders', 'transactions.order_id', '=', 'orders.id')
                ->leftJoin('invoices', 'transactions.invoice_id', '=', 'invoices.id')
                ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                ->leftJoin('user_details', 'transactions.bill_to', '=', 'user_details.user_id')
                ->whereIn('transactions.type', array(1,2))
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('transactions.total_amount', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('transactions.amount_paid', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('orders.order_id', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('leads.lead_number', 'LIKE', '%' . $searchTerm . '%');
                })
                ->where(function ($query) use ($OrderId) {
                    if($OrderId != '') {
                        $query->where('transactions.order_id', '=', $OrderId);
                    }
                })
                ->select('transactions.*', 'user_details.firstName as first_name', 'user_details.middleName as middle_name', 'user_details.lastName as last_name', 'orders.payment_intent_id', 'leads.lead_number', 'user_details.user_id', 'invoices.invoice_no AS InvoiceNo', 'invoices.payment_intent_id AS InvoicePaymentIntent')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $Status = "";
            $ParentName = $item->first_name . " " . $item->last_name;
            if ($item->status == 1) {
                $Status = '<span class="badge badge-pill badge-warning">Pending</span>';
            } elseif ($item->status == 2) {
                $Status = '<span class="badge badge-pill badge-success">Paid</span>';
            } elseif ($item->status == 3) {
                $Status = '<span class="badge badge-pill badge-danger">Failed</span>';
            }

            $_User = User::find($item->user_id);
            $sub_array = array();
            $sub_array['id'] = $SrNo;
            if($item->payment_intent_id != '') {
                $sub_array['transaction_id'] = $item->payment_intent_id;
            } else {
                $sub_array['transaction_id'] = $item->InvoicePaymentIntent;
            }
            /*$sub_array['lead_number'] = $item->lead_number;*/
            $sub_array['lead_number'] = isset($_User->userId)? $_User->userId : '';
            $sub_array['bill_to'] = $ParentName;
            $sub_array['total_amount'] = "$" . number_format((float)$item->total_amount, 2, '.', '');
            $sub_array['amount_paid'] = "$" . number_format((float)$item->amount_paid, 2, '.', '');
            $sub_array['status'] = $Status;
            $sub_array['comments'] = wordwrap($item->comments, 20, '<br>');
            $sub_array['paid_date'] = Carbon::parse($item->created_at)->format('m/d/Y') . '<br>' . Carbon::parse($item->created_at)->format('H:i a');
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
    /*Transactions*/

    /*Subscriptions*/
    function AdminAllSubscriptions()
    {
        $page = "billing";
        $Role = Session::get('user_role');
        return view('dashboard.billing.subscriptions.index', compact('page', 'Role'));
    }

    function AdminAllSubscriptionsLoad(Request $request)
    {
        $Role = Session::get('user_role');
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];

        $columnIndex = $request->post('order')[0]['column']; // Column index
        $columnName = 'orders.' . $request->post('columns')[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->post('order')[0]['dir']; // asc or desc

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;

        if ($Role == 1 || $Role == 2 || $Role == 3) {
            if ($searchTerm == '') {
                $fetch_data = DB::table('orders')
                    ->join('packages', 'orders.package_id', '=', 'packages.id')
                    ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                    ->leftJoin('lead_details', 'leads.id', '=', 'lead_details.lead_id')
                    ->select('orders.*', 'leads.parentFirstName', 'leads.parentLastName', 'lead_details.playerFirstName', 'lead_details.playerLastName', 'packages.title AS PackageTitle')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('orders')
                    ->join('packages', 'orders.package_id', '=', 'packages.id')
                    ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                    ->leftJoin('lead_details', 'leads.id', '=', 'lead_details.lead_id')
                    ->select('orders.*', 'leads.parentFirstName', 'leads.parentLastName', 'lead_details.playerFirstName', 'lead_details.playerLastName', 'packages.title AS PackageTitle')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('orders')
                    ->join('packages', 'orders.package_id', '=', 'packages.id')
                    ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                    ->leftJoin('lead_details', 'leads.id', '=', 'lead_details.lead_id')
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('leads.parentFirstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('leads.parentLastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('lead_details.playerFirstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('lead_details.playerLastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('packages.title', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('orders.*', 'leads.parentFirstName', 'leads.parentLastName', 'lead_details.playerFirstName', 'lead_details.playerLastName', 'packages.title AS PackageTitle')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('orders')
                    ->join('packages', 'orders.package_id', '=', 'packages.id')
                    ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                    ->leftJoin('lead_details', 'leads.id', '=', 'lead_details.lead_id')
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('leads.parentFirstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('leads.parentLastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('lead_details.playerFirstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('lead_details.playerLastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('packages.title', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('orders.*', 'leads.parentFirstName', 'leads.parentLastName', 'lead_details.playerFirstName', 'lead_details.playerLastName', 'packages.title AS PackageTitle')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        } elseif($Role == 5) {
            if ($searchTerm == '') {
                $fetch_data = DB::table('orders')
                    ->join('packages', 'orders.package_id', '=', 'packages.id')
                    ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                    ->leftJoin('lead_details', 'leads.id', '=', 'lead_details.lead_id')
                    ->where('orders.user_id', '=', Auth::id())
                    ->select('orders.*', 'leads.parentFirstName', 'leads.parentLastName', 'lead_details.playerFirstName', 'lead_details.playerLastName', 'packages.title AS PackageTitle')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('orders')
                    ->join('packages', 'orders.package_id', '=', 'packages.id')
                    ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                    ->leftJoin('lead_details', 'leads.id', '=', 'lead_details.lead_id')
                    ->where('orders.user_id', '=', Auth::id())
                    ->select('orders.*', 'leads.parentFirstName', 'leads.parentLastName', 'lead_details.playerFirstName', 'lead_details.playerLastName', 'packages.title AS PackageTitle')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('orders')
                    ->join('packages', 'orders.package_id', '=', 'packages.id')
                    ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                    ->leftJoin('lead_details', 'leads.id', '=', 'lead_details.lead_id')
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('leads.parentFirstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('leads.parentLastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('lead_details.playerFirstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('lead_details.playerLastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('packages.title', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->where('orders.user_id', '=', Auth::id())
                    ->select('orders.*', 'leads.parentFirstName', 'leads.parentLastName', 'lead_details.playerFirstName', 'lead_details.playerLastName', 'packages.title AS PackageTitle')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('orders')
                    ->join('packages', 'orders.package_id', '=', 'packages.id')
                    ->leftJoin('leads', 'orders.lead_id', '=', 'leads.id')
                    ->leftJoin('lead_details', 'leads.id', '=', 'lead_details.lead_id')
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('leads.parentFirstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('leads.parentLastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('lead_details.playerFirstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('lead_details.playerLastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('packages.title', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->where('orders.user_id', '=', Auth::id())
                    ->select('orders.*', 'leads.parentFirstName', 'leads.parentLastName', 'lead_details.playerFirstName', 'lead_details.playerLastName', 'packages.title AS PackageTitle')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $Status = "";
            $ParentName = $item->parentFirstName . " " . $item->parentLastName;
            if($ParentName == " ") {
                $ParentDetails = SiteHelper::GetUserDetails($item->user_id);
                if(sizeof($ParentDetails) > 0) {
                    $ParentName = $ParentDetails[0]->firstName . ' ' . $ParentDetails[0]->lastName;
                }
            }
            $PlayerName = $item->playerFirstName . " " . $item->playerLastName;
            if($PlayerName == " ") {
                $PlayerDetails = SiteHelper::GetUserDetails($item->player_id);
                if(sizeof($PlayerDetails) > 0) {
                    $PlayerName = $PlayerDetails[0]->firstName . ' ' . $PlayerDetails[0]->lastName;
                }
            }
            if ($item->status == 1) {
                $Status = '<span class="badge badge-pill badge-primary">Active</span>';
            } elseif ($item->status == 2) {
                $Status = '<span class="badge badge-pill badge-danger">Suspended</span>';
            } elseif ($item->status == 3) {
                $Status = '<span class="badge badge-pill badge-warning">Hold</span>';
            } elseif ($item->status == 4) {
                $Status = '<span class="badge badge-pill badge-success">Completed</span>';
            }

            /*Next Billing Date*/
            $NextBilling = '';
            $Amount = $item->sub_fee;
            /*Tax Inclusion*/
            $Amount = round($Amount + ($Amount * floatval($item->tax)) / 100, 2);
            /*Processing Fee Inclusion*/
            $Amount = round($Amount + ($Amount * floatval($item->processing)) / 100, 2);
            if($item->status == 4 || $item->status == 3 || $item->status == 2) {
                $NextBilling = '-';
            } else {
                $NextBilling = '<b>' . $this->GetNextBillingForSubscription($item->id) . '</b><br>$' . $Amount;
            }
            /*Next Billing Date*/

            /*Action*/
            $Action = "";
            if ($Role == 1) {
                $Action = "<span>";
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="viewBtn_' . $item->id . '" data-toggle="tooltip" title="View" onclick="ViewSubscription(this);"><i class="fas fa-eye"></i></button>';
                if($item->status == 1) { /*if($item->status != 2 && $item->status != 4) {*/
                    $Action .= '<button type="button" class="btn btn-primary btn-sm" id="suspendBtn_' . $item->id . '" data-toggle="tooltip" title="Suspend" onclick="SuspendSubscription(this);"><i class="fas fa-ban"></i></button>';
                    $Action .= '<button type="button" class="btn btn-primary btn-sm" id="holdBtn_' . $item->id . '" data-toggle="tooltip" title="Hold" onclick="HoldSubscription(this);"><i class="fas fa-stop-circle"></i></button>';
                    $Action .= '<button type="button" class="btn btn-primary btn-sm" id="cancelBtn_' . $item->id . '" data-toggle="tooltip" title="Cancel" onclick="CancelSubscription(this);"><i class="fas fa-times-circle"></i></button>';
                } elseif($item->status == 2) {
                    $Action .= '<button type="button" class="btn btn-primary btn-sm" id="activateBtn_' . $item->id . '" data-toggle="tooltip" title="Activate" onclick="ActivateSubscription(this);"><i class="fas fa-check"></i></button>';
                    $Action .= '<button type="button" class="btn btn-primary btn-sm" id="cancelBtn_' . $item->id . '" data-toggle="tooltip" title="Cancel" onclick="CancelSubscription(this);"><i class="fas fa-times-circle"></i></button>';
                } elseif($item->status == 3) {
                    $Action .= '<button type="button" class="btn btn-primary btn-sm" id="activateBtn_' . $item->id . '" data-toggle="tooltip" title="Activate" onclick="ActivateSubscription(this);"><i class="fas fa-check"></i></button>';
                } else {
                    $Action .= '';
                }
                $Action .= "<span>";
            } elseif ($Role == 5) {
                $Action = "<span>";
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="viewBtn_' . $item->id . '" data-toggle="tooltip" title="View" onclick="ViewSubscription(this);"><i class="fas fa-eye"></i></button>';
                $Action .= "<span>";
            }
            /*Action*/

            $sub_array = array();
            $sub_array['id'] = $SrNo;
            $sub_array['parent'] = $ParentName;
            $sub_array['player'] = $PlayerName;
            $sub_array['package'] = $item->PackageTitle;
            $sub_array['package_type'] = ucwords($item->package_type == 'semi'? 'Semi Annual' : $item->package_type);
            $sub_array['status'] = $Status;
            $sub_array['register_date'] = Carbon::parse($item->created_at)->format('m/d/Y');
            $sub_array['next_billing'] = $NextBilling;
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

    function GetNextBillingForSubscription($OrderId)
    {
        $OrderInvoices = DB::table('order_invoices')
            ->where('order_id', '=', $OrderId)
            ->orderBy('id', 'DESC')
            ->get();
        return Carbon::parse($OrderInvoices[0]->invoice_date)->addMonths(1)->startOfMonth()->format('m/d/Y');
    }

    function AdminAllSubscriptionsHold(Request $request)
    {
        DB::beginTransaction();
        $OrderId = $request->post('OrderId');
        DB::table('orders')
            ->where('id', '=', $OrderId)
            ->update([
                'status' => 3,
                'holding_date' => Carbon::now(),
                'suspended_reason' => null,
                'suspended_date' => null,
                'cancel_reason' => null,
                'cancel_date' => null,
                'updated_at' => Carbon::now()
            ]);
        echo true;
        DB::commit();
//        $Order = DB::table('orders')
//            ->where('id', '=', $OrderId)
//            ->get();
//        /*Termination Fee for Package*/
//        $PackageFeeStructure = DB::table('package_fee_structures')
//            ->where('package', '=', $Order[0]->package_id)
//            ->where('fee_Type', '=', $Order[0]->package_type == 'semi'? 'semi-annual' : $Order[0]->package_type)
//            ->get();
//        $Fee = 0;
//        if(sizeof($PackageFeeStructure) > 0) {
//            $Fee = round(floatval($PackageFeeStructure[0]->holding_fee), 2);
//        }
//        /*Termination Fee for Package*/
//        DB::beginTransaction();
//        /*Charge Customer For Holding Fee*/
//        if($this->StripeChargeCustomer($OrderId, $Order[0]->stripe_customer_id, $Fee, 'Holding Fee')) {
//            DB::table('orders')
//                ->where('id', '=', $OrderId)
//                ->update([
//                    'status' => 3,
//                    'updated_at' => Carbon::now()
//                ]);
//            echo true;
//        } else {
//            echo false;
//        }
    }

    function AdminAllSubscriptionsActivate(Request $request){
        $OrderId = $request->post('OrderId');
        $Order = DB::table('orders')
            ->where('id', '=', $OrderId)
            ->get();
        if($Order[0]->status == 2) {
            /*Charge Reactivation only if suspended*/
            /*Reactivation Fee for Package*/
            $PackageFeeStructure = DB::table('package_fee_structures')
                ->where('package', '=', $Order[0]->package_id)
                ->where('fee_Type', '=', $Order[0]->package_type == 'semi'? 'semi-annual' : $Order[0]->package_type)
                ->get();
            $Fee = 0;
            if(sizeof($PackageFeeStructure) > 0) {
                $Fee = round(floatval($PackageFeeStructure[0]->reactivation_fee), 2);
            }
            /*Termination Fee for Package*/
            DB::beginTransaction();
            /*Charge Customer For Reactivation Fee*/
            if($this->StripeChargeCustomer($OrderId, $Order[0]->stripe_customer_id, $Fee, 'Reactivation Fee')) {
                DB::table('orders')
                    ->where('id', '=', $OrderId)
                    ->update([
                        'status' => 1,
                        'holding_date' => null,
                        'suspended_reason' => null,
                        'suspended_date' => null,
                        'cancel_reason' => null,
                        'cancel_date' => null,
                        'updated_at' => Carbon::now()
                    ]);
                echo true;
            } else {
                echo false;
            }
        } else {
            /*Reactivate after hold*/
            DB::table('orders')
                ->where('id', '=', $OrderId)
                ->update([
                    'status' => 1,
                    'holding_date' => null,
                    'updated_at' => Carbon::now()
                ]);
            echo true;
        }
        DB::commit();
    }

    function AdminAllSubscriptionsSuspend(Request $request)
    {
        DB::beginTransaction();
        $OrderId = $request->post('OrderId');
        DB::table('orders')
            ->where('id', '=', $OrderId)
            ->update([
                'status' => 2,
                'holding_date' => null,
                'suspended_reason' => $request['SuspendReason'],
                'suspended_date' => Carbon::now(),
                'cancel_reason' => null,
                'cancel_date' => null,
                'updated_at' => Carbon::now()
            ]);
        echo true;
        DB::commit();
//        $OrderId = $request->post('OrderId');
//        $Order = DB::table('orders')
//            ->where('id', '=', $OrderId)
//            ->get();
//        /*Termination Fee for Package*/
//        $PackageFeeStructure = DB::table('package_fee_structures')
//            ->where('package', '=', $Order[0]->package_id)
//            ->where('fee_Type', '=', $Order[0]->package_type == 'semi'? 'semi-annual' : $Order[0]->package_type)
//            ->get();
//        $TerminationFee = 0;
//        if(sizeof($PackageFeeStructure) > 0) {
//            $TerminationFee = round(floatval($PackageFeeStructure[0]->termination_fee), 2);
//        }
//        /*Termination Fee for Package*/
//        DB::beginTransaction();
//        /*Charge Customer For Early Termination Fee*/
//        if($this->StripeChargeCustomer($OrderId, $Order[0]->stripe_customer_id, $TerminationFee, 'Early Termination Fee')) {
//            DB::table('orders')
//                ->where('id', '=', $OrderId)
//                ->update([
//                    'status' => 2,
//                    'suspended_reason' => $request['SuspendReason'],
//                    'updated_at' => Carbon::now()
//                ]);
//            echo true;
//        } else {
//            echo false;
//        }
//        DB::commit();
    }

    function AdminAllSubscriptionsCancel(Request $request)
    {
        $OrderId = $request->post('OrderId');
        $Order = DB::table('orders')
            ->where('id', '=', $OrderId)
            ->get();
        /*Termination Fee for Package*/
        $PackageFeeStructure = DB::table('package_fee_structures')
            ->where('package', '=', $Order[0]->package_id)
            ->where('fee_Type', '=', $Order[0]->package_type == 'semi'? 'semi-annual' : $Order[0]->package_type)
            ->get();
        $TerminationFee = 0;
        if(sizeof($PackageFeeStructure) > 0) {
            $TerminationFee = round(floatval($PackageFeeStructure[0]->termination_fee), 2);
        }
        $OrderStartDate = Carbon::parse($Order[0]->created_at);
        $OrderEndDate = null;
        if($Order[0]->package_type == 'monthly') {
            $OrderEndDate = $OrderStartDate->addMonths(11)->endOfMonth();
        } elseif($Order[0]->package_type == 'semi') {
            $OrderEndDate = $OrderStartDate->addMonths(5)->endOfMonth();
        }
        /*Termination Fee for Package*/
        DB::beginTransaction();
        if(Carbon::now() > $OrderEndDate) {
            /*Termination on Time*/
            DB::table('orders')
                ->where('id', '=', $Order[0]->id)
                ->update([
                    'status' => 5,
                    'holding_date' => null,
                    'suspended_reason' => null,
                    'suspended_date' => null,
                    'cancel_reason' => $request['CancelReason'],
                    'cancel_date' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            echo true;
        } else {
            /*Early Termination*/
            /*Charge Customer For Early Termination Fee*/
            if($this->StripeChargeCustomer($OrderId, $Order[0]->stripe_customer_id, $TerminationFee, 'Early Termination Fee')) {
                DB::table('orders')
                    ->where('id', '=', $OrderId)
                    ->update([
                        'status' => 5,
                        'holding_date' => null,
                        'suspended_reason' => null,
                        'suspended_date' => null,
                        'cancel_reason' => $request['CancelReason'],
                        'cancel_date' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                echo true;
            } else {
                echo false;
            }
        }
        DB::commit();
    }

    function StripeChargeCustomer($OrderId, $CustomerId, $Amount, $Comments)
    {
        $HomeController = new HomeController();
        Stripe::setApiKey('sk_test_4eC39HqLyjWDarjtT1zdp7dc');
        $stripe = new StripeClient(Config::get('services.stripe.secret'));
        try {
            $PaymentMethodId = $HomeController->GetStripeCustomerPaymentMethods($CustomerId);
            $PaymentIntent = $stripe->paymentIntents->create([
                'amount' => $Amount * 100,
                'currency' => 'usd',
                'customer' => $CustomerId
            ]);
            $PaymentStatus = $stripe->paymentIntents->confirm($PaymentIntent->id, ['payment_method' => $PaymentMethodId]);
            if($PaymentStatus->status == 'succeeded') {
                /*Payment Successful*/
                /*Transactions Table Entry*/
                Transaction::create([
                    'type' => 2,
                    'bill_to' => $HomeController->GetUserFromLeadConversion($OrderId),
                    'order_id' => $OrderId,
                    'total_amount' => $Amount,
                    'amount_paid' => $Amount,
                    'status' => 2,
                    'paid_date' => Carbon::now(),
                    'comments' => $Comments,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                return true;
            } else {
                /*Payment unsuccessful*/
                /*Transactions Table Entry*/
                Transaction::create([
                    'type' => 2,
                    'bill_to' => $HomeController->GetUserFromLeadConversion($OrderId),
                    'order_id' => $OrderId,
                    'total_amount' => $Amount,
                    'amount_paid' => $Amount,
                    'status' => 3,
                    'paid_date' => Carbon::now(),
                    'comments' => 'Early Termination Fee',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                return false;
            }
        } catch (\Exception $exception) {
            /*Payment unsuccessful*/
            /*Transactions Table Entry*/
            Transaction::create([
                'type' => 2,
                'bill_to' => $HomeController->GetUserFromLeadConversion($OrderId),
                'order_id' => $OrderId,
                'total_amount' => $Amount,
                'amount_paid' => $Amount,
                'status' => 3,
                'paid_date' => Carbon::now(),
                'comments' => 'Early Termination Fee',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            return false;
        }
    }

    function AdminAllSubscriptionsView($Id)
    {
        $Id = base64_decode($Id);
        $page = "billing";
        $Role = Session::get('user_role');
        $Order = DB::table('orders')
            ->where('id', '=', $Id)
            ->get();
        if(sizeof($Order) == 0) {
            return redirect()->route('billing.subscriptions');
        }
        return view('dashboard.billing.subscriptions.view', compact('page', 'Role', 'Id'));
    }
    /*Subscriptions*/

    /*Coupons*/
    function AdminAllCoupons()
    {
        $page = "billing";
        $Role = Session::get('user_role');
        return view('dashboard.billing.coupons.index', compact('page', 'Role'));
    }

    function AdminAllCouponsLoad(Request $request)
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

        if ($Role == 1 || $Role == 2 || $Role == 3) {
            if ($searchTerm == '') {
                $fetch_data = DB::table('coupons')
                    ->where('deleted_at', '=', null)
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('coupons')
                    ->where('deleted_at', '=', null)
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('coupons')
                    ->where('deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('coupons.coupon_name', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('coupons.coupon_code', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('coupons.coupon_type', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('coupons.coupon_limit', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('coupons.coupon_apply', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('coupons')
                    ->where('deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('coupons.coupon_name', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('coupons.coupon_code', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('coupons.coupon_type', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('coupons.coupon_limit', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('coupons.coupon_apply', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $Action = "";
            if($Role == 1) {
                /*Action*/
                $Action = "<span>";
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="editBtn_' . $item->id . '" data-toggle="tooltip" title="Edit" onclick="Edit(this);"><i class="fas fa-pen"></i></button>';
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="deleteBtn_' . $item->id . '" data-toggle="tooltip" title="Delete" onclick="Delete(this);"><i class="fas fa-trash"></i></button>';
                $Action .= "<span>";
                /*Action*/
            } elseif($Role == 2 || $Role == 3) {
                /*Action*/
                $Action = "<span>";
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="editBtn_' . $item->id . '" data-toggle="tooltip" title="Edit" onclick="Edit(this);"><i class="fas fa-pen"></i></button>';
                $Action .= "<span>";
                /*Action*/
            }

            $sub_array = array();
            $sub_array['id'] = $SrNo;
            $sub_array['coupon_name'] = $item->coupon_name;
            $sub_array['coupon_code'] = $item->coupon_code;
            $sub_array['coupon_type'] = ucwords($item->coupon_type);
            $sub_array['coupon_limit'] = $item->coupon_limit;
            $sub_array['coupon_apply'] = $item->coupon_apply == 'everyMonth'? 'Every Month' : 'One Time';
            $sub_array['coupon_rate'] = $item->coupon_type == 'flat'? '$' . $item->coupon_rate : $item->coupon_rate . '%';
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

    function AdminAllCouponsAdd()
    {
        $page = "billing";
        $Role = Session::get('user_role');
        return view('dashboard.billing.coupons.add', compact('page', 'Role'));
    }

    function AdminAllCouponsStore(Request $request)
    {
        $Affected = Coupons::create([
            'coupon_name' => $request->post('name'),
            'coupon_code' => $this->GenerateCouponCode(9),
            'coupon_type' => $request->post('type'),
            'coupon_limit' => $request->post('limit'),
            'coupon_apply' => $request->post('applyOn'),
            'coupon_rate' => $request->post('rate'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if($Affected) {
            return redirect()->route('billing.coupons')->with('success', 'Coupon code created successfully!');
        } else {
            return redirect()->route('billing.coupons')->with('error', 'An unhandled error occurred!');
        }
    }

    function AdminAllCouponsDelete(Request $request)
    {
        $Affected = DB::table('coupons')
            ->where('id', '=', $request->post('id'))
            ->update([
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ]);
        if($Affected) {
            return redirect()->route('billing.coupons')->with('success', 'Coupon code deleted successfully!');
        } else {
            return redirect()->route('billing.coupons')->with('error', 'An unhandled error occurred!');
        }
    }

    function AdminAllCouponsEdit($Id)
    {
        $Id = base64_decode($Id);
        $page = "billing";
        $Role = Session::get('user_role');
        $Coupon = DB::table('coupons')
            ->where('id', '=', $Id)
            ->get();
        if(sizeof($Coupon) == 0) {
            return redirect()->route('billing.coupons');
        }
        return view('dashboard.billing.coupons.edit', compact('page', 'Role', 'Id', 'Coupon'));
    }

    function AdminAllCouponsUpdate(Request $request)
    {
        $Affected = DB::table('coupons')
            ->where('id', '=', $request->post('id'))
            ->update([
                'coupon_name' => $request->post('name'),
                'coupon_type' => $request->post('type'),
                'coupon_limit' => $request->post('limit'),
                'coupon_apply' => $request->post('applyOn'),
                'coupon_rate' => $request->post('rate'),
                'updated_at' => Carbon::now()
            ]);

        if($Affected) {
            return redirect()->route('billing.coupons.edit', array(base64_encode($request->post('id'))))->with('success', 'Coupon code updated successfully!');
        } else {
            return redirect()->route('billing.coupons.edit', array(base64_encode($request->post('id'))))->with('error', 'An unhandled error occurred!');
        }
    }

    function GenerateCouponCode($length = 10) {
        $characters = '!123@456#789$0AB%CDE^FGH&IJK*LMN(OPQ)RST_UVW+XYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    /*Coupons*/
}
