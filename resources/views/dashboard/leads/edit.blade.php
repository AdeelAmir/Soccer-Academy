@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        .dataTables_filter, .dataTables_paginate {
            display: none;
        }
        @media (min-width: 992px){
            .responsive {

            }
            .head {

            }
            .head_mob{
                display:none;
            }
        }


        @media (max-width: 767px) {
            .responsive {
                overflow-x:auto;
            }
            .head {
                display:none;
            }
            .head_mob{

            }
        }
    </style>
    <form action="{{route('leads.save')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="lead_id" id="lead_id" value="{{$Lead[0]->id}}"/>
        <div class="container-fluid" id="EditLeadPage">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="head">Leads > <span class="text-primary">Edit</span></h3>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary mb-0 mr-2" style="float: right;"
                            onclick="window.location.href='{{route('leads')}}';"><i
                                class="fas fa-arrow-left"></i></button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    @if(\Illuminate\Support\Facades\Session::has('success'))
                        <div class="alert alert-success" id="message-alert">
                            <button type="button" class="close" data-dismiss="alert"><span
                                        aria-hidden="true">×</span><span
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

            {{-- Edit Lead --}}
            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="panel panel-default" style="padding: 0px 30px 10px 30px;">
                        <div class="panel-body">

                            <div class="row">
                              <div class="col-md-2">
                                <strong>{{$Lead[0]->lead_number}}</strong>
                                <br>
                                {{$Lead[0]->parentFirstName . ' ' . $Lead[0]->parentLastName}}
                                <br>
                                {{\Illuminate\Support\Carbon::parse($Lead[0]->created_at)->format('m/d/Y')}}
                                <br>
                                {{\Illuminate\Support\Carbon::parse($Lead[0]->created_at)->format('g:i a')}}
                                @if($Lead[0]->is_duplicate == 1)
                                <br>
                                <span class="badge badge-pill badge-danger">Duplicate</span>
                                @endif
                              </div>
                              <div class="col-md-7 text-center mt-5">
                                <?php
                                $LeadsController = new \App\Http\Controllers\LeadController();
                                echo '<span class="cursor-pointer" id="leadupdatestatus_' . $Lead[0]->id . '_1_2" onclick="showLeadUpdateStatus(this.id);">' . $LeadsController->GetLeadStatusColor($Lead[0]->lead_status) .'</span>';
                                ?>
                              </div>
                              <div class="col-md-3 text-center mt-5">
                                <a href="tel:{{$Lead[0]->parentPhone}}">
                                  <button class="btn btn-primary float-right mr-1"
                                          type="button" data-toggle="tooltip" title="Call">
                                      <i class="fa fa-phone"></i>
                                  </button>
                                </a>
                              </div>
                            </div>

                            <!-- PARENTS INFORMATION - START -->
                            <div class="mt-5">
                                <a class="btn btn-primary text-left pt-3 pb-3" style="width:100%;font-size: 14px;"
                                   data-toggle="collapse" href="#parentsInformation" role="button" aria-expanded="false"
                                   aria-controls="collapseExample">
                                    Parents Information
                                    <i class="fa fa-minus float-right" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="collapse" id="parentsInformation">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 mt-2">
                                        <label for="parentFirstName"><strong>First Name</strong></label>
                                        <input type="text" name="parentFirstName" id="parentFirstName"
                                               class="form-control enableEdit"
                                               placeholder="First Name" autocomplete="off"
                                               value="{{$Lead[0]->parentFirstName}}"
                                               disabled="disabled"/>
                                        <!-- <div style="margin-top: 7px;" id="parent_f_name"></div> -->
                                    </div>
                                    <div class="col-md-3 col-sm-3 mt-2">
                                        <label for="parentLastName"><strong>Last Name</strong></label>
                                        <input type="text" name="parentLastName" id="parentLastName"
                                               class="form-control enableEdit"
                                               placeholder="Last Name" autocomplete="off"
                                               value="{{$Lead[0]->parentLastName}}"
                                               disabled="disabled"/>
                                        <!-- <div style="margin-top: 7px;" id="parent_l_name"></div> -->
                                    </div>
                                    <div class="col-md-3 col-sm-3 mt-2">
                                        <label for="parentPhone" class="w-100"><strong>Phone Number 1</strong><i
                                                    class="fa fa-plus-circle float-right" style="cursor: pointer;"
                                                    onclick="ShowParentPhone2Field();"></i></label>
                                        <input type="number" name="parentPhone" id="parentPhone"
                                               class="form-control enableEdit"
                                               placeholder="Enter Your Phone Number" maxlength="20" autocomplete="off"
                                               value="{{$Lead[0]->parentPhone}}" disabled="disabled"/>
                                        <!-- <div style="margin-top: 7px;" id="parent_phone1"></div> -->
                                    </div>
                                    <div class="col-md-3 col-sm-3 mt-2" id="ParentPhoneNumber2"
                                    <?php if ($Lead[0]->parentPhone2 == "") {
                                        echo "style='display: none;'";
                                    } ?>>
                                        <label for="parentPhone2" class="w-100">Phone Number 2<i
                                                    class="fa fa-trash float-right" style="cursor: pointer;"
                                                    onclick="HideParentPhone2Field();"></i></label>
                                        <input type="number" name="parentPhone2" id="parentPhone2"
                                               class="form-control enableEdit" placeholder="Enter Your Phone Number"
                                               maxlength="20" autocomplete="off" value="{{$Lead[0]->parentPhone2}}"
                                               disabled="disabled"/>
                                    </div>
                                    <div class="col-md-3 col-sm-3 mt-2">
                                        <label for="parentEmail"><strong>Email</strong></label>
                                        <input type="email" name="parentEmail" id="parentEmail"
                                               class="form-control"
                                               placeholder="Email" value="{{$Lead[0]->parentEmail}}"
                                               readonly/>
                                    </div>
                                    <div class="col-md-3 col-sm-3 mt-2">
                                        <div class="form-group">
                                            <label class="control-label" for="state">State</label>
                                            <select name="state" id="state" class="form-control select2 enableEdit"
                                                    onchange="LoadStateCountyCity();"
                                                    disabled="disabled">
                                                <option value="">Select State</option>
                                                @foreach($States as $state)
                                                    <option value="{{$state->name}}" <?php if ($state->name == $Lead[0]->state) {
                                                        echo "selected";
                                                    } ?>>{{$state->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 mt-2"
                                         id="citySection" <?php if ($Lead[0]->state == "") {
                                        echo "style='display: none;'";
                                    } ?>>
                                        <div class="form-group">
                                            <label class="control-label" for="city">City</label>
                                            <select name="city" id="city" class="form-control select2 enableEdit"
                                                    disabled="disabled">
                                                <option value="" selected>Select City</option>
                                                @foreach($Cities as $city)
                                                    <option value="{{$city->city}}" <?php if ($Lead[0]->city == $city->city) {
                                                        echo "selected";
                                                    } ?>>{{$city->city}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 mt-2">
                                        <div class="form-group">
                                            <label class="control-label" for="street">Street</label>
                                            <input type="text" class="form-control enableEdit" name="street" id="street"
                                                   placeholder="Street" autocomplete="off" value="{{$Lead[0]->street}}"
                                                   disabled="disabled"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 mt-2">
                                        <div class="form-group">
                                            <label class="control-label" for="zipcode">Zip code</label>
                                            <input type="number" name="zipcode" id="zipcode"
                                                   class="form-control enableEdit"
                                                   data-validate="minlength[5]"
                                                   onkeypress="limitKeypress(event,this.value,5)"
                                                   onblur="limitZipCodeCheck();"
                                                   autocomplete="off"
                                                   placeholder="Zip Code"
                                                   value="{{$Lead[0]->zipcode}}"
                                                   disabled="disabled"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- PARENTS INFORMATION - END -->

                            <!-- PLAYERS INFORMATION - START -->
                            <div class="mt-2">
                                <a class="btn btn-primary text-left pt-3 pb-3" style="width:100%;font-size: 14px;"
                                   data-toggle="collapse" href="#playerInformation" role="button" aria-expanded="false"
                                   aria-controls="collapseExample">
                                    Players Information
                                    <i class="fa fa-minus float-right" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="custom-row">
                                <div class="custom-col-3 mt-2">
                                    <label for="playerFirstName"><strong>First Name</strong></label>
                                    <input type="text" name="playerFirstName" id="playerFirstName"
                                           class="form-control enableEdit"
                                           placeholder="First Name" autocomplete="off"
                                           value="{{$LeadDetails[0]->playerFirstName}}"
                                           disabled="disabled"/>
                                </div>
                                <div class="custom-col-3 mt-2">
                                    <label for="playerLastName"><strong>Last Name</strong></label>
                                    <input type="text" name="playerLastName" id="playerLastName"
                                           class="form-control enableEdit"
                                           placeholder="Last Name" autocomplete="off"
                                           value="{{$LeadDetails[0]->playerLastName}}"
                                           disabled="disabled"/>
                                </div>
                                <div class="custom-col-3 mt-2">
                                    <label for="playerDOB">Date of Birth</label>
                                    <input class="form-control datepicker enableEdit" name="playerDOB"
                                           id="playerDOB"
                                           autocomplete="off"
                                           placeholder="MM/DD/YYYY" autocomplete="off"
                                           value="{{\Carbon\Carbon::parse($LeadDetails[0]->playerDOB)->format('m/d/Y')}}"
                                           disabled="disabled"/>
                                </div>
                                <div class="custom-col-3 mt-2">
                                    <label for="playerEmail">Email</label>
                                    <input type="email" name="playerEmail" id="playerEmail"
                                           class="form-control"
                                           placeholder="Email" autocomplete="off"
                                           value="{{$LeadDetails[0]->playerEmail}}"
                                           readonly/>
                                </div>
                                <div class="custom-col-3 mt-2">
                                    <div class="form-group">
                                        <label class="control-label" for="playerGender">Gender</label>
                                        <div class="mt-2">
                                            <label class="radio-inline">
                                                <input type="radio" name="playerGender" id="male_gender"
                                                       value="Male" <?php if ($LeadDetails[0]->playerGender == "Male") {
                                                    echo "checked";
                                                } ?>>Male
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="playerGender" id="female_gender"
                                                       value="Female" <?php if ($LeadDetails[0]->playerGender == "Female") {
                                                    echo "checked";
                                                } ?>>Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-col-3 mt-2">
                                    <div class="form-group">
                                        <label class="control-label" for="playerRelationship">Relationship</label>
                                        <select class="form-control enableEdit" name="playerRelationship"
                                                id="playerRelationship"
                                                disabled="disabled">
                                            <option value="">Select</option>
                                            <option value="Mother" <?php if ($LeadDetails[0]->playerRelationship == "Mother") {
                                                echo "selected";
                                            } ?>>Mother
                                            </option>
                                            <option value="Father" <?php if ($LeadDetails[0]->playerRelationship == "Father") {
                                                echo "selected";
                                            } ?>>Father
                                            </option>
                                            <option value="Legal Guardian" <?php if ($LeadDetails[0]->playerRelationship == "Legal Guardian") {
                                                echo "selected";
                                            } ?>>Legal Guardian
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="custom-col-3 mt-2">
                                    <div class="form-group">
                                        <label class="control-label" for="location">Locations</label>
                                        <select class="form-control select2 enableEdit" name="location"
                                                id="location" onchange="checkLeadLocation(this.value);"
                                                disabled="disabled">
                                            <option value="">Select</option>
                                            <option value="-1" <?php if ($LeadDetails[0]->location == -1) {
                                                echo "selected";
                                            } ?>>I don't know
                                            </option>
                                            @foreach($Locations as $index => $item)
                                                <option value="{{$item->id}}" <?php if ($LeadDetails[0]->location == $item->id) {
                                                    echo "selected";
                                                } ?>>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="custom-col-3 mt-2"
                                     id="LocationZipCodeBlock" <?php if ($LeadDetails[0]->location != -1) {
                                    echo "style='display:none;'";
                                } ?>>
                                    <div class="form-group">
                                        <label class="control-label" for="locationZipcode">Zip code</label>
                                        <input type="number" name="locationZipcode" id="locationZipcode"
                                               class="form-control enableEdit"
                                               data-validate="minlength[5]"
                                               onkeypress="limitKeypress(event,this.value,5)"
                                               onblur="limitZipCodeCheck();"
                                               placeholder="Zip Code"
                                               value="{{$LeadDetails[0]->locationZipcode}}"
                                               disabled="disabled"/>
                                    </div>
                                </div>
                                <div class="custom-col-12">
                                    <div class="form-group">
                                        <label class="control-label" for="message">Message</label>
                                        <textarea name="message" id="message"
                                                  class="form-control enableEdit" rows="5"
                                                  cols="80"
                                                  disabled="disabled">{{$LeadDetails[0]->message}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- PLAYERS INFORMATION - END -->

                            <!-- SCHEDULE FREE CLASS - START -->
                            <div class="mt-2">
                                <a class="btn btn-primary text-left pt-3 pb-3" style="width:100%;font-size: 14px;"
                                   data-toggle="collapse" href="#scheduleFreeClass" role="button" aria-expanded="false"
                                   aria-controls="collapseExample">
                                    Schedule Free Class
                                    <i class="fa fa-minus float-right" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="collapse" id="scheduleFreeClass">
                                <div class="custom-row">
                                    <div class="custom-col-12 mt-2">
                                        <div class="form-group">
                                            <label class="control-label" for="playerGender">Are you register now or
                                                schedule a free class?</label>
                                            <div class="mt-2">
                                                <label class="radio-inline">
                                                    <input type="radio" name="getregister_or_schedulefreeclass"
                                                           id="get_register" value="1"
                                                    <?php if ($Lead[0]->getregister_or_schedulefreeclass == 1) {
                                                        echo "checked";
                                                    } ?>>Get Register
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="getregister_or_schedulefreeclass"
                                                           id="schedule_free_class" value="2"
                                                    <?php if ($Lead[0]->getregister_or_schedulefreeclass == 2) {
                                                        echo "checked";
                                                    } ?>>Schedule Free Class
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="custom-col-3 mt-2 freeClassField" <?php if ($Lead[0]->getregister_or_schedulefreeclass != 2) {
                                        echo "style='display:none;'";
                                    } ?>>
                                        <div class="form-group">
                                            <label class="control-label" for="free_class">Free Class</label>
                                            <select class="form-control select2 enableEdit" name="free_class"
                                                    id="free_class" onchange="getFreeClassDays(this.value);"
                                                    disabled="disabled">
                                                <option value="">Select</option>
                                                @foreach($FreeClasses as $index => $class)
                                                    <option value="{{$class->id}}" <?php if ($LeadDetails[0]->free_class == $class->id) {
                                                        echo "selected";
                                                    } ?>>{{$class->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="custom-col-3 mt-2 freeClassField" <?php if ($Lead[0]->getregister_or_schedulefreeclass != 2) {
                                        echo "style='display:none;'";
                                    } ?>>
                                        <div class="form-group">
                                            <label class="control-label" for="free_class_date">Free Class Date</label>
                                            <input class="form-control free_class_date enableEdit"
                                                   name="free_class_date"
                                                   id="free_class_date" autocomplete="off"
                                                   value="{{\Carbon\Carbon::parse($LeadDetails[0]->free_class_date)->format('m/d/Y')}}"
                                                   placeholder="MM/DD/YYYY" disabled="disabled"/>
                                        </div>
                                    </div>

                                    <div class="custom-col-3 mt-2 freeClassField" <?php if ($Lead[0]->getregister_or_schedulefreeclass != 2) {
                                        echo "style='display:none;'";
                                    } ?>>
                                        <div class="form-group">
                                            <label class="control-label" for="free_class_time">Free Class Time</label>
                                            <select class="form-control select2 enableEdit" name="free_class_time"
                                                    id="free_class_time" disabled="disabled">
                                                <option value="">Select</option>
                                                @foreach($FreeClassTimings as $index => $timing)
                                                    <?php
                                                    $Time = \Carbon\Carbon::parse($timing->time)->format('h:i A');
                                                    ?>
                                                    <option value="{{$timing->time}}" <?php if ($timing->time == $LeadDetails[0]->free_class_time) {
                                                        echo "selected";
                                                    } ?>>{{$Time}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- SCHEDULE FREE CLASS - END -->

                            {{-- LEAD UPDATE BUTTON - START --}}
                            <div class="custom-row">
                                <div class="custom-col-12 mt-5 mb-5 text-center">
                                    <button type="button" name="editLeadBtn" id="editLeadBtn" class="btn btn-primary"
                                            onclick="checkConfirmation();">Edit
                                    </button>
                                    <input type="submit" class="btn btn-primary" name="submitLeadFormBtn"
                                           id="submitLeadFormBtn" style="display:none;" value="Save Changes"/>
                                </div>
                            </div>
                            {{-- LEAD UPDATE BUTTON - END --}}
                        </div>
                    </div>
                </div>
                <!-- Lead Comments -->
                <div class="col-md-4">
                    <!-- Add Lead Comment - Start -->
                    <div class="panel panel-default" style="padding: 0px 30px 10px 30px;">
                        <div class="panel-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-12 mb-3 mt-3">
                                        <label class="w-100" for="note">Comments</label>
                                        <textarea class="form-control" id="lead_comment" name="lead_comment"
                                                  rows="4"></textarea>
                                        <div class="error pt-2" id="lead_comment_error" style="display:none;">Comment is
                                            missing!
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-right mt-3">
                                        <button type="button" class="btn btn-primary"
                                                onclick="SaveEditLeadComment();">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Add Lead Comment - End -->

                    <!-- Display Lead Comments - Start -->
                    <div class="panel panel-default" style="padding: 0px 30px 10px 30px;">
                        <div class="panel-body">
                            <h6>
                                Lead Comments
                            </h6>
                            <div class="table-responsive">
                                <table id="editlead_comments_table" class="table w-100">
                                    <thead>
                                    <tr>
                                        <th>Created At</th>
                                        <th style="width: 5%;">#</th>
                                        <th>User</th>
                                        <th>Comment</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Display Lead Comments - End -->
                </div>
            </div>
            {{-- Edit Lead --}}
        </div>
    </form>

    @include('dashboard.leads.editConfirmationModal')
    @include('dashboard.leads.scripts')
    <script>
        let DaysExcluded = JSON.parse('<?= $DaysExcluded ?>');
    </script>
@endsection
