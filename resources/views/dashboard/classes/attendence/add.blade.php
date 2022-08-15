@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        .radio-success:after {
            width: 15px;
            height: 15px;
            border-radius: 15px;
            top: 0px;
            left: -1px;
            position: relative;
            background-color: white;
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid #a8a8a8;
        }

        .radio-success:checked:after {
            width: 17px;
            height: 17px;
            border-radius: 15px;
            top: -2px;
            left: -1px;
            position: relative;
            background-color: #2c8754;
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
        }

        .radio-danger:after {
            width: 15px;
            height: 15px;
            border-radius: 15px;
            top: 0px;
            left: -1px;
            position: relative;
            background-color: white;
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid #a8a8a8;
        }

        .radio-danger:checked:after {
            width: 17px;
            height: 17px;
            border-radius: 15px;
            top: -2px;
            left: -1px;
            position: relative;
            background-color: #db3c46;
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
        }

        .radio-warning:after {
            width: 15px;
            height: 15px;
            border-radius: 15px;
            top: 0px;
            left: -1px;
            position: relative;
            background-color: white;
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid #a8a8a8;
        }

        .radio-warning:checked:after {
            width: 18px;
            height: 18px;
            border-radius: 15px;
            top: -2px;
            left: -1px;
            position: relative;
            background-color: #fec354;
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
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
            <div class="col-md-3"></div>
            <div class="col-md-6" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3 head">Classes > Attendance > {{$ClassDetails[0]->title}} > <span
                                    class="text-primary">Add</span></h3>
                        <h3 class="panel-title pt-3 head_mob" style="font-size: 15px">Attendance
                            > {{$ClassDetails[0]->title}} > <span class="text-primary">Add</span></h3>
                        @if($Role == 1)
                            <button type="button" class="btn btn-primary mb-0 mr-2 padd" style="float: right;"
                                    onclick="window.location.href='{{route('classes.attendence', ['id' => $ClassId])}}';">
                                <i
                                        class="fas fa-arrow-left"></i></button>
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

                        <form action="{{route('classes.attendence.edit')}}" method="post"
                              id="classAttendanceEdit"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="class_id" id="attendence_class_id" value="{{$ClassId}}"/>

                            <div class="row mb-3">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="dob">Attendance Date</label>
                                        <input class="form-control attendance_datepicker" name="attendence_date"
                                               id="attendence_date" data-validate="required"
                                               value="<?php if ($AttendenceDate != "") {
                                                   echo $AttendenceDate;
                                               } ?>"
                                               autocomplete="off"
                                               required
                                               onblur="ResetCount();"
                                               onchange="SubmitFilterAttendanceForm();"
                                               placeholder="MM/DD/YYYY"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    {{--<input type="submit" class="btn btn-primary" name="submitBtn" value="Filter"
                                           style="margin-top: 2.3rem;margin-left:-1rem;"/>--}}
                                </div>
                            </div>
                        </form>

                        <form action="{{route('classes.attendence.update')}}" method="post"
                              enctype="multipart/form-data" class="mt-5">
                            @csrf
                            <input type="hidden" name="class_id" id="attendence_class_id" value="{{$ClassId}}"/>
                            <input type="hidden" name="attendence_date" id="attendence_date"
                                   value="{{$AttendenceDate}}"/>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="responsive">
                                        <table class="table w-100 tbl-responsive" id="">
                                            <thead>
                                            <tr class="replace-inputs">
                                                <th style="width: 5%;">#</th>
                                                <th style="width: 25%;">Player</th>
                                                <th style="width: 30%;">Status</th>
                                                <th style="width: 40%;">Remarks</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $counter = 0;
                                            $SrNo = 1;
                                            $PlayerName = "";
                                            $status = "";
                                            $remarks = "";
                                            foreach ($PlayerAttendence as $key => $player) {
                                            $PlayerName = $player->firstName . " " . $player->lastName;
                                            $status = "";
                                            $remarks = "";
                                            foreach ($AttendenceRecord as $index => $attendence) {
                                                if ($player->id == $attendence->player_id && $_ClassId == $attendence->class_id) {
                                                    $status = $attendence->status;
                                                    $remarks = $attendence->remarks;
                                                }
                                            }
                                            ?>
                                            <input type="hidden" name="userid_{{$counter}}" id="userid_{{$counter}}"
                                                   value="{{$player->id}}"/>
                                            <tr>
                                                <td>{{$SrNo}}</td>
                                                <td>
                                                    <?php echo wordwrap($PlayerName, 20, "<br>"); ?>
                                                    <br>
                                                    {{$player->userId}}
                                                    <br>
                                                    <?php echo wordwrap($ClassDetails[0]->LocationName, 15, "<br>"); ?>
                                                </td>
                                                <td>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="attendence_status_{{$counter}}"
                                                               class="radio-success" value="P"
                                                               required
                                                        <?php if ($status == "P") {
                                                            echo "checked";
                                                        } ?>>Present
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="attendence_status_{{$counter}}"
                                                               class="radio-danger" value="A"
                                                        <?php if ($status == "A") {
                                                            echo "checked";
                                                        } ?>>Absent
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="attendence_status_{{$counter}}"
                                                               class="radio-warning" value="L"
                                                        <?php if ($status == "L") {
                                                            echo "checked";
                                                        } ?>>Late
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                           name="remarks_{{$counter}}" id="remarks_{{$counter}}"
                                                           value="{{$remarks}}"/>
                                                </td>
                                            </tr>
                                            <?php $counter++;$SrNo++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center mt-4">
                                    <input type="submit" class="btn btn-primary" name="submitBtn" value="Save"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.classes.delete')
    @include('dashboard.classes.statusConfirmationModal')
    @include('dashboard.classes.scripts')
@endsection
