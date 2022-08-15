@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1" id="beforeTablePage"></div>
            <div class="col-md-10" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Expenses</h3>
                        <div class="panel-options">
                          <button type="button" class="btn btn-primary"
                                  onclick="UserFilterBackButton();"><i class="fas fa-filter"></i></button>
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

                        <table class="table w-100 tbl-responsive" id="parentsAllExpensesTable">
                            <thead>
                            <tr class="replace-inputs">
                                <th style="width: 5%;">#</th>
                                <th style="width: 15%;">Id</th>
                                <th style="width: 30%;">Expense</th>
                                <th style="width: 20%;">Amount</th>
                                <th style="width: 15%;">Status</th>
                                <th style="width: 15%;">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
                                        onclick="DestroyParentExpenseDataTable(); MakeParentExpenseTable();">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.billing.expenses.scripts')
@endsection
