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

            .padd {
                padding:4px 9px;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-2">Billing > <span class="text-primary">Coupons</span></h3>
                        <div class="panel-options">
                            <button type="button" class="btn btn-primary padd" onclick="window.location.href='{{route('billing')}}';">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <button type="button" class="btn btn-primary padd" onclick="window.location.href='{{route('billing.coupons.add')}}';" data-toggle="tooltip" title="Create New Coupon">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
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
                        <div class="responsive">
                            <table class="table w-100 tbl-responsive" id="couponsTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 20%;">Name</th>
                                    <th style="width: 10%;">Code</th>
                                    <th style="width: 15%;">Type</th>
                                    <th style="width: 10%;">Limit</th>
                                    <th style="width: 15%;">Apply On</th>
                                    <th style="width: 10%;">Rate</th>
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

    @include('dashboard.billing.coupons.scripts')
    @include('dashboard.billing.coupons.delete')
@endsection
