@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        @media (min-width: 992px){
            .responsive {

            }
        }


        @media (max-width: 767px) {
            .responsive {
                overflow-x:auto;
            }
            .padd{
                padding: 4px 9px;
            }
        }


    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1" id="beforeTablePage"></div>
            <div class="col-md-10" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Classes</h3>
                        @if($Role == 1 || $Role == 2 || $Role == 3)
                        <button type="button" class="btn btn-primary mb-0 mr-2 padd" style="float: right;"
                                data-toggle="tooltip" title="Create New Class"
                                onclick="window.location.href='{{route('classes.add')}}';"><i
                                    class="fas fa-plus-square"></i></button>
                        @elseif($Role == 4)
                        <button type="button" class="btn btn-primary mr-3 padd"
                                onclick="UserFilterBackButton();"
                                data-toggle="tooltip" title="Filter"
                                style="float: right;">
                                <i class="fas fa-filter"></i></button>
                        <button type="button" class="btn btn-primary mb-0 mr-2 padd"
                                data-toggle="tooltip" title="Announcement"
                                style="float: right;"
                                onclick="openPlayerAnnouncementModal();">
                                <i class="fa fa-microphone mr-1"></i>
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
                        <div class="responsive" >
                            <table class="table w-100 tbl-responsive" id="classesTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th>Created At</th>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Package</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter - START -->
            <div class="col-md-2" id="filterPage" style="display:none;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title w-100">
                            Filter
                            <i class="fas fa-times" style="font-size: 16px; cursor: pointer; float: right;"
                               onclick="UserFilterCloseButton();"></i>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="start_date">Location</label>
                                <select class="form-control select2" name="class_location[]" id="class_location" multiple>
                                  <option value="" disabled>Select</option>
                                  @foreach($locations as $location)
                                  <option value="{{$location->id}}">{{$location->name}}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <button type="button" name="button" class="btn btn-primary"
                                        onclick="DestroyDataTable(); MakeClassesTable();">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter - END -->
        </div>
    </div>

    @include('dashboard.classes.delete')
    @include('dashboard.classes.statusConfirmationModal')
    @include('dashboard.classes.assignPlayerModal')
    @include('dashboard.classes.playerAnnouncementModal')
    @include('dashboard.classes.scripts')
@endsection
