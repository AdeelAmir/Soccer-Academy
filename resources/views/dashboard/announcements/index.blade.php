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
        }


    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Announcements</h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2" style="float: right;"
                                onclick="window.location.href='{{route('announcements.add')}}';"><i
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
                        <div class="responsive" >
                            <table class="table w-100 tbl-responsive" id="announcementsTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 60%;">Announcement</th>
                                    <th style="width: 15%;">Expiration</th>
                                    <th style="width: 10%;">Status</th>
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
    @include('dashboard.announcements.scripts')
    @include('dashboard.announcements.delete')
@endsection
