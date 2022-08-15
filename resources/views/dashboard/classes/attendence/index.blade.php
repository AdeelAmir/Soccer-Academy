@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        .success {
            color: #3ea465;
        }

        .danger {
            color: #db3c46;
        }

        .warning {
            color: #fec354;
        }

        .text-black {
            color: #000000;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .tableRowSetting {
            border-bottom: 1px solid #dee2e6;
        }

        @media (min-width: 992px) {
            .responsive {

            }

            .head {

            }

            .head_mob {
                display: none;
            }

        }


        @media (max-width: 767px) {
            .responsive {
                overflow-x: auto;
            }

            .head {
                display: none;
            }

            .head_mob {

            }

            .padd {
                padding: 4px 9px;
            }
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <!-- <div class="col-md-2"></div> -->
            <div class="col-md-12" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3 head">Classes > Attendance > <span
                                    class="text-primary">{{$ClassDetails[0]->title}}</span></h3>
                        <h4 class="panel-title pt-2 head_mob" style="font-size: 15px;">Attendance > <span
                                    class="text-primary">{{$ClassDetails[0]->title}}</span></h4>
                        @if($Role == 1 || $Role == 4)
                            <button type="button" class="btn btn-primary mb-0 mr-2 padd" style="float: right;"
                                    onclick="window.location.href='{{route('classes')}}';"><i
                                        class="fas fa-arrow-left"></i></button>
                            <button type="button" class="btn btn-primary mb-0 mr-2 padd" style="float: right;"
                                    data-toggle="tooltip" title="Create New Attendance"
                                    onclick="window.location.href='{{route('classes.attendence.add',['id' => $ClassId])}}';">
                                <i
                                        class="fas fa-plus-square"></i></button>
                        @endif
                    </div>
                    <div class="panel-body">
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
                        <div class="row">

                            <div class="col-md-12">
                                <form action="{{route('classes.month.attendence')}}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="class_id" id="attendence_class_id" value="{{$ClassId}}"/>
                                    <div class="row mb-3">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label" for="dob">Attendance</label>
                                                <select class="form-control" name="month" required>
                                                    <option value="">Select Month</option>
                                                    <option value="January" <?php if ($CurrentMonth == "January") {
                                                        echo "selected";
                                                    } ?>>January
                                                    </option>
                                                    <option value="February" <?php if ($CurrentMonth == "February") {
                                                        echo "selected";
                                                    } ?>>Febuary
                                                    </option>
                                                    <option value="March" <?php if ($CurrentMonth == "March") {
                                                        echo "selected";
                                                    } ?>>March
                                                    </option>
                                                    <option value="April" <?php if ($CurrentMonth == "April") {
                                                        echo "selected";
                                                    } ?>>April
                                                    </option>
                                                    <option value="May" <?php if ($CurrentMonth == "May") {
                                                        echo "selected";
                                                    } ?>>May
                                                    </option>
                                                    <option value="June" <?php if ($CurrentMonth == "June") {
                                                        echo "selected";
                                                    } ?>>June
                                                    </option>
                                                    <option value="July" <?php if ($CurrentMonth == "July") {
                                                        echo "selected";
                                                    } ?>>July
                                                    </option>
                                                    <option value="August" <?php if ($CurrentMonth == "August") {
                                                        echo "selected";
                                                    } ?>>August
                                                    </option>
                                                    <option value="September" <?php if ($CurrentMonth == "September") {
                                                        echo "selected";
                                                    } ?>>September
                                                    </option>
                                                    <option value="October" <?php if ($CurrentMonth == "October") {
                                                        echo "selected";
                                                    } ?>>October
                                                    </option>
                                                    <option value="November" <?php if ($CurrentMonth == "November") {
                                                        echo "selected";
                                                    } ?>>November
                                                    </option>
                                                    <option value="December" <?php if ($CurrentMonth == "December") {
                                                        echo "selected";
                                                    } ?>>December
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="submit" class="btn btn-primary" name="submitBtn" value="Filter"
                                                   style="margin-top: 2.3rem;margin-left:-1rem;"/>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-12">
                                <h4 class="text-black">{{$CurrentMonthYear}}</h4>
                            </div>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="table w-100 tbl-responsive" id="">
                                <thead>
                                <tr class="replace-inputs">
                                    <th>Students</th>
                                    <?php
                                    for ($i = 0; $i < $TotalDays; $i++) {
                                        echo "<th>" . ($i + 1) . "</th>";
                                    }
                                    ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $status = "";
                                foreach ($PlayerAttendence as $key => $player) {
                                if ($player->middleName != "") {
                                    $PlayerName = $player->firstName . " " . $player->middleName . " " . $player->lastName;
                                } else {
                                    $PlayerName = $player->firstName . " " . $player->lastName;
                                }
                                ?>
                                <tr>
                                    <td>{{$PlayerName}}</td>
                                    <?php
                                    $startdate = \Carbon\Carbon::parse($first_day_this_month)->format("Y-m-d");
                                    for ($i = 0; $i < $TotalDays; $i++) {
                                        $status = "";
                                        foreach ($AttendenceRecord as $index => $attendence) {
                                            if ($player->id == $attendence->player_id && $_ClassId == $attendence->class_id && $attendence->attendence_date == $startdate) {
                                                $status = $attendence->status;
                                                $remarks = $attendence->remarks;
                                            }
                                        }
                                        if ($status == "P") {
                                            if ($remarks != "") {
                                                echo "<td><i class='fa fa-check success' aria-hidden='true' data-toggle='tooltip' title='" . $remarks . "'></i></td>";
                                            } else {
                                                echo "<td><i class='fa fa-check success' aria-hidden='true'></i></td>";
                                            }
                                        } elseif ($status == "A") {
                                            if ($remarks != "") {
                                                echo "<td><i class='fa fa-check danger' aria-hidden='true' data-toggle='tooltip' title='" . $remarks . "'></i></td>";
                                            } else {
                                                echo "<td><i class='fa fa-check danger' aria-hidden='true'></i></td>";
                                            }
                                        } elseif ($status == "L") {
                                            if ($remarks != "") {
                                                echo "<td><i class='fa fa-check warning' aria-hidden='true' data-toggle='tooltip' title='" . $remarks . "'></i></td>";
                                            } else {
                                                echo "<td><i class='fa fa-check warning' aria-hidden='true'></i></td>";
                                            }
                                        } else {
                                            echo "<td> - </td>";
                                        }
                                        $startdate = \Carbon\Carbon::parse($startdate)->addDays(1)->format("Y-m-d");
                                    }
                                    ?>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.classes.delete')
    @include('dashboard.classes.statusConfirmationModal')
    @include('dashboard.classes.scripts')
@endsection
