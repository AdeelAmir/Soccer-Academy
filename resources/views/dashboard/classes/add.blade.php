@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Classes > <span class="text-primary">New</span></h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2" style="float: right;"
                                onclick="window.location.href='{{route('classes')}}';"><i
                                    class="fas fa-arrow-left"></i></button>
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

                        <form action="{{route('classes.store')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="coach">Coach</label>
                                    <select class="form-control select2" name="coach" id="coach">
                                        <option value="" selected>Select Coach</option>
                                        <?php
                                        $CoachName = "";
                                        ?>
                                        @foreach($Coaches as $coach)
                                        <?php
                                        if ($coach->middleName != "") {
                                          $CoachName = $coach->firstName . " " . $coach->middleName . " " . $coach->lastName;
                                        } else {
                                          $CoachName = $coach->firstName . " " . $coach->lastName;
                                        }
                                        ?>
                                        <option value="{{$coach->id}}">{{$CoachName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="category">Category</label>
                                    <select class="form-control select2" name="category" id="category" required>
                                        <option value="" selected>Select Category</option>
                                        @foreach($Categories as $category)
                                            <option value="{{$category->id}}">{{$category->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--<div class="col-md-4 mt-2">
                                    <label for="level">Level</label>
                                    <select class="form-control select2" name="level[]" id="level" multiple required>
                                        @foreach($Levels as $level)
                                            <option value="{{$level->id}}">{{$level->symbol}}</option>
                                        @endforeach
                                    </select>
                                </div>--}}
                                <div class="col-md-4 mt-2">
                                    <label for="location">Location</label>
                                    <select class="form-control select2" name="location" id="location" required>
                                        <option value="">Select</option>
                                        @foreach($Locations as $index => $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label for="is_free">Is Free?</label>
                                    <br>
                                    <input type="checkbox" class="iswitch iswitch-primary" value="0" name="is_free"
                                           id="is_free"> {{--onchange="CheckClassFeeStatus(this.checked);"--}}
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h4 style="color: black;">Schedule</h4>
                                        <hr class="mt-2 mb-2">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="repeater-custom-show-hide">
                                            <div data-repeater-list="timing">
                                                <div data-repeater-item="">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label for="days">Day</label>
                                                            <select name="days"
                                                                    class="form-control">
                                                                <option value="">Select</option>
                                                                <option value="1">Monday</option>
                                                                <option value="2">Tuesday</option>
                                                                <option value="3">Wednesday</option>
                                                                <option value="4">Thursday</option>
                                                                <option value="5">Friday</option>
                                                                <option value="6">Saturday</option>
                                                                <option value="7">Sunday</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4 mb-2">
                                                            <label for="price">Time</label>
                                                            <div class="input-group input-group-minimal">
                                                                <input type="text"
                                                                       class="form-control timepicker"
                                                                       name="time"
                                                                       data-template="dropdown"
                                                                       data-show-seconds="false"
                                                                       data-default-time="08:00 AM"
                                                                       data-show-meridian="true"
                                                                       data-minute-step="5"
                                                                       data-second-step="5"/>
                                                                <div class="input-group-addon">
                                                                    <a href="#">
                                                                        <i class="linecons-clock"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 mb-2">
                                                            <label> &nbsp;</label>
                                                            <div>
                                                                <span data-repeater-delete="" class="btn btn-danger btn-sm">
                                                                    <span class="far fa-trash-alt mr-1"></span> Delete
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <span data-repeater-create="" class="btn btn-primary btn-sm">
                                                        <span class="fa fa-plus"></span> Add
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12 mt-2 text-center">
                                    <input type="submit" class="btn btn-primary " name="submitAddExpenseForm"
                                           id="submitAddUserForm" value="Save"/>
                                </div>
                            </div>

                            {{--<div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="registrationFee">Registration Fee</label>
                                    <input type="text" name="registrationFee" id="registrationFee"
                                           class="form-control" readonly
                                           required/>
                                </div>
                                <div class="col-md-4">
                                    <label for="monthlyFee">Monthly Fee</label>
                                    <input type="text" name="monthlyFee" id="monthlyFee"
                                           class="form-control" readonly
                                           required/>
                                </div>
                            </div>--}}

                            <input type="hidden" name="level" id="level" value="">
                            <input type="hidden" name="registrationFee" id="registrationFee" value="0">
                            <input type="hidden" name="monthlyFee" id="monthlyFee" value="0">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.classes.scripts')
@endsection
