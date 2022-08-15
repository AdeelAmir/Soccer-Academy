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
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-2">Billing > Invoices > <span class="text-primary">New</span></h3>
                        <button type="button" class="btn btn-primary mb-0 padd" style="float: right;"
                                onclick="window.location.href='{{route('billing.invoices')}}';"><i
                                    class="fas fa-arrow-left"></i></button>
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

                        <form action="{{route('billing.invoices.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="invoice_no" id="invoice_no" value="{{$invoice_no}}">
                            <input type="hidden" name="processing_fee" id="processing_fee" value="{{$Configuration[0]->processing_fee}}">
                            <input type="hidden" name="tax_rate" id="tax_rate" value="{{$Configuration[0]->tax_rate}}">
                            <div class="custom-row">
                                <div class="custom-col-4 mb-2">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title" maxlength="100" required>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="bill_to">Bill To</label>
                                    <select class="form-control select2" name="bill_to" id="bill_to" onchange="checkBillTo();" required>
                                      <option value="">Select Parent</option>
                                      <option value="-1">Other</option>
                                      <?php
                                      $ParentName = "";
                                      ?>
                                      @foreach($Parents as $index => $item)
                                      <?php
                                      if ($item->middleName != "") {
                                        $ParentName = $item->firstName . " " . $item->middleName . " " . $item->lastName;
                                      } else {
                                        $ParentName = $item->firstName . " " . $item->lastName;
                                      }
                                      ?>
                                      <option value="{{$item->id}}">{{$ParentName}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2" id="fullNameSection" style="display:none;">
                                    <div class="form-group">
                                        <label class="control-label" for="fullName">Full Name</label>
                                        <input class="form-control" name="fullName"
                                               id="fullName"
                                               placeholder="Enter Full Name" />
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2" id="stateSection" style="display:none;">
                                    <div class="form-group">
                                        <label class="control-label" for="state">State</label>
                                        <select name="state" id="state" class="form-control select2" onchange="LoadStateCountyCity();">
                                            <option value="" selected>Select State</option>
                                            @foreach($States as $state)
                                                <option value="{{$state->name}}">{{$state->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2" id="citySection" style="display: none;">
                                    <div class="form-group">
                                        <label class="control-label" for="city">City</label>
                                        <select name="city" id="city" class="form-control">
                                            <option value="" selected>Select City</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2" id="streetSection" style="display:none;">
                                    <div class="form-group">
                                        <label class="control-label" for="street">Street</label>
                                        <input class="form-control" name="street" id="street"
                                               placeholder="Street"/>
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2" id="zipcodeSection" style="display:none;">
                                    <div class="form-group">
                                        <label class="control-label" for="zipcode">Zip code/Postal Code</label>
                                        <input type="number" name="zipcode" id="zipcode"
                                               class="form-control"
                                               data-validate="minlength[5]"
                                               onkeypress="limitKeypress(event,this.value,5)"
                                               onblur="limitZipCodeCheck();"
                                               placeholder="Zip Code"/>
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2" id="playerSection" style="display:none;">
                                    <label for="bill_to">Player</label>
                                    <select class="form-control select2" name="player" id="player">
                                      <option value="">Select Player</option>
                                      <?php
                                      $PlayerName = "";
                                      ?>
                                      @foreach($Players as $index => $item)
                                      <?php
                                      if ($item->middleName != "") {
                                        $PlayerName = $item->firstName . " " . $item->middleName . " " . $item->lastName;
                                      } else {
                                        $PlayerName = $item->firstName . " " . $item->lastName;
                                      }
                                      ?>
                                      <option value="{{$item->id}}">{{$PlayerName}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="due_type">Due</label>
                                    <select class="form-control select2" name="due_type" id="due_type" required onchange="checkDueType();">
                                      <option value="">Select</option>
                                      <option value="on receipt">On Receipt</option>
                                      <option value="pay now">Pay Now</option>
                                      <option value="on a specific date">On a Specific Date</option>
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2 sendDateBlock" style="display:none;">
                                    <label for="sendDate">Send Date</label>
                                    <input type="text" class="form-control datepicker" name="sendDate" id="sendDate" data-format="mm/dd/yyyy">
                                </div>
                                <div class="custom-col-4 mb-2 dueDateBlock" style="display:none;">
                                    <label for="dueDate">Due Date</label>
                                    <input type="text" class="form-control datepicker" name="dueDate" id="dueDate" data-format="mm/dd/yyyy">
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="discount">Discount</label>
                                    <input type="number" class="form-control" name="discount" id="discount" step="any" value="0" min="0" required>
                                </div>
                                <div class="col-md-12 mt-2 mb-2" style="margin-left: -5px;">
                                    <label for="message">Message</label>
                                    <textarea name="message" rows="5" cols="80" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4 style="color: black;">Items</h4>
                                    <hr class="mt-2 mb-2">
                                </div>

                                <div class="col-md-12">
                                    <div class="repeater-custom-show-hide">
                                        <div data-repeater-list="item">
                                            <div data-repeater-item="">
                                                <div class="row">
                                                    <div class="col-md-3 mb-2">
                                                        <label for="item">Item</label>
                                                        <input type="text"
                                                               name="item"
                                                               id="item"
                                                               maxlength="100"
                                                               class="form-control" />
                                                    </div>

                                                    <div class="col-md-3 mb-2">
                                                        <label for="price">Price</label>
                                                        <input type="text"
                                                               name="price"
                                                               id="price"
                                                               maxlength="100"
                                                               class="form-control" />
                                                    </div>

                                                    <div class="col-md-3 mb-2">
                                                        <label for="quantity">Quantity</label>
                                                        <input type="number"
                                                               name="quantity"
                                                               id="quantity"
                                                               min="0"
                                                               class="form-control" />
                                                    </div>

                                                    <div class="col-md-3 mb-2">
                                                        <label> &nbsp;</label>
                                                        <div>
                                                            <span data-repeater-delete="" class="btn btn-danger btn-sm">
                                                                <span class="far fa-trash-alt mr-1"></span> Delete
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <span data-repeater-create="" class="btn btn-primary btn-sm">
                                                    <span class="fa fa-plus"></span> Item
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center mt-4">
                                    <input type="submit" class="btn btn-primary" value="Send" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.billing.invoices.scripts')
@endsection
