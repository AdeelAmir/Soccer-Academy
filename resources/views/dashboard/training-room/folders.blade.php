@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        @media (min-width: 992px){
            .responsive {

            }
            .head{

            }
            .head_mob{
                display: none;
            }
        }


        @media (max-width: 767px) {
            .responsive {
                overflow-x:auto;
            }
            .head{
                display: none;
            }
            .head_mob{

            }
            .padd {
                padding: 4px 9px;
            }
        }
    </style>
    <div class="page-content" id="TrainingRoomDetailsPage">
        <div class="row">
            <!-- Role -->
            <input type="hidden" name="training_room_role_id" id="training_room_role_id" value="{{$RoleId}}">

            <!-- Training Room - Start -->
            <div class="col-md-1"></div>
            <div class="col-md-10 mt-4">
                <div class="col-12 mb-3" id="message-alert">
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @elseif(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title pt-3 head">
                            Training Room > <span class="text-primary">{{$TrainingRoomRole}}</span>
                        </div>
                        <div class="panel-title pt-2 head_mob">
                            <span class="text-primary">{{$TrainingRoomRole}}</span>
                        </div>
                        <div class="panel-options">
                            <button type="button" class="btn btn-primary btn-icon-text head"
                                    onclick="window.location.href='{{url('training-room/folder/add/' . $RoleId)}}';">
                                <i class="fas fa-plus-square mr-1"></i>
                                Add New Folder
                            </button>
                            <button type="button" class="btn btn-primary btn-icon-text head_mob padd"
                                    onclick="window.location.href='{{url('training-room/folder/add/' . $RoleId)}}';">
                                <i class="fas fa-plus-square mr-1"></i>
                            </button>
                            <button style="margin-left: 5px" type="button" class="btn btn-primary padd" onclick="window.location.href='{{route('trainingRoom')}}';">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="responsive" >
                            <table id="admin_training_room_folders" class="table w-100 tbl-responsive">
                                <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 25%;">Folder Name</th>
                                    <th style="width: 15%;">Picture</th>
                                    <th style="width: 15%;">Required</th>
                                    <th style="width: 35%;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1"></div>
            <!-- Training Room - End -->
        </div>
    </div>
    @include('dashboard.training-room.scripts')
    @include('dashboard.includes.deleteTrainingRoomFolderModal')
    @include('dashboard.includes.copyTrainingRoomFolderModal')
@endsection
