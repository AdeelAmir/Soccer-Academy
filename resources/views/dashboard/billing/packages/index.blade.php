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
            <div class="col-md-2"></div>
            <div class="col-md-8" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-2">Billing > <span class="text-primary">Packages</span></h3>
                        <div class="panel-options">
                          @if($Role == 1 || $Role == 2)
                          <button type="button" class="btn btn-primary padd"
                                  data-toggle="tooltip" title="Create New Package"
                                  onclick="window.location.href='{{route('packages.add')}}';"><i
                                      class="fas fa-plus-square"></i></button>
                          @endif
                          <button type="button" class="btn btn-primary padd" onclick="window.location.href='{{route('billing')}}';">
                            <i class="fas fa-arrow-left"></i>
                          </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(\Illuminate\Support\Facades\Session::has('success'))
                            <div class="alert alert-success" id="message-alert">
                                <button type="button" class="close" data-dismiss="alert"><span
                                            aria-hidden="true">×</span> <span
                                            class="sr-only">Close</span></button>
                                <strong>Message:</strong> {{\Illuminate\Support\Facades\Session::get('success')}}
                            </div>
                        @elseif(\Illuminate\Support\Facades\Session::has('error'))
                            <div class="alert alert-danger" id="message-alert">
                                <strong>Message:</strong> {{\Illuminate\Support\Facades\Session::get('error')}}
                            </div>
                        @endif
                        <div class="responsive" >
                            <table class="table w-100 tbl-responsive" id="packagesTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th>Created At</th>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Limit</th>
                                    <th>Invitation</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
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

    @include('dashboard.billing.packages.delete')
    @include('dashboard.billing.packages.scripts')
@endsection
