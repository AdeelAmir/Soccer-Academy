@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        .d-none {
            display: none;
        }

        .w-0 {
            width: 0;
        }

        .error {
            font-size: 12px;
            color: red;
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

        }


    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-2" id="beforeTablePage"></div>
            <div class="col-12 col-md-8" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3 head">
                            @if($UserRole == 5)
                                Family
                            @else
                                Users
                            @endif
                        </h3>
                        @if($UserRole == 1)
                            <button type="button" class="btn btn-primary btn-icon-text mb-0 mr-2" style="float: right;"
                                    data-toggle="tooltip" data-title="Filter" onclick="UserFilterBackButton();">
                                <i class="fa fa-filter"></i>
                            </button>
                        @endif
                        <button type="button" class="btn btn-primary mb-0 mr-2" style="float: right;"
                                data-toggle="tooltip" title="Create New User"
                                onclick="openUserRoleTypeModal();"><i
                                    class="fas fa-plus-square"></i></button>
                        @if($UserRole == 1 || $UserRole == 2 || $UserRole == 3)
                            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 mr-2"
                                    data-toggle="tooltip" title="Announcement"
                                    style="float: right;"
                                    onclick="window.location.href='{{route('announcements')}}';">
                                <i class="fa fa-microphone mr-1"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0"
                                    style="display:none;float: right;"
                                    data-toggle="tooltip" title="Ban Selected Users" onclick="BanMultipleUsers();"
                                    id="banAllUsersBtn">
                                <i class="fas fa-ban mr-1"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 float-right"
                                    style="display:none;float: right;"
                                    data-toggle="tooltip" title="Active Selected Users" onclick="ActiveMultipleUsers();"
                                    id="activeAllUsersBtn">
                                <i class="fas fa-check mr-1"></i>
                            </button>

                            @if($UserRole == 1)
                                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0"
                                        style="display:none;float: right;"
                                        data-toggle="tooltip" title="Delete Selected Users"
                                        onclick="DeleteMultipleUsers();"
                                        id="deleteAllUsersBtn">
                                    <i class="fas fa-trash mr-1"></i>
                                </button>
                            @endif

                            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0"
                                    style="display:none;float: right;"
                                    data-toggle="tooltip" title="Broadcast Selected Users"
                                    onclick="BroadcastMultipleUsers();" id="broadcastAllUsersBtn">
                                <i class="fa fa-broadcast-tower mr-1"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0"
                                    style="float: right;"
                                    data-toggle="tooltip" title="Select" onclick="HandleUserAction();">
                                <i class="fa fa-tasks mr-1"></i>
                            </button>
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
                        <form action="{{url('')}}" method="post" enctype="multipart/form-data" id="usersForm">
                            @csrf
                            @include('dashboard.users.delete')
                            @include('dashboard.users.broadcast')
                            @include('dashboard.users.active')
                            @include('dashboard.users.ban')
                            <div class="responsive">
                                <table class="table w-100 tbl-responsive" id="usersTable">
                                    <thead>
                                    <tr class="replace-inputs">
                                        <th>Created At</th>
                                        <th>
                                            <input type="checkbox" name="checkAllBox" class="allUsersCheckBox"
                                                   id="checkAllBox"
                                                   onchange="CheckAllUserRecords(this);"/>
                                        </th>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3" id="filterPage" style="display:none;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title w-100 pt-3">
                            Filter
                            <i class="fa fa-window-close float-right" style="font-size: 16px;
      cursor: pointer;" aria-hidden="true" onclick="UserFilterCloseButton();"></i>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="fullNameFilter">Full Name</label>
                                <input type="text" name="fullNameFilter" id="fullNameFilter"
                                       class="form-control"/>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="phoneFilter">Phone Number</label>
                                <input type="text" name="phoneFilter" id="phoneFilter" class="form-control"/>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="stateFilter">State</label>
                                <select name="stateFilter" id="stateFilter" class="form-control select2"
                                        onchange="CheckLeadFilterState(this); LoadFilterStateCountyCity();">
                                    <option value="0">All States</option>
                                    @if(isset($States))
                                        @foreach($States as $state)
                                            <option value="{{$state->name}}">{{$state->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-12 mb-3" id="_leadFilterCityBlock" style="display:none;">
                                <label for="cityFilter">City</label>
                                <select name="cityFilter" id="cityFilter" class="form-control">
                                    <option value="0" selected>Select City</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="zipCodeFilter">Zip Code</label>
                                <input type="text" name="zipCodeFilter" id="zipCodeFilter"
                                       class="form-control"/>
                            </div>

                            <div class="col-md-12 mt-2 mb-3">
                                <label for="user_role">User Type</label>
                                <select class="form-control" name="user_role" id="user_role">
                                    <option value="">Select</option>
                                    @foreach($Roles as $role)
                                        <option value="{{$role->id}}">{{$role->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mt-2 mb-3">
                                <label for="user_status">Status</label>
                                <select class="form-control" name="user_status" id="user_status">
                                    <option value="">Select</option>
                                    <option value="1">Active</option>
                                    <option value="0">Ban</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2 mb-3">
                                <label for="location">Location</label>
                                <select class="form-control select2" name="location" id="location" required>
                                    <option value="">Select</option>
                                    @foreach($Locations as $index => $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary" onclick="MakeUsersTable();">Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.users.scripts')
    @include('dashboard.users.power-type')
    @include('dashboard.users.userActivityModal')
    @include('dashboard.users.changePasswordModal')
    @include('dashboard.users.role-type')
    @include('dashboard.users.ban-active')
@endsection
