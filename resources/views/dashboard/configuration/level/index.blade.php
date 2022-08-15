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
                padding:4px 9px;
            }
        }


    </style>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pt-2">Configuration > <span class="text-primary">Levels</span></h3>
                    <div class="panel-options">
                        <button type="button" class="btn btn-primary padd" onclick="AddLevel();"><i class="fas fa-plus-square"></i></button>
                        <button type="button" class="btn btn-primary padd" onclick="window.location.href='{{route('configuration')}}';">
                          <i class="fas fa-arrow-left"></i>
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if(\Illuminate\Support\Facades\Session::has('success'))
                                <div class="alert alert-success" id="message-alert">
                                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span> <span
                                                class="sr-only">Close</span></button>
                                    {{\Illuminate\Support\Facades\Session::get('success')}}
                                </div>
                            @elseif(\Illuminate\Support\Facades\Session::has('error'))
                                <div class="alert alert-danger" id="message-alert">
                                    {{\Illuminate\Support\Facades\Session::get('error')}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="responsive">
                        <table class="table tbl-responsive" id="levelsTable">
                            <thead>
                            <tr class="replace-inputs">
                                <th>#</th>
                                <th>Title</th>
                                <th>Symbol</th>
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
    </div>

    @include('dashboard.configuration.level.add')
    @include('dashboard.configuration.level.delete')
    @include('dashboard.configuration.level.edit')
    @include('dashboard.configuration.level.editConfirmationModal')
    @include('dashboard.configuration.level.scripts')
@endsection
