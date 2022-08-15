@extends('dashboard.layouts.app')
@section('content')
    <style>
        .badge.badge-primary {
            background-color: #062C90;
        }
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
            <div class="col-md-offset-1 col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @if($Role == 1 || $Role == 2 || $Role == 3)
                            <h3 class="panel-title pt-2">Billing > <span class="text-primary">Memberships</span></h3>
                            <div class="panel-options">
                                <button type="button" class="btn btn-primary padd" onclick="window.location.href='{{route('billing')}}';">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                        @elseif($Role == 5)
                            <h3 class="panel-title pt-2">Memberships</h3>
                            <div class="panel-options">
                                <button type="button" class="btn btn-primary padd" onclick="window.location.href='{{route('dashboard.memberships.new')}}';">
                                    <i class="fas fa-plus-square"></i>
                                </button>
                            </div>
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

                        <div class="alert alert-danger" id="message-alert" style="display: none;"></div>
                        <div class="responsive" >
                            <table class="table w-100 tbl-responsive" id="subscriptionsTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">Parent</th>
                                    <th style="width: 15%;">Player</th>
                                    <th style="width: 15%;">Package</th>
                                    <th style="width: 10%;">Package Type</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 10%;">Register Date</th>
                                    <th style="width: 10%;">Next Billing</th>
                                    <th style="width: 10%;">Action</th>
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

    @include('dashboard.billing.subscriptions.scripts')
    @include('dashboard.billing.subscriptions.activateModal')
    @include('dashboard.billing.subscriptions.suspendModal')
    @include('dashboard.billing.subscriptions.holdModal')
    @include('dashboard.billing.subscriptions.cancelModal')
@endsection
