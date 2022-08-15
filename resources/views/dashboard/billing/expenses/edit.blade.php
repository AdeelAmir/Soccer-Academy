@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Billing > Expenses > <span class="text-primary">View</span></h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2" style="float: right;"
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

                        <form action="{{route('billing.expenses.update')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="_id" value="{{$expense_details[0]->id}}"/>

                            @foreach($expense_details as $expense)
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="description">Description</label>
                                        <input type="text" name="description" id="description" class="form-control"
                                               placeholder="Description" value="{{$expense->description}}"
                                               disabled required/>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="total">Total</label>
                                        <input type="text" step="any" name="total" id="total" class="form-control"
                                               placeholder="Total" value="{{$expense->total}}" disabled required/>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="expenseDate">Invoice Date</label>
                                        <input type="text" class="form-control datepicker" name="expenseDate"
                                        id="expenseDate" value="{{\Carbon\Carbon::parse($expense->expense_date)->format('m/d/Y')}}"
                                        data-format="mm/dd/yyyy" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="vendor">Vendor</label>
                                        <input type="text" name="vendor" id="vendor" class="form-control"
                                               placeholder="Vendor" value="{{$expense->vendor}}"
                                               disabled required/>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="location">Location</label>
                                        <select class="form-control select2" name="location" id="location" disabled required>
                                            <option value="">Select</option>
                                            <option value="0" <?php if($expense->location == 0){echo "selected";} ?>>General</option>
                                            @foreach($Locations as $index => $item)
                                                <option value="{{$item->id}}" <?php if($expense->location == $item->id){echo "selected";} ?>>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="currency">Currency</label>
                                        <select class="form-control" name="currency" id="currency"
                                                onchange="checkExpenseCurrency();" disabled required>
                                            <option value="USD" <?php if ($expense->currency == "USD") {
                                                echo "selected";
                                            } ?> >USD
                                            </option>
                                            <option value="Others" <?php if ($expense->currency == "Others") {
                                                echo "selected";
                                            } ?> >Others
                                            </option>
                                        </select>
                                    </div>
                                    @if($expense->currency == "USD")
                                    <div class="col-md-3 mb-3" id="_currencyNameBlock" style="display:none;">
                                    @else
                                    <div class="col-md-3 mb-3" id="_currencyNameBlock">
                                    @endif
                                        <label for="other_currency_name">Curreny Name</label>
                                        <input type="text" name="other_currency_name"
                                               id="other_currency_name" class="form-control"
                                               value="{{$expense->other_currency_name}}"
                                               disabled
                                               placeholder="Currency Name"/>
                                    </div>

                                    @if($expense->currency == "USD")
                                    <div class="col-md-3 mb-3" id="_exchangeRateBlock" style="display:none;">
                                    @else
                                    <div class="col-md-3 mb-3" id="_exchangeRateBlock">
                                    @endif
                                        <label for="rate">Exchange Rate</label>
                                        <input type="text" name="rate" id="rate"
                                               class="form-control"
                                               value="{{$expense->exchange_rate}}"
                                               disabled
                                               placeholder="Exchange Rate"/>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="notes">Notes</label>
                                        <textarea name="notes" id="notes"
                                                  class="form-control" placeholder="Notes"
                                                  rows="3"
                                                  disabled
                                                  required>{{$expense->note}}</textarea>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <button type="button" name="editExpenseBtn" id="editExpenseBtn"
                                                class="btn btn-primary" onclick="checkConfirmation();">Edit</button>
                                        <input type="submit" class="btn btn-primary " name="submitEditExpenseForm"
                                               id="submitEditExpenseForm" value="Save Changes" style="display:none;"/>
                                    </div>
                                </div>
                            @endforeach
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.billing.expenses.scripts')
    @include('dashboard.billing.expenses.editConfirmationModal')
@endsection
