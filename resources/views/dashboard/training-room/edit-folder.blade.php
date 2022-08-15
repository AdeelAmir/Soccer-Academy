@extends('dashboard.layouts.app')
@section('content')
<style>
    @media (min-width: 992px){
        .head {

        }
        .head_mob{
            display:none;
        }

    }

    @media (max-width: 767px) {
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
    <div class="page-content" id="editTrainingRoomFolderPage">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        @if(Session::get('user_role') == 1 || 4)
            <form action="{{route('trainingRoomFolder.update')}}" method="post" id="editFolderForm" enctype="multipart/form-data">
                @endif
                @csrf
                <input type="hidden" name="id" id="training_room_folder_id" value="{{$folder['id']}}" />
                <input type="hidden" name="training_room_role_id" id="training_room_role_id" value="{{$RoleId}}" />
                <input type="hidden" name="oldFolderPicture" id="oldFolderPicture" value="{{$folder->picture}}"/>
                <section class="contact-area pb-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6 grid-margin stretch-card">
                                <div class="col-6 mb-3" id="message-alert">
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
                                        <span class="head">Training Room > <span class="text-primary">Edit Folder</span></span>
                                        <span class="head_mob"><span class="text-primary">Edit Folder</span></span>
                                        @if($Role == 1)
                                            <button type="button" class="btn btn-primary float-right padd"
                                                    onclick="window.location.href='{{url('training-room/folders/' . $RoleId)}}';">
                                                <i class="fas fa-arrow-left mr-1"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3 mt-3">
                                                <label class="control-label" for="title">Name</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                       placeholder="Enter Folder Name" value="{{$folder['name']}}" required/>
                                            </div>
                                            <div class="col-md-12 mb-3 mt-1">
                                                <label class="control-label" for="">Picture</label>
                                                <input type="file" name="picture" id="picture" class="form-control"
                                                       accept="image/png, image/gif, image/jpeg" />
                                            </div>
                                            <div class="col-md-12 mb-3 mt-1">
                                                <label class="control-label" for="">Required</label>
                                                <select class="form-control" name="required_status" id="required_status" required>
                                                    <option value="0" <?php if($folder['required'] == "0"){echo "selected";} ?> >No</option>
                                                    <option value="1" <?php if($folder['required'] == "1"){echo "selected";} ?> >Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-center mt-3">
                                                <button type="submit" class="btn btn-primary">
                                                    Update
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
