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
            <div class="col-md-3"></div>
            <div class="col-md-6" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Locations</h3>
                        @if($Role == 1 || $Role == 2)
                        <button type="button" class="btn btn-primary mb-0 mr-2 padd" style="float: right;"
                                data-toggle="tooltip" title="Create New Location"
                                onclick="window.location.href='{{route('locations.add')}}';"><i
                                    class="fas fa-plus-square"></i></button>
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
                            <table class="table w-100 tbl-responsive" id="locationsTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th>Created At</th>
                                    <th>#</th>
                                    <th>Location</th>
                                    <th>Player</th>
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
        </div>
    </div>

    @include('dashboard.locations.delete')
    @include('dashboard.locations.statusConfirmationModal')
    @include('dashboard.locations.scripts')
@endsection
