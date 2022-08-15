@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">

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
                        <h3 class="panel-title pt-3 head">Billing > Expenses > <span class="text-primary">New</span></h3>
                        <h3 class="panel-title pt-2 head_mob" style="font-size: 15px">Billing > Expenses > <span class="text-primary">New</span></h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2 padd" style="float: right;"
                                onclick="window.location.href='{{route('billing.expenses')}}';"><i
                                    class="fas fa-arrow-left"></i></button>
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

                        <form action="{{route('billing.expenses.store')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label for="description">Description</label>
                                    <input type="text" name="description" id="description" class="form-control"
                                           placeholder="Description" autocomplete="off" required/>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="total">Total</label>
                                    <input type="text" name="total" id="total" class="form-control"
                                           placeholder="Total" autocomplete="off" required/>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="expenseDate">Invoice Date</label>
                                    <input type="text" class="form-control datepicker" name="expenseDate"
                                           id="expenseDate" data-format="mm/dd/yyyy"  autocomplete="off">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="vendor">Vendor</label>
                                    <input type="text" name="vendor" id="vendor" class="form-control"
                                           placeholder="Vendor" autocomplete="off" required/>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="location">Location</label>
                                    <select class="form-control select2" name="location" id="location" required>
                                        <option value="">Select</option>
                                        <option value="0">General</option>
                                        @foreach($Locations as $index => $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="currency">Currency</label>
                                    <select class="form-control" name="currency" id="currency"
                                            onchange="checkExpenseCurrency();" required>
                                        <option value="USD">USD</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2" id="_currencyNameBlock" style="display:none;">
                                    <label for="other_currency_name">Curreny Name</label>
                                    <input type="text" name="other_currency_name" id="other_currency_name"
                                           class="form-control" placeholder="Currency Name" autocomplete="off"/>
                                </div>
                                <div class="col-md-3 mb-2" id="_exchangeRateBlock" style="display:none;">
                                    <label for="rate">Exchange Rate</label>
                                    <input type="text" name="rate" id="rate" class="form-control"
                                           placeholder="Exchange Rate" autocomplete="off"/>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" placeholder="Notes"
                                              rows="3" autocomplete="off"></textarea>
                                </div>

                                <div class="col-md-12 text-center mt-3">
                                    <input type="submit" class="btn btn-primary " name="submitAddExpenseForm"
                                           id="submitAddUserForm" value="Save"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.billing.expenses.scripts')
@endsection
