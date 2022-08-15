@extends('dashboard.layouts.app')
@section('content')
    <style>
        .cntr {
            display: table;
            width: 100%;
            height: 100%;
        }
        .cntr .cntr-innr {
            display: table-cell;
            text-align: right;
            vertical-align: middle;
        }
        /*** STYLES ***/
        .search {
            display: inline-block;
            position: relative;
            height: 35px;
            width: 35px;
            box-sizing: border-box;
            padding: 3px 9px 0 9px;
            border: 3px solid #023A51;
            border-radius: 25px;
            transition: all 200ms ease;
            cursor: text;
        }
        .search:after {
            content: "";
            position: absolute;
            width: 3px;
            height: 20px;
            right: -5px;
            top: 21px;
            background: #023A51;
            border-radius: 3px;
            transform: rotate(-45deg);
            transition: all 200ms ease;
        }
        .search.active,
        .search:hover {
            width: 100%;
            margin-right: 0;
        }
        .search.active:after,
        .search:hover:after {
            height: 0;
        }
        .search input {
            width: 100%;
            border: none;
            box-sizing: border-box;
            font-family: Helvetica;
            font-size: 15px;
            color: inherit;
            background: transparent;
            outline-width: 0;
        }

        #searchFaq{
            width: 100%;
            border-radius: 50px;
            margin: 0 auto;
            /* padding-left: 30px; */
            padding-left: 15px;
            padding-top: 6px;
        }

        .searchIcon1 {
            position: absolute;
            right: 27px;
            top: 8px;
        }

        .searchIcon2 {
            position: absolute;
            left: 25px;
            top: 11px;
        }

        #searchFaq.active,
        #searchFaq:hover {
            width: 100%;
        }

        /* Folders CSS */
        .progress {
            display: flex;
            height: 1.5rem;
            overflow: hidden;
            line-height: 0;
            font-size: 0.55rem;
            background-color: #e9ecef;
            border-radius: 0.25rem;
            margin-top: -20px;
        }

        .progress-bar {
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            background-color: #4fd36d;
            transition: width 0.6s ease;
            padding-top: 1px;
            padding-top: 2px;
        }

        .cardBackgroundColor{
            background-color: #f8f8f8 !important;
        }

        .courseTitleSetting{
            margin-top: -25px;
        }

        .courseOpenLinkSetting{
            font-size: 10px;
        }

        .badge{
            font-size: 8px !important;
        }
        /* Folders CSS */
        @media (min-width: 992px){
            .responsive {

            }
        }


        @media (max-width: 767px) {
            .responsive {
                overflow-x:auto;
            }
        }
    </style>
    <!-- Get User Training Room Courses - Start -->
    <?php
    $AllTrainingAssignmentFolders = \Illuminate\Support\Facades\DB::table('training_assignment_folders')
        ->join('folders', 'training_assignment_folders.folder_id', '=', 'folders.id')
        ->where('training_assignment_folders.user_id', '=', \Illuminate\Support\Facades\Auth::id())
        ->select('training_assignment_folders.*', 'folders.name AS FolderName', 'folders.picture', 'folders.required')
        ->get();
    ?>
    <!-- Get User Training Room Courses - End -->
    <div class="page-content">
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

                    <div class="col-md-1"></div>
                    <div class="col-md-10 grid-margin stretch-card mt-5">
                        @if($Role == 4)
                        <button type="button" class="btn btn-primary mr-2"
                                data-toggle="tooltip" title="Create Training"
                              onclick="window.location.href='{{route('trainingRoom')}}';" style="float:right;"><i
                              class="fas fa-plus-square"></i></button>
                        @endif
                        <div class="panel panel-default" <?php if($Role == 4){echo 'style="margin-top: 50px;"';} ?>>
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="panel-title pt-3">
                                            Training Room > <span class="text-primary">My Courses</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div>
                                            <i class="fa fa-search searchIcon searchIcon1"></i>
                                            <input type="text" class="form-control" name="searchFaq" id="searchFaq" placeholder="Search" onkeyup="SearchFolder(this);" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                {{--Assigned Course Folder--}}
                                <div class="row" id="TrainingRoomFolders">
                                    @if(count($AllTrainingAssignmentFolders) > 0)
                                        @foreach($AllTrainingAssignmentFolders as $folder)
                                            <?php
                                            $Url = url('training/course/' . $folder->id);
                                            ?>
                                            <div class="col-md-4">
                                                <a href="{{$Url}}">
                                                    <div class="panel panel-default" style="box-shadow: 2px 2px #f7f7f7;">
                                                        <div class="panel-heading">
                                                            <img src="{{ asset('public/storage/folders/' . $folder->picture)}}" alt="logo-small" class="img-fluid" style="width: 100%; height: 200px;">
                                                        </div>
                                                        <div class="panel-body">
                                                            <p class="text-left courseTitleSetting pt-4">{{$folder->FolderName}}</p>
                                                            <div class="mt-1">
                                                                <a href="{{$Url}}" class="mt-2 courseOpenLinkSetting">
                                                                    @if($folder->completion_rate > 0 && $folder->completion_rate < 100)
                                                                        Resume Course
                                                                    @elseif($folder->completion_rate == 100)
                                                                        Review Course
                                                                    @else
                                                                        Start Course
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            @if($folder->required == 1)
                                                                <div class="mt-1">
                                                                    <span class="badge badge-danger">Required</span>
                                                                </div>
                                                            @endif
                                                            <div class="progress mt-3">
                                                                <div class="progress-bar" role="progressbar" style="width: {{round($folder->completion_rate) . '%'}}" aria-valuenow="{{$folder->completion_rate}}" aria-valuemin="0" aria-valuemax="100">{{round($folder->completion_rate)}}%</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6 mt-4 mb-5">
                                            <h4 class="text-center" style="font-size: 16px;color: #b0b6b0;">Training Room is empty!</h4>
                                        </div>
                                    @endif
                                </div>
                                {{--Assigned Course Folder--}}

                                {{--Course Search Result--}}
                                <div class="row mt-5" id="searchResultsCourseDiv" style="display: none;"></div>
                                {{--Course Search Result--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </section>
    </div>
    @include('dashboard.training-room.scripts')
@endsection
