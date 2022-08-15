@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row" id="EditInvoicePage">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Billing > Invoices > <span class="text-primary">View</span></h3>
                        <button type="button" class="btn btn-primary mb-0" style="float: right;"
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

                        <form action="{{route('billing.invoices.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="invoice_id" id="invoice_id" value="{{$invoice_id}}">
                            <input type="hidden" name="processing_fee" id="processing_fee" value="{{$InvoiceDetails[0]->processing_fee}}">
                            <input type="hidden" name="tax_rate" id="tax_rate" value="{{$InvoiceDetails[0]->tax_rate}}">
                            <input type="hidden" name="old_due_type" id="old_due_type" value="{{$InvoiceDetails[0]->due_type}}">
                            <input type="hidden" name="old_send_date" id="old_send_date" value="{{$InvoiceDetails[0]->send_date}}">
                            <input type="hidden" name="old_due_date" id="old_due_date" value="{{$InvoiceDetails[0]->due_date}}">

                            <div class="custom-row">
                                <div class="custom-col-4 mb-2">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                           maxlength="100" value="{{$InvoiceDetails[0]->title}}" disabled required>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="bill_to">Bill To</label>
                                    <select class="form-control select2" name="bill_to" id="bill_to"
                                            onchange="checkBillTo();"
                                            disabled required>
                                      <option value="">Select Parent</option>
                                      <option value="-1" <?php if($InvoiceDetails[0]->bill_to == -1){echo "selected";} ?>>Other</option>
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
                                      <option value="{{$item->id}}" <?php if($InvoiceDetails[0]->bill_to == $item->id){echo "selected";} ?>>{{$ParentName}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2" id="fullNameSection" <?php if($InvoiceDetails[0]->bill_to != -1){echo 'style="display:none;"';} ?>>
                                    <div class="form-group">
                                        <label class="control-label" for="fullName">Full Name</label>
                                        <input class="form-control" name="fullName"
                                               id="fullName"
                                               disabled
                                               value="{{$InvoiceDetails[0]->fullName}}"
                                               placeholder="Enter Full Name" />
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2" id="stateSection" <?php if($InvoiceDetails[0]->bill_to != -1){echo 'style="display:none;"';} ?>>
                                    <div class="form-group">
                                        <label class="control-label" for="state">State</label>
                                        <select name="state" id="state" class="form-control select2" disabled onchange="LoadStateCountyCity();">
                                            <option value="" selected>Select State</option>
                                            @foreach($States as $state)
                                                <option value="{{$state->name}}" <?php if($InvoiceDetails[0]->state == $state->name){echo "selected";} ?>>{{$state->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2" id="citySection" <?php if($InvoiceDetails[0]->bill_to != -1){echo 'style="display:none;"';} ?>>
                                    <div class="form-group">
                                        <label class="control-label" for="city">City</label>
                                        <select name="city" id="city" class="form-control" disabled>
                                            <option value="" selected>Select City</option>
                                            @foreach($Cities as $index => $item)
                                                @if($InvoiceDetails[0]->city == $item->city)
                                                    <option value="{{$item->city}}" selected>{{$item->city}}</option>
                                                @else
                                                    <option value="{{$item->city}}">{{$item->city}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2" id="streetSection" <?php if($InvoiceDetails[0]->bill_to != -1){echo 'style="display:none;"';} ?>>
                                    <div class="form-group">
                                        <label class="control-label" for="street">Street</label>
                                        <input class="form-control" name="street" id="street"
                                               value="{{$InvoiceDetails[0]->street}}"
                                               disabled placeholder="Street"/>
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2" id="zipcodeSection" <?php if($InvoiceDetails[0]->bill_to != -1){echo 'style="display:none;"';} ?>>
                                    <div class="form-group">
                                        <label class="control-label" for="zipcode">Zip code/Postal Code</label>
                                        <input type="number" name="zipcode" id="zipcode"
                                               class="form-control"
                                               data-validate="minlength[5]"
                                               onkeypress="limitKeypress(event,this.value,5)"
                                               onblur="limitZipCodeCheck();"
                                               disabled
                                               value="{{$InvoiceDetails[0]->zipcode}}"
                                               placeholder="Zip Code"/>
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2" id="playerSection" <?php if($InvoiceDetails[0]->bill_to != -1){echo 'style="display:none;"';} ?>>
                                    <label for="bill_to">Player</label>
                                    <select class="form-control select2" name="player" id="player" disabled>
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
                                      <option value="{{$item->id}}" <?php if($InvoiceDetails[0]->player == $item->id){echo "selected";} ?>>{{$PlayerName}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="due_type">Due</label>
                                    <select class="form-control select2" name="due_type" id="due_type"
                                            disabled required onchange="checkDueType();">
                                      <option value="">Select</option>
                                      <option value="on receipt" <?php if($InvoiceDetails[0]->due_type == "on receipt"){echo "selected";} ?>>On Receipt</option>
                                      <option value="pay now" <?php if($InvoiceDetails[0]->due_type == "pay now"){echo "selected";} ?>>Pay Now</option>
                                      <option value="on a specific date" <?php if($InvoiceDetails[0]->due_type == "on a specific date"){echo "selected";} ?>>On a Specific Date</option>
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2 sendDateBlock" <?php if($InvoiceDetails[0]->due_type != "on a specific date"){echo "style='display:none;'";} ?>>
                                    <label for="sendDate">Send Date</label>
                                    @if($InvoiceDetails[0]->send_date == "")
                                    <input type="text" class="form-control datepicker" name="sendDate"
                                           disabled id="sendDate" data-format="mm/dd/yyyy">
                                    @else
                                    <input type="text" class="form-control datepicker" name="sendDate" id="sendDate"
                                           data-format="mm/dd/yyyy" value="{{\Carbon\Carbon::parse($InvoiceDetails[0]->send_date)->format('m/d/Y')}}">
                                    @endif
                                </div>
                                <div class="custom-col-4 mb-2 dueDateBlock" <?php if($InvoiceDetails[0]->due_type != "on a specific date"){echo "style='display:none;'";} ?>>
                                    <label for="dueDate">Due Date</label>
                                    @if($InvoiceDetails[0]->due_date == "")
                                    <input type="text" class="form-control datepicker" name="dueDate"
                                           disabled id="dueDate" data-format="mm/dd/yyyy">
                                    @else
                                    <input type="text" class="form-control datepicker" name="dueDate" id="dueDate"
                                           data-format="mm/dd/yyyy" value="{{\Carbon\Carbon::parse($InvoiceDetails[0]->due_date)->format('m/d/Y')}}"
                                           disabled>
                                    @endif
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="discount">Discount</label>
                                    <input type="number" class="form-control" name="discount" id="discount"
                                           step="any" value="{{$InvoiceDetails[0]->discount}}" min="0" disabled required>
                                </div>
                                <div class="col-md-12 mb-2 mt-2" style="margin-left: -5px;">
                                    <label for="message">Message</label>
                                    <textarea name="message" id="message" rows="5" cols="80" class="form-control" disabled>{{$InvoiceDetails[0]->message}}</textarea>
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
                                            @if(sizeof($InvoiceItems) > 0)
                                              @foreach($InvoiceItems as $index => $item)
                                              <div data-repeater-item="">
                                                  <div class="row">
                                                      <div class="col-md-3 mb-2">
                                                          <label for="item">Item</label>
                                                          <input type="text"
                                                                 name="item"
                                                                 id="item"
                                                                 maxlength="100"
                                                                 value="{{$item->item}}"
                                                                 class="form-control" />
                                                      </div>

                                                      <div class="col-md-3 mb-2">
                                                          <label for="price">Price</label>
                                                          <input type="text"
                                                                 name="price"
                                                                 id="price"
                                                                 maxlength="100"
                                                                 value="{{$item->price}}"
                                                                 class="form-control" />
                                                      </div>

                                                      <div class="col-md-3 mb-2">
                                                          <label for="quantity">Quantity</label>
                                                          <input type="number"
                                                                 name="quantity"
                                                                 id="quantity"
                                                                 min="0"
                                                                 value="{{$item->quantity}}"
                                                                 class="form-control" />
                                                      </div>

                                                      <div class="col-md-3 mb-2">
                                                          <label> &nbsp;</label>
                                                          <div>
                                                              <span data-repeater-delete="" class="btn btn-danger btn-sm hide-data-repeater-btn">
                                                                  <span class="far fa-trash-alt mr-1"></span> Delete
                                                              </span>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                              @endforeach
                                            @else
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
                                                            <span data-repeater-delete="" class="btn btn-danger btn-sm hide-data-repeater-btn">
                                                                <span class="far fa-trash-alt mr-1"></span> Delete
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <span data-repeater-create="" class="btn btn-primary btn-sm hide-data-repeater-btn">
                                                    <span class="fa fa-plus"></span> Item
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center mt-4">
                                    <button type="button" name="editInvoiceBtn" id="editInvoiceBtn"
                                            class="btn btn-primary" onclick="checkConfirmation();">Edit</button>
                                    <input type="submit" class="btn btn-primary " name="submitEditInvoiceForm"
                                           id="submitEditInvoiceForm" value="Save Changes" style="display:none;"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.billing.invoices.scripts')
    @include('dashboard.billing.invoices.editConfirmationModal')
@endsection
