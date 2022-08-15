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
            <input type="hidden" name="training_room_folder_id" id="training_room_folder_id" value="{{$FolderId}}">
            <input type="hidden" name="training_room_role_id" id="training_room_role_id" value="{{$RoleId}}">

            <!-- Training Room - Start -->
            <div class="col-md-2 "></div>
            <div class="col-md-8 grid-margin mt-5">
                <div class="col-10 mb-3" id="message-alert">
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
                        <div class="panel-title pt-3 head_mob">
                           <span class="text-primary">{{$TrainingRoomRole}}</span>
                        </div>
                        <div class="panel-options">
                            <button type="button" class="btn btn-primary btn-icon-text head"
                                    onclick="openTrainingRoomTypeModal();">
                                <i class="fas fa-plus-square mr-1"></i>
                                Add New
                            </button>
                            <button type="button" class="btn btn-primary btn-icon-text head_mob padd"
                                    onclick="openTrainingRoomTypeModal();">
                                <i class="fas fa-plus-square mr-1"></i>
                            </button>
                            <button type="button" class="btn btn-primary ml-2 padd"
                                    onclick="window.location.href='{{url('training-room/folders/' . $RoleId)}}';">
                                <i class="fas fa-arrow-left mr-1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="responsive" >
                            <table id="admin_training_room" class="table w-100 tbl-responsive">
                                <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 10%;">Type</th>
                                    <th style="width: 45%;">Title</th>
                                    <th style="width: 40%;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
            <!-- Training Room - End -->
        </div>
    </div>
    @include('dashboard.training-room.scripts')
    @include('dashboard.includes.trainingRoomTypeModal')
    @include('dashboard.includes.copyTrainingRoomItemModal')
    @include('dashboard.includes.deleteTrainingRoomModal')
@endsection
