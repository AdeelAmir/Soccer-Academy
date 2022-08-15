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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3 head">Classes > {{$ClassDetails[0]->title}} > <span class="text-primary">Evaluation</span></h3>
                        <h3 class="panel-title pt-2 head_mob" style="font-size: 15px">{{$ClassDetails[0]->title}} > <span class="text-primary">Evaluation</span></h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2 padd" style="float: right;"
                                onclick="window.location.href='{{route('classes')}}';"><i
                                    class="fas fa-arrow-left"></i></button>
                        <button type="button" class="btn btn-primary mb-0 mr-2 padd" style="float: right;"
                                data-toggle="tooltip" title="Create New Evaluation"
                                onclick="openAddEvaluationModal();"><i
                                    class="fas fa-plus-square"></i></button>
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
                        <input type="hidden" name="class_id" id="evaluation_class_id" value="{{$ClassId}}" />
                        <div class="responsive" >
                            <table class="table w-100 tbl-responsive" id="classPlayersTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">Report No</th>
                                    <th style="width: 20%;">Player</th>
                                    <th style="width: 20%;">Evaluation Date</th>
                                    <th style="width: 10%;">Grade</th>
                                    <th style="width: 15%;">Report PDF</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.classes.evaluation.addEvaluationModal')
    @include('dashboard.classes.evaluation.delete')
    @include('dashboard.classes.scripts')
@endsection
