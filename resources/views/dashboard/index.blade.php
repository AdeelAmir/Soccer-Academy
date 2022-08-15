@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        .monthDropdownMenuStyling {
            background: none;
            margin-bottom: 0px;
        }

        .monthDropdownMenuStyling:hover {
            background: none;
            margin-bottom: 0px;
        }

        .monthDropdownMenuStyling:active {
            box-shadow: none;
            background: none;
            margin-bottom: 0px;
        }

        .panel {
            position: relative;
            background: #ffffff;
            padding: 10px 30px;
            border: 0;
            margin-bottom: 30px;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
        }

        .panel .panel-heading {
            position: relative;
            padding: 0;
            margin: 0;
            background: none;
            font-size: 17px;
            padding-bottom: 0px;
            border-bottom: 2px solid #f5f5f5;
        }

        .myKidsTable > thead > tr > td, .myKidsTable > tbody > tr > td, .myKidsTable > tfood > tr > td, .myKidsTable > thead > tr > th, .myKidsTable > tbody > tr > th, .myKidsTable > tfood > tr > th {
            padding: 5px 15px;
        }

        .myKidsTableHeading {
            border: none !important;
            width: 40%;
        }

        .myKidsTableData {
            border: none !important;
            width: 60%;
        }

        .kidBlock {
            background-color: #fafafa;
            margin-left: 15px;
            margin-right: 15px;
        }

        .parentExpenseBlock {
            margin-left: 15px;
            margin-right: 15px;
        }

        .blockResponsiveness {
            max-height: 424px;
            overflow: auto;
            overflow-x: hidden;
        }

        .coachNotificationBlockResponsiveness {
            max-height: 376px;
            overflow: auto;
            overflow-x: hidden;
        }

        .tableResponsiveness {
            border: none;
            border-collapse: collapse;
        }

        .coachPLayersBlock {
            margin-left: 15px;
            margin-right: 15px;
        }

        /* Coach Dashboard - Start */
        .xe-widget.xe-counter .xe-coach-player i {
            display: block;
            background: #c075c6;
            color: #fff;
            text-align: center;
            font-size: 37px;
            line-height: 74px;
            width: 72px;
            height: 72px;
            -webkit-border-radius: 50%;
            -webkit-background-clip: padding-box;
            -moz-border-radius: 50%;
            -moz-background-clip: padding;
            border-radius: 50%;
            background-clip: padding-box;
        }

        .xe-widget.xe-counter .xe-coach-report i {
            display: block;
            background: #4a70db;
            color: #fff;
            text-align: center;
            font-size: 37px;
            line-height: 74px;
            width: 72px;
            height: 72px;
            -webkit-border-radius: 50%;
            -webkit-background-clip: padding-box;
            -moz-border-radius: 50%;
            -moz-background-clip: padding;
            border-radius: 50%;
            background-clip: padding-box;
        }

        .xe-widget.xe-counter .xe-coach-class i {
            display: block;
            background: #f8b867;
            color: #fff;
            text-align: center;
            font-size: 37px;
            line-height: 74px;
            width: 72px;
            height: 72px;
            -webkit-border-radius: 50%;
            -webkit-background-clip: padding-box;
            -moz-border-radius: 50%;
            -moz-background-clip: padding;
            border-radius: 50%;
            background-clip: padding-box;
        }

        .xe-widget.xe-counter .xe-coach-income i {
            display: block;
            background: #fc4440;
            color: #fff;
            text-align: center;
            font-size: 37px;
            line-height: 74px;
            width: 72px;
            height: 72px;
            -webkit-border-radius: 50%;
            -webkit-background-clip: padding-box;
            -moz-border-radius: 50%;
            -moz-background-clip: padding;
            border-radius: 50%;
            background-clip: padding-box;
        }

        /* Coach Dashboard - End */
    </style>
    <div class="row">
        <div class="col-md-12">
            @if(\Illuminate\Support\Facades\Session::has('success'))
                <div class="alert alert-success" id="message-alert">
                    <button type="button" class="close" data-dismiss="alert"><span
                                aria-hidden="true">×</span> <span
                                class="sr-only">Close</span></button>
                    {{\Illuminate\Support\Facades\Session::get('success')}}
                </div>
            @elseif(\Illuminate\Support\Facades\Session::has('error'))
                <div class="alert alert-danger" id="message-alert">
                    {{\Illuminate\Support\Facades\Session::get('error')}}
                </div>
            @endif
        </div>
    </div>
    @if($Role == 1 || $Role == 2 || $Role == 3)
        <div class="row" id="dashboardPage">
            @foreach($Announcement as $announcement)
                <?php
                $CheckUserAnnouncementReadStatus = \Illuminate\Support\Facades\DB::table('read_announcements')
                    ->where('announcement_id', $announcement->id)
                    ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->count();
                ?>
                @if($CheckUserAnnouncementReadStatus == 0)
                    @if(\Carbon\Carbon::parse($announcement->expiration) >= \Carbon\Carbon::now())
                        <div class="col-md-12">
                            <input type="hidden" name="announcement_id" id="announcement_id"
                                   value="{{$announcement->id}}"/>
                            <div class="alert alert-success text-center" role="alert">
                                <div class="row">
                                    <div class="col-md-11">
                                        {{$announcement->message}}
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                                                onclick="ReadAnnouncement();" style="color: #ffffff; opacity: 1;">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endif
            @endif
        @endforeach

        <!-- Stat Cards - START -->
            <div class="col-sm-2">
                <div class="xe-widget xe-counter" data-count=".num"
                     data-duration="4" data-easing="true">
                    <div class="xe-icon"><i class="fas fa-running"></i></div>
                    <div class="xe-label"><strong class="num">{{$TotalAthletes}}</strong> <span>Athletes</span></div>
                </div>
                <div class="xe-widget xe-counter xe-counter-purple" data-count=".num"
                     data-duration="4" data-easing="true">
                    <div class="xe-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="xe-label"><strong class="num">{{$TotalCoaches}}</strong> <span>Coaches</span></div>
                </div>
                <div class="xe-widget xe-counter xe-counter-info" data-count=".num"
                     data-duration="4" data-easing="true">
                    <div class="xe-icon"><i class="fas fa-child"></i></div>
                    <div class="xe-label"><strong class="num">{{$TotalParents}}</strong> <span>Parents</span></div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="xe-widget xe-counter xe-counter-yellow" data-count=".num"
                     data-duration="4" data-easing="true">
                    <div class="xe-icon"><i class="fas fa-hand-holding"></i></div>
                    <div class="xe-label"><strong class="num">${{$TotalHold}}</strong> <span>Hold</span></div>
                </div>
                <div class="xe-widget xe-counter xe-counter-red" data-count=".num"
                     data-duration="4" data-easing="true">
                    <div class="xe-icon"><i class="fa fa-times" aria-hidden="true"></i></div>
                    <div class="xe-label"><strong class="num">${{$TotalCancel}}</strong> <span>Cancel</span></div>
                </div>
                <div class="xe-widget xe-counter xe-counter-orange" data-count=".num"
                     data-duration="4" data-easing="true">
                    <div class="xe-icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                    <div class="xe-label"><strong class="num">${{$TotalDueFee}}</strong> <span>Fee Due</span></div>
                </div>
            </div>
            <!-- Stat Cards - END -->

            <!-- Pie Graph Expense/Earning - START -->
            <div class="col-md-4">
                <div class="chart-item-bg">
                    <div class="panel panel-default">
                        <input type="hidden" name="earning_spending_title" id="earning_spending_title"
                               value="Earning vs Spending in {{\Carbon\Carbon::now()->format('Y')}}">
                        <input type="hidden" name="FinanceTypes" id="FinanceTypes"
                               value="{{json_encode($FinanceTypes)}}">
                        <input type="hidden" name="FinanceAmounts" id="FinanceAmounts"
                               value="{{json_encode($FinanceAmounts)}}">
                        <div class="panel-body">
                            <div id="earning-expense-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pie Graph Expense/Earning - END -->

            <!-- Earning Graph -->
            <div class="col-sm-4">
                <div class="chart-item-bg">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Earnings <span id="totalMonthlyEarning"></h5>
                                    <input type="hidden" name="EarningAmount" id="EarningAmount"
                                           value="{{$TotalEarnings}}">
                                    <input type="hidden" name="MembershipAmounts" id="MembershipAmounts"
                                           value="{{json_encode($MembershipAmounts)}}">
                                    <input type="hidden" name="InvoicesAmounts" id="InvoicesAmounts"
                                           value="{{json_encode($InvoicesAmounts)}}">
                                </div>
                                <div class="col-md-6">
                                    <div class="dropdown" style="float:right;">
                                        <button class="btn dropdown-toggle monthDropdownMenuStyling" type="button"
                                                id="earning-dropdown-value" data-toggle="dropdown">
                                            {{\Carbon\Carbon::now()->format('F')}}
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">January</a>
                                            </li>
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">February</a>
                                            </li>
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">March</a>
                                            </li>
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">April</a>
                                            </li>
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">May</a></li>
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">June</a>
                                            </li>
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">July</a>
                                            </li>
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">August</a>
                                            </li>
                                            <li><a href="javascript:void(0);"
                                                   class="earning-dropdown-item">September</a></li>
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">October</a>
                                            </li>
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">November</a>
                                            </li>
                                            <li><a href="javascript:void(0);" class="earning-dropdown-item">December</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div id="earnings-chart" style="height: 220px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <!-- Expense Graph - Start -->
                <div class="chart-item-bg">
                    <div class="panel panel-default">
                        <input type="hidden" name="expense_title" id="expense_title"
                               value="Finance in {{\Carbon\Carbon::now()->format('Y')}} (${{$TotalExpenses}})">
                        <input type="hidden" name="ExpenseAmounts" id="ExpenseAmounts"
                               value="{{json_encode($ExpenseAmounts)}}">
                        <div class="panel-body">
                            <div id="finance-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- Expense Graph - End -->
            </div>
            <div class="col-sm-6">
                <!-- Player Category Graph - Start -->
                <div class="chart-item-bg">
                    <div class="panel panel-default">
                        <input type="hidden" name="CategoryAmounts" id="CategoryAmounts"
                               value="{{json_encode($CategoryAmounts)}}">
                        <input type="hidden" name="CategoryPlayerAmounts" id="CategoryPlayerAmounts"
                               value="{{json_encode($CategoryPlayerAmounts)}}">
                        <div class="panel-body">
                            <div id="playercategory-chart"></div>
                        </div>
                    </div>
                </div>
                <!-- Player Category Graph - End -->
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Event Calender</h5>
                    </div>
                    <div class="panel-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <!-- Notifications - Start -->
            <div class="col-sm-6">
                <div class="xe-widget xe-counter">
                    <h4 class="pl-4 mb-4">Announcement</h4>
                    <div class="blockResponsiveness">
                        <div class="row mt-2 mb-2 ml-2 mr-2">
                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-warning">16 Feb, 2022</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>Jennefer Lofez / 5 min ago</p>
                                <hr>
                            </div>
                            <!-- Card -->

                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-danger">8 Mar, 2021</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>John Doe / 15 min ago</p>
                                <hr>
                            </div>
                            <!-- Card -->

                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-success">29 Jun, 2021</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>Petter Berg / 1 hour ago</p>
                                <hr>
                            </div>
                            <!-- Card -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Notifications - End -->
        </div>

    @elseif($Role == 4)
        <div class="row" id="CoachDashoboardPage">
            <!-- Statistics Cards - Start -->
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="xe-widget xe-counter">
                            <center>
                                <div class="xe-coach-player mt-5 mb-4"><i class="fas fa-running"></i></div>
                                <div class="">
                                    <p style="font-size: 24px;font-weight: 500;color:black;"
                                       class="mb-3">{{$Coach_TotalStudents}}</p>
                                    <p class="mb-5 mt-3" style="font-size: 16px;">Total Players</p>
                                </div>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="xe-widget xe-counter">
                            <center>
                                <div class="xe-coach-report mt-5 mb-4"><i class="fa fa-file-pdf-o"></i></div>
                                <div class="">
                                    <p style="font-size: 24px;font-weight: 500;color:black;"
                                       class="mb-3">{{$Coach_TotalReports}}</p>
                                    <p class="mb-5 mt-3" style="font-size: 16px;">Total Reports</p>
                                </div>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="xe-widget xe-counter">
                            <center>
                                <div class="xe-coach-class mt-5 mb-4"><i class="fa fa-graduation-cap"></i></div>
                                <div class="">
                                    <p style="font-size: 24px;font-weight: 500;color:black;"
                                       class="mb-3">{{$Coach_TotalNewPlayers}}</p>
                                    <p class="mb-5 mt-3" style="font-size: 16px;">New Players</p>
                                </div>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="xe-widget xe-counter">
                            <center>
                                <div class="xe-coach-income mt-5 mb-4"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="">
                                    <p style="font-size: 24px;font-weight: 500;color:black;"
                                       class="mb-3">{{$Coach_TotalLocation}}</p>
                                    <p class="mb-5 mt-3" style="font-size: 16px;">Location</p>
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Statistics Cards - End -->

            <!-- Pie Chart - Start -->
            <div class="col-md-4">
                <div class="chart-item-bg">
                    <div class="panel panel-default">
                        <input type="hidden" name="TotalCategories" id="TotalCategories" value="{{$category_list}}"/>
                        <input type="hidden" name="TotalCategoryPlayers" id="TotalCategoryPlayers"
                               value="{{$category_player_list}}"/>
                        <div class="panel-body">
                            <div id="playerreport-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pie Chart - End -->

            <!-- Notifications - Start -->
            <div class="col-md-4">
                <div class="xe-widget xe-counter">
                    <h4 class="pl-4 mb-4">Announcement</h4>
                    <div class="coachNotificationBlockResponsiveness">
                        <div class="row mt-2 mb-2 ml-2 mr-2">
                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-warning">16 Feb, 2022</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>Jennefer Lofez / 5 min ago</p>
                                <hr>
                            </div>
                            <!-- Card -->

                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-danger">8 Mar, 2021</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>John Doe / 15 min ago</p>
                                <hr>
                            </div>
                            <!-- Card -->

                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-success">29 Jun, 2021</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>Petter Berg / 1 hour ago</p>
                                <hr>
                            </div>
                            <!-- Card -->

                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-warning">16 Feb, 2022</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>Jennefer Lofez / 5 min ago</p>
                                <hr>
                            </div>
                            <!-- Card -->

                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-warning">16 Feb, 2022</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>Jennefer Lofez / 5 min ago</p>
                                <hr>
                            </div>
                            <!-- Card -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Notifications - End -->
        </div>

        <!-- My Players - Start -->
        <div class="row">
            <div class="col-md-12">
                <div class="xe-widget xe-counter">
                    <h4 class="pl-4 mb-4">All Players</h4>
                    <div class="coachNotificationBlockResponsiveness">
                        <div class="row mt-2 mb-2 coachPLayersBlock">
                            <table class="table w-100 tbl-responsive" id="coachAllPlayersTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 8%;">Id</th>
                                    <th style="width: 12%;">Photo</th>
                                    <th style="width: 20%;">Player</th>
                                    <th style="width: 12%;">Gender</th>
                                    <th style="width: 13%;">Position</th>
                                    <th style="width: 15%;">Training Days</th>
                                    <th style="width: 15%;">Admission Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- My Players - End -->

    @elseif($Role == 5)
        <?php
        $LeadConversionCheck = \Illuminate\Support\Facades\DB::table('lead_conversions')
            ->where('parent_id', '=', \Illuminate\Support\Facades\Auth::id())
            ->get();
        $CheckForDashboardData = true;
        $Lead = null;
        if (sizeof($LeadConversionCheck) > 0) {
            if ($LeadConversionCheck[0]->conversion_type == 2) {
                $CheckForDashboardData = false;
            }
            $Lead = \Illuminate\Support\Facades\DB::table('leads')
                ->where('id', '=', $LeadConversionCheck[0]->lead_id)
                ->get();
        }
        ?>
        @if($CheckForDashboardData)
            <div class="row" id="ParentDashoboardPage">
                <div class="col-md-3">
                    <div class="xe-widget xe-counter xe-counter-red" data-count=".num" data-decimal=","
                         data-easing="true">
                        <div class="xe-icon"><i class="fal fa-money-bill"></i></div>
                        <div class="xe-label"><strong class="num">${{$Parent_DueFees}}</strong> <span>Due Fees</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="xe-widget xe-counter xe-counter-purple" data-count=".num" data-decimal=","
                         data-easing="true">
                        <div class="xe-icon"><i class="fa fa-bell"></i></div>
                        <div class="xe-label"><strong class="num">{{$Parent_Notifications}}</strong>
                            <span>Notifications</span></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="xe-widget xe-counter xe-counter-yellow" data-count=".num" data-decimal=","
                         data-easing="true">
                        <div class="xe-icon"><i class="fa fa-graduation-cap"></i></div>
                        <div class="xe-label"><strong class="num">{{$Parent_Evaluations}}</strong>
                            <span>Evaluations</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="xe-widget xe-counter xe-counter-info" data-count=".num" data-decimal=","
                         data-easing="true">
                        <div class="xe-icon"><i class="fas fa-money-bill-alt"></i></div>
                        <div class="xe-label"><strong class="num">${{$Parent_Expenses}}</strong> <span>Expenses</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- My Kids Cards - Start -->
                <div class="col-md-6">
                    <div class="xe-widget xe-counter">
                        <h4 class="pl-4 mb-4">My Players</h4>
                        <div class="blockResponsiveness">
                        @foreach($MyKids as $kid)
                            <!-- Kid -->
                                <div class="row mt-2 mb-2 kidBlock">
                                    <div class="col-md-1">
                                        <?php
                                        $ProfilePic = asset('public/assets/images/user.png');
                                        if ($kid->profile_pic != "") {
                                            $ProfilePic = asset('public/storage/user-profiles/' . $kid->profile_pic);
                                        }
                                        ?>
                                        <img class="mt-4 ml-3 pt-3" src="{{$ProfilePic}}" alt="Kid Profile Picture"
                                             width="50">
                                    </div>
                                    <div class="col-md-11">
                                        <table class="table myKidsTable tableResponsiveness ml-5 mt-4">
                                            <tbody>
                                            <tr>
                                                <th class="myKidsTableHeading">Player ID:</th>
                                                <td class="myKidsTableData">{{$kid->userId}}</td>
                                            </tr>
                                            <tr>
                                                <th class="myKidsTableHeading">Name:</th>
                                                <td class="myKidsTableData">{{$kid->firstName}} {{$kid->lastName}}</td>
                                            </tr>
                                            <tr>
                                                <th class="myKidsTableHeading">Training Days:</th>
                                                <td class="myKidsTableData">{{$kid->athletesTrainingDays}}</td>
                                            </tr>
                                            <tr>
                                                <th class="myKidsTableHeading">Admission Date:</th>
                                                <td class="myKidsTableData">{{Carbon\Carbon::parse($kid->created_at)->format("m/d/Y")}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- Kid -->
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- My Kids Cards - End -->

                <!-- All Expenses - Start -->
                <div class="col-md-6">
                    <div class="xe-widget xe-counter">
                        <h4 class="pl-4 mb-4">Monthly Fees</h4>
                        <div class="blockResponsiveness">
                            <div class="row mt-2 mb-2 parentExpenseBlock">
                                <table class="table w-100 tbl-responsive" id="parentsAllExpensesTable">
                                    <thead>
                                    <tr class="replace-inputs">
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 15%;">Id</th>
                                        <th style="width: 30%;">Expense</th>
                                        <th style="width: 20%;">Amount</th>
                                        <th style="width: 15%;">Status</th>
                                        <th style="width: 15%;">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- All Expenses - End -->
            </div>

            <div class="row">
                <!-- Notifications - Start -->
                <div class="col-md-6">
                    <div class="xe-widget xe-counter">
                        <h4 class="pl-4 mb-4">Announcement</h4>
                        <div class="blockResponsiveness">
                            <div class="row mt-2 mb-2 ml-2 mr-2">
                                <!-- Card -->
                                <div class="col-md-12">
                                    <span class="badge badge-warning">16 Feb, 2022</span>
                                    <h5 style="font-weight: 600;">Great School manage mene escom text of the
                                        printing</h5>
                                    <p>Jennefer Lofez / 5 min ago</p>
                                    <hr>
                                </div>
                                <!-- Card -->

                                <!-- Card -->
                                <div class="col-md-12">
                                    <span class="badge badge-danger">8 Mar, 2021</span>
                                    <h5 style="font-weight: 600;">Great School manage mene escom text of the
                                        printing</h5>
                                    <p>John Doe / 15 min ago</p>
                                    <hr>
                                </div>
                                <!-- Card -->

                                <!-- Card -->
                                <div class="col-md-12">
                                    <span class="badge badge-success">29 Jun, 2021</span>
                                    <h5 style="font-weight: 600;">Great School manage mene escom text of the
                                        printing</h5>
                                    <p>Petter Berg / 1 hour ago</p>
                                    <hr>
                                </div>
                                <!-- Card -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Notifications - End -->

                <!-- Evaluation Reports - Start -->
                <div class="col-md-6">
                    <div class="xe-widget xe-counter">
                        <h4 class="pl-4 mb-4">Evaluations</h4>
                        <div class="blockResponsiveness">
                            <div class="row mt-2 mb-2 parentExpenseBlock">
                                <table class="table w-100 tbl-responsive" id="parentsEvaluationReportTable">
                                    <thead>
                                    <tr class="replace-inputs">
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 15%;">Id</th>
                                        <th style="width: 30%;">Player</th>
                                        <th style="width: 20%;">Grade</th>
                                        <th style="width: 30%;">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Evaluation Reports - End -->
            </div>
        @else
            <style>
                .button-green-1 {
                    background-color: green;
                    border-color: green;
                    color: #fff;
                }

                .button-green-2 {
                    background-color: green;
                    border-color: green;
                    color: #fff;
                    padding: 12px 8px 12px 8px;
                }

                .button-blue-1 {
                    background-color: deepskyblue;
                    border-color: deepskyblue;
                    color: #fff;
                }

                .button-blue-2 {
                    background-color: deepskyblue;
                    border-color: deepskyblue;
                    color: #fff;
                    padding: 12px 8px 12px 8px;
                }

                .btn:hover, .btn:focus, .btn:active {
                    color: rgba(255, 255, 255, 0.9);
                    box-shadow: none;
                }
            </style>

            <div class="row mb-5">
                <div class="col-md-offset-3 col-md-6 mb-3">
                    <img src="{{ asset('public/assets/images/Logo.jpg')}}" alt="logo-small"
                         style="width: 125px; margin: auto;" class="img-responsive">
                </div>

                <div class="col-md-offset-3 col-md-6 mb-2">
                    <h1 class="text-center mb-0">Welcome</h1>
                </div>

                <div class="col-md-offset-3 col-md-6" style="margin-bottom: 80px;">
                    <h4 class="text-center">You are a click away to become part of the MSA family</h4>
                </div>

                @if($Lead[0]->lead_status != 7)
                    <div class="col-md-offset-2 col-md-4 text-center">
                        <?php
                        $RegisterUrl = route('dashboard.registration.complete');
                        $UpdateRegistration = route('dashboard.registration.update');
                        ?>
                        <div class="btn-group mt-5" role="group"
                             aria-label="Basic example" onclick="window.location.href='{{$RegisterUrl}}';">
                            <button type="button"
                                    class="btn btn-lg button-green-1 mb-0">Finish Registration
                            </button>
                            <button type="button" class="btn button-green-2 mb-0"><i
                                        class="fas fa-angle-right"></i></button>
                        </div>
                    </div>

                    <div class="col-md-4 text-center"
                         style="background-image: url('{{ asset('public/assets/images/Ball.png')}}'); background-repeat: no-repeat; background-position: bottom; background-size: contain; height: 40vh;">
                        <div class="btn-group mt-5" role="group"
                             aria-label="Basic example"
                             onclick="window.location.href='{{$UpdateRegistration}}';">
                            <button type="button"
                                    class="btn btn-lg button-blue-1 mb-0">Update Free Class
                            </button>
                            <button type="button" class="btn button-blue-2 mb-0"><i
                                        class="fas fa-angle-right"></i></button>
                        </div>
                    </div>
                @else
                    <div class="col-md-12 text-center">
                        <?php
                        $RegisterUrl = route('dashboard.registration.complete');
                        $UpdateRegistration = route('dashboard.registration.update');
                        ?>
                        <div class="btn-group mt-5" role="group"
                             aria-label="Basic example" onclick="window.location.href='{{$RegisterUrl}}';">
                            <button type="button"
                                    class="btn btn-lg button-green-1 mb-0">Finish Registration
                            </button>
                            <button type="button" class="btn button-green-2 mb-0"><i
                                        class="fas fa-angle-right"></i></button>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @elseif($Role == 6)
        <input type="hidden" name="totalAttendedDays" id="totalAttendedDays" value="{{$TotalAttendedDays}}"/>
        <input type="hidden" name="totalPresentDays" id="totalPresentDays" value="{{$TotalPresent}}"/>
        <input type="hidden" name="totalLate" id="totalLate" value="{{$TotalLate}}"/>
        <input type="hidden" name="TotalAbsent" id="TotalAbsent" value="{{$TotalAbsent}}"/>

        <!-- Player Dashboard -->
        <div class="row" id="PlayerDashboardPage">
            <!-- Player Profile -->
            <div class="col-md-6">
                <div class="xe-widget xe-counter">
                    <h4 class="pl-4 mb-4">My Players</h4>
                    <div class="blockResponsiveness">
                        <div class="row mt-2 mb-2 kidBlock">
                            <div class="col-md-1">
                                <?php
                                $ProfilePic = asset('public/assets/images/user.png');
                                ?>
                                <img class="mt-4 ml-3 pt-3" src="{{$ProfilePic}}" alt="Kid Profile Picture"
                                     width="50">
                            </div>
                            <div class="col-md-11">
                                <table class="table myKidsTable tableResponsiveness ml-5 mt-4">
                                    <tbody>
                                    <tr>
                                        <th class="myKidsTableHeading">Player ID:</th>
                                        <td class="myKidsTableData">{{$PlayerProfile[0]->userId}}</td>
                                    </tr>
                                    <tr>
                                        <th class="myKidsTableHeading">Name:</th>
                                        <td class="myKidsTableData">{{$PlayerProfile[0]->firstName}} {{$PlayerProfile[0]->lastName}}</td>
                                    </tr>
                                    <tr>
                                        <th class="myKidsTableHeading">DOB:</th>
                                        <td class="myKidsTableData">{{Carbon\Carbon::parse($PlayerProfile[0]->dob)->format("m/d/Y")}}</td>
                                    </tr>
                                    <tr>
                                        <th class="myKidsTableHeading">Level:</th>
                                        <td class="myKidsTableData">{{$PlayerProfile[0]->PlayerLevel}}</td>
                                    </tr>
                                    <tr>
                                        <th class="myKidsTableHeading">Category:</th>
                                        <td class="myKidsTableData">{{$PlayerProfile[0]->PlayerCategory}}</td>
                                    </tr>
                                    <tr>
                                        <th class="myKidsTableHeading">Position:</th>
                                        <td class="myKidsTableData">{{$PlayerProfile[0]->PlayerPosition}}</td>
                                    </tr>
                                    <tr>
                                        <th class="myKidsTableHeading">Training Days:</th>
                                        <td class="myKidsTableData">{{$PlayerProfile[0]->athletesTrainingDays}}</td>
                                    </tr>
                                    <tr>
                                        <th class="myKidsTableHeading">Admission Date:</th>
                                        <td class="myKidsTableData">{{Carbon\Carbon::parse($PlayerProfile[0]->created_at)->format("m/d/Y")}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Player Profile -->

            <!-- Player Statistics -->
            <div class="col-md-2">
                <div class="xe-widget xe-counter xe-counter-red" data-count=".num" data-decimal="," data-easing="true">
                    <div class="xe-icon"><i class="fa fa-file"></i></div>
                    <div class="xe-label"><strong class="num">{{$Evaluations}}</strong><span>@if($Evaluations == 1)
                                Report @else Report @endif</span></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="xe-widget xe-counter xe-counter-purple" data-count=".num" data-decimal=","
                     data-easing="true">
                    <div class="xe-icon"><i class="fa fa-bell"></i></div>
                    <div class="xe-label"><strong class="num">7</strong>
                        <span>Announcement</span></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="xe-widget xe-counter xe-counter-yellow" data-count=".num" data-decimal=","
                     data-easing="true">
                    <div class="xe-icon"><i class="fa fa-percent"></i></div>
                    <div class="xe-label"><strong class="num">{{$AttendancePercent}}%</strong> <span>Attendance</span>
                    </div>
                </div>
            </div>
            <!-- Player Statistics -->

            <!-- Evaluation Reports - Start -->
            <div class="col-md-6">
                <div class="xe-widget xe-counter">
                    <h4 class="pl-4 mb-4">Evaluations</h4>
                    <div class="blockResponsiveness">
                        <div class="row mt-2 mb-2 parentExpenseBlock">
                            <table class="table w-100 tbl-responsive" id="playerEvaluationReportTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">Id</th>
                                    <th style="width: 30%;">Player</th>
                                    <th style="width: 20%;">Grade</th>
                                    <th style="width: 30%;">Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Evaluation Reports - End -->
        </div>
        <div class="row">
            <!-- Attendance Pie Graph - Start -->
            <div class="col-md-6">
                <div class="chart-item-bg">
                    <div class="panel panel-default">
                        <input type="hidden" name="earning_spending_title" id="earning_spending_title"
                               value="Attendance in {{\Carbon\Carbon::now()->format('Y')}}">
                        <input type="hidden" name="AttendanceTypes" id="AttendanceTypes"
                               value="">
                        <input type="hidden" name="AttendanceRecord" id="AttendanceRecord"
                               value="">
                        <div class="panel-body">
                            <div id="attendance-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Attendance Pie Graph - End -->
            <!-- <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Event Calender</h5>
                    </div>
                    <div class="panel-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div> -->
            <!-- Notifications - Start -->
            <div class="col-sm-6">
                <div class="xe-widget xe-counter">
                    <h4 class="pl-4 mb-4">Announcement</h4>
                    <div class="blockResponsiveness">
                        <div class="row mt-2 mb-2 ml-2 mr-2">
                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-warning">16 Feb, 2022</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>Jennefer Lofez / 5 min ago</p>
                                <hr>
                            </div>
                            <!-- Card -->

                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-danger">8 Mar, 2021</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>John Doe / 15 min ago</p>
                                <hr>
                            </div>
                            <!-- Card -->

                            <!-- Card -->
                            <div class="col-md-12">
                                <span class="badge badge-success">29 Jun, 2021</span>
                                <h5 style="font-weight: 600;">Great School manage mene escom text of the printing</h5>
                                <p>Petter Berg / 1 hour ago</p>
                                <hr>
                            </div>
                            <!-- Card -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Notifications - End -->
        </div>
    @endif

    @include('dashboard.users.changePasswordModal')
    @if(\Illuminate\Support\Facades\Auth::user()->role_id == 5)
        @include('dashboard.includes.CompletePlayerProfile')
    @endif
    @include('dashboard.includes.scripts')
@endsection
