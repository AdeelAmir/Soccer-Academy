@extends('dashboard.layouts.app')
@section('content')
    <style>
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
            .padd{
                padding: 4px 9px;
            }
        }


    </style>
    <div class="page-content" id="addTrainingRoomVideoPage">
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

        @if(Session::get('user_role') == 1 || 4)
            <form action="{{route('trainingRoomVideo.store')}}" method="post" id="addVideoForm" enctype="multipart/form-data">
                @endif
                @csrf
                <input type="hidden" name="training_room_role_id" id="training_room_role_id" value="{{$TrainingRoomRoleId}}" />
                <input type="hidden" name="training_room_folder_id" id="training_room_folder_id" value="{{$FolderId}}" />
                <section class="contact-area pb-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6 grid-margin stretch-card">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="head">Training Room > <span class="text-primary">Add New Video</span></span>
                                        <span class="head_mob"><span class="text-primary">Add New Video</span></span>
                                        @if($Role == 1)
                                            <button type="button" class="btn btn-primary float-right padd"
                                                    onclick="window.location.href='{{url('training-room/folder/details/' . $FolderId . '/' . $TrainingRoomRoleId)}}';">
                                                <i class="fas fa-arrow-left mr-1"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3 mt-1">
                                                <label class="control-label" for="title">Title</label>
                                                <input type="text" name="title" id="title" class="form-control"
                                                       placeholder="Enter Video Title" required/>
                                            </div>
                                            <div class="col-md-12 mb-3 mt-1">
                                                <label class="control-label" for="link">Link</label>
                                                <textarea name="link" rows="5" class="form-control" required></textarea>
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
