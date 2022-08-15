@extends('dashboard.layouts.app')
@section('content')
    <style>

        @media (min-width: 992px) {
            .responsive {

            }

            .head {

            }

            .head_mob {
                display: none;
            }

        }


        @media (max-width: 767px) {
            .responsive {
                overflow-x: auto;
            }

            .head {
                display: none;
            }

            .head_mob {

            }

            .padd {
                padding: 4px 8px;
            }
        }

    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1" id="beforeTablePage"></div>
            <div class="col-md-10" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3 head">Billing > <span class="text-primary">Expenses</span></h3>
                        <h3 class="panel-title pt-2 head_mob" style="font-size: 15px">Billing > <span
                                    class="text-primary">Expenses</span></h3>
                        <div class="panel-options">
                            <button type="button" class="btn btn-primary padd"
                                    onclick="UserFilterBackButton();"><i class="fas fa-filter"></i></button>
                            <button type="button" class="btn btn-primary padd"
                                    data-toggle="tooltip" title="Create New Expense"
                                    onclick="window.location.href='{{route('billing.expenses.add')}}';"><i
                                        class="fas fa-plus-square"></i></button>
                            <button type="button" class="btn btn-primary padd"
                                    onclick="window.location.href='{{route('billing')}}';">
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
                        <div class="responsive">
                            <table class="table w-100 tbl-responsive" id="expensesTable">
                                <thead>
                                <tr class="replace-inputs">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Description</th>
                                    <th style="width: 10%;">Total</th>
                                    <th style="width: 10%;">Date</th>
                                    <th style="width: 10%;">Vendor</th>
                                    <th style="width: 10%;">Location</th>
                                    <th style="width: 25%;">Note</th>
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

            <div class="col-md-2" id="filterPage" style="display:none;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title w-100">
                            Filter
                            <i class="fas fa-times" style="font-size: 16px; cursor: pointer; float: right;"
                               onclick="UserFilterCloseButton();"></i>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" id="expense_start_date" value=""
                                       class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" id="expense_end_date" value="" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <button type="button" name="button" class="btn btn-primary"
                                        onclick="DestroyDataTable(); MakeExpenseTable();">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.billing.expenses.delete')
    @include('dashboard.billing.expenses.scripts')
@endsection
