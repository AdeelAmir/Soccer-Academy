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
    <div class="page-content" id="addTrainingRoomFolderPage">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        </div>
        @if(Session::get('user_role') == 1 || 4)
            <form action="{{route('folder.store')}}" method="post" id="addFolderForm" enctype="multipart/form-data">
        @endif
                @csrf
                <input type="hidden" name="training_room_role_id" id="training_room_role_id"
                       value="{{$TrainingRoomRoleId}}"/>
                <section class="contact-area pb-5">
                    <div class="container">
                        <div class="row">
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

                            <div class="col-md-3"></div>
                            <div class="col-md-6 grid-margin stretch-card mt-5">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title pt-3 head">
                                            Training Room > <span class="text-primary">Add New Folder</span>
                                        </div>
                                        <div class="panel-title pt-3 head_mob">
                                           <span class="text-primary">Add New Folder</span>
                                        </div>
                                        <button type="button" class="mt-2 btn btn-primary float-right padd"
                                                onclick="window.location.href='{{url('training-room/folders/' . $TrainingRoomRoleId)}}';">
                                            <i class="fas fa-arrow-left mr-1"></i>
                                        </button>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3 mt-1">
                                                <label class="control-label" for="title">Name</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                       placeholder="Enter Folder Name" required/>
                                            </div>
                                            <div class="col-md-12 mb-3 mt-1">
                                                <label class="control-label" for="">Picture</label>
                                                <input type="file" name="picture" id="picture" class="form-control"
                                                       accept="image/png, image/gif, image/jpeg" required/>
                                            </div>
                                            <div class="col-md-12 mb-3 mt-1">
                                                <label class="control-label" for="">Required</label>
                                                <select class="form-control" name="required_status" id="required_status"
                                                        required>
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-center mt-3">
                                                <button type="submit" class="btn btn-primary">
                                                    Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                </section>
            </form>
    </div>
@endsection
