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
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-2">Billing > <span class="text-primary">Transactions</span></h3>
                        <div class="panel-options">
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
                                {{\Illuminate\Support\Facades\Session::get('success')}}
                            </div>
                        @elseif(\Illuminate\Support\Facades\Session::has('error'))
                            <div class="alert alert-danger" id="message-alert">
                                {{\Illuminate\Support\Facades\Session::get('error')}}
                            </div>
                        @endif
                        <div class="responsive" >
                            <table class="table w-100 tbl-responsive" id="transactionsTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Transaction Id</th>
                                    <th style="width: 10%;">User Id</th>
                                    <th style="width: 10%;">Bill To</th>
                                    <th style="width: 10%;">Total Amount</th>
                                    <th style="width: 10%;">Amount Paid</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 10%;">Comments</th>
                                    <th style="width: 15%;">Paid Date</th>
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

    @include('dashboard.billing.transactions.scripts')
@endsection