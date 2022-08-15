@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        @media only screen and (min-width: 768px) {
            div.dataTables_wrapper div.dataTables_filter {
                text-align: right;
                /*margin-right: 170px;*/
                color: black;
            }
            .table-responsive {
                display: block;
                width: 100%;
                overflow: hidden;
                -webkit-overflow-scrolling: touch;
            }

            p {
                color:black;
            }
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
            .padd{
                padding: 4px 9px;
            }
        }
    </style>
    <div class="page-content">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 mt-5 ">
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
                <div class="panel panel-defaultl">
                    <div class="panel-heading">
                        <div class="panel-title pt-3 head">
                            <span class=""> Knowledge Zone > <span class="text-primary">Questions</span></span>
                        </div>
                        <div class="panel-title pt-2 head_mob">
                            <span class="head_mob" style="font-size:14px;"> Knowledge Zone > <span class="text-primary">Questions</span></span>
                        </div>
                        <div class="panel-options">
                            <!-- Delete -->
                            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 padd" style="display:none;"
                                    data-toggle="tooltip" title="Delete Selected Question"
                                    onclick="DeleteMultipleFaq();" id="deleteAllFaqBtn">
                                <i class="fas fa-trash mr-1"></i>
                            </button>
                            <!-- Delete -->
                            <button type="button" class="btn btn-primary btn-icon-text float-right mb-2 mb-md-0 padd"
                                    data-toggle="tooltip" title="Add New Question" onclick="OpenAddFaqModal();">
                                <i class="fas fa-plus-square mr-1"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 mr-1 padd"
                                    data-toggle="tooltip" title="Action" onclick="HandleFaqAction();">
                                <i class="fa fa-tasks mr-1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{url('')}}" method="post" enctype="multipart/form-data" id="faqForm">
                            @csrf
                            @include('dashboard.includes.deleteFaqModal')
                            <div class="responsive" >
                                <table id="admin_training_room_faqs" class="table w-100 tbl-responsive">
                                    <thead>
                                    <tr>
                                        <th class="allFaqActionCheckBoxColumn">
                                            <input type="checkbox" name="checkAllBox" class="allFaqCheckBox" id="checkAllBox"
                                                   onchange="CheckAllFaqRecords(this);"/>
                                        </th>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 35%;">Question</th>
                                        <th style="width: 50%;">Answer</th>
                                        <th style="width: 10%;">Action</th>
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
        </div>
    </div>
    @include('dashboard.training-room.scripts')
    @include('dashboard.includes.addFaqModal')
    @include('dashboard.includes.editFaqModal')
@endsection
