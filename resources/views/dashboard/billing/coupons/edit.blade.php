@extends('dashboard.layouts.app')
@section('content')
    <style>

        @media (max-width: 767px) {

            .padd {
                padding:4px 9px;
            }
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-2">Billing > Coupons > <span class="text-primary">Edit</span></h3>
                        <button type="button" class="btn btn-primary mb-0 padd" style="float: right;"
                                onclick="window.location.href='{{route('billing.coupons')}}';"><i
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

                        <form action="{{route('billing.coupons.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$Id}}">

                            <div class="custom-row">
                                <div class="custom-col-4 mb-2">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" maxlength="100" value="{{$Coupon[0]->coupon_name}}" required>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="coupon_code">Code</label>
                                    <input type="text" class="form-control" name="coupon_code" id="coupon_code" value="{{$Coupon[0]->coupon_code}}" readonly>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Type</label>
                                        <select name="type" id="type" class="form-control select2" required>
                                            <option value="">Select</option>
                                            <option value="flat" <?php if($Coupon[0]->coupon_type == 'flat') { echo 'selected'; } ?>>Flat</option>
                                            <option value="rate" <?php if($Coupon[0]->coupon_type == 'rate') { echo 'selected'; } ?>>Rate %</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <div class="form-group">
                                        <label class="control-label" for="limit">Limit</label>
                                        <input type="number" name="limit" id="limit"
                                               step="any"
                                               class="form-control"
                                               placeholder="Limit"
                                               maxlength="100"
                                               value="{{$Coupon[0]->coupon_limit}}"
                                               required />
                                    </div>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="applyOn">Apply on Membership</label>
                                    <select class="form-control select2" name="applyOn" id="applyOn" required>
                                        <option value="">Select</option>
                                        <option value="oneTime" <?php if($Coupon[0]->coupon_apply == 'oneTime') { echo 'selected'; } ?>>One Time</option>
                                        <option value="everyMonth" <?php if($Coupon[0]->coupon_apply == 'everyMonth') { echo 'selected'; } ?>>Every Month</option>
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <div class="form-group">
                                        <label class="control-label" for="rate">Rate</label>
                                        <input type="number" name="rate" id="rate"
                                               class="form-control"
                                               placeholder="Limit"
                                               maxlength="100"
                                               step="any"
                                               value="{{$Coupon[0]->coupon_rate}}"
                                               required />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center mt-4">
                                    <input type="submit" class="btn btn-primary" value="Save"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.billing.coupons.scripts')
@endsection