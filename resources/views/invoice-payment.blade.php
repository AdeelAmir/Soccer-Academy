@extends('layouts.siteapp')
@section('content')
    <style media="screen">
        #page-container {
            position: relative;
            min-height: 80vh;
        }

        #content-wrap {
            padding-bottom: 2.5rem; /* Footer height */
        }

        #footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 2.5rem; /* Footer height */
        }

        .button-green-1 {
            background-color: green;
            border-color: green;
            color: #fff;
        }

        .button-green-2 {
            background-color: green;
            border-color: green;
            color: #fff;
            padding: 12px 8px 12px 8px;
        }

        .button-blue-1 {
            background-color: deepskyblue;
            border-color: deepskyblue;
            color: #fff;
        }

        .button-blue-2 {
            background-color: deepskyblue;
            border-color: deepskyblue;
            color: #fff;
            padding: 12px 8px 12px 8px;
        }

        .btn:hover, .btn:focus, .btn:active {
            color: rgba(255, 255, 255, 0.9);
            box-shadow: none;
        }
    </style>

    <div class="loading">Loading</div>
    <div id="page-container">
        <div id="content-wrap">
            <form action="#"
                  method="post" enctype="multipart/form-data" id="invoice-payment-form">
                @csrf

                <input type="hidden" name="invoice_id" id="invoice_id" value="{{$InvoiceId}}" />
                <input type="hidden" name="parent_name" id="parent_name" value="{{$ParentName}}" />
                <input type="hidden" name="parent_email" id="parent_email" value="{{$ParentEmail}}" />
                <input type="hidden" name="parent_phone" id="parent_phone" value="{{$ParentPhone}}" />
                <input type="hidden" name="parent_state" id="parent_state" value="{{$ParentState}}" />
                <input type="hidden" name="parent_city" id="parent_city" value="{{$ParentCity}}" />
                <input type="hidden" name="parent_zip_code" id="parent_zip_code" value="{{$ParentZipCode}}" />

                <input type="hidden" name="invoice_total" id="invoice_total" value="{{round(floatval($InvoiceDetails[0]->total_bill), 2)}}" />

                <input type="hidden" name="PaymentIntentId" id="PaymentIntentId" value="">
                <input type="hidden" name="ClientSecret" id="ClientSecret" value="">
                <input type="hidden" name="StripeCustomerId" id="StripeCustomerId" value="">

                <div class="container-fluid pt-5">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <img src="{{ asset('public/assets/images/Logo.jpg')}}" alt="logo-small"
                                 style="width: 125px;" class="img-fluid mb-3">
                        </div>

                        <div class="col-md-12">
                            <h3 class="text-center mt-4 mb-5">Make Payment</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-2 col-md-8">
                            @if(\Illuminate\Support\Facades\Session::has('success'))
                                <div class="alert alert-success mb-3" id="message-alert">
                                    <button type="button" class="close" data-dismiss="alert"><span
                                                aria-hidden="true">×</span> <span
                                                class="sr-only">Close</span></button>
                                    {{\Illuminate\Support\Facades\Session::get('success')}}
                                </div>
                            @elseif(\Illuminate\Support\Facades\Session::has('error'))
                                <div class="alert alert-danger mb-3" id="message-alert">
                                    {{\Illuminate\Support\Facades\Session::get('error')}}
                                </div>
                            @endif
                            <div class="alert alert-danger mb-3" id="error-message-alert" style="display: none;"></div>

                            @if($DueDateStatus)
                                <div class="alert alert-warning mb-3">
                                    Invoice Due date has passed.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Get Checkout --}}
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <div class="row">
                                {{--Personal Information Card--}}
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <h2 class="panel-title">
                                            Personal Information
                                        </h2>
                                        <div class="panel-body">
                                            <table border="0" style="width: 100%;">
                                                <tr>
                                                    <td width="25%" class="pt-2 pb-2">
                                                        Parent Name:
                                                    </td>
                                                    <td width="75%" class="pt-2 pb-2 text-black">{{$ParentName}}</td>
                                                </tr>
                                                <tr>
                                                    <td width="25%" class="pt-2 pb-2">
                                                        Parent Address:
                                                    </td>
                                                    <td width="75%" class="pt-2 pb-2 text-black">{{$ParentAddress}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                {{--Stripe--}}
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <h2 class="panel-title">
                                            Payment Information
                                        </h2>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3" id="stripeErrorAlert"
                                                     style="display: none;">
                                                    <div class="alert alert-danger" id="stripeErrorAlertMessage">
                                                        Stripe Error!
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <form id="payment-form">
                                                        <div id="payment-element">
                                                            <!-- Elements will create form elements here -->
                                                        </div>
                                                        <div id="error-message">
                                                            <!-- Display error message to your customers here -->
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{--Summary Card--}}
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <h2 class="panel-title">
                                            Summary
                                        </h2>
                                        <div class="panel-body">
                                            <table border="0" style="width: 100%;">
                                                <tr>
                                                    <td width="25%" class="pt-2 pb-2">
                                                        Subtotal:
                                                    </td>
                                                    <td width="75%" class="pt-2 pb-2 text-black">${{$SubTotal}}</td>
                                                </tr>
                                                <tr>
                                                    <td width="25%" class="pt-2 pb-2">
                                                        Discount ({{$InvoiceDetails[0]->discount . '%'}}):
                                                    </td>
                                                    <td width="75%" class="pt-2 pb-2 text-black">${{$InvoiceDetails[0]->discount_price}}</td>
                                                </tr>
                                                <tr>
                                                    <td width="25%" class="pt-2 pb-2">
                                                        Processing Fee ({{$InvoiceDetails[0]->processing_fee . '%'}}):
                                                    </td>
                                                    <td width="75%" class="pt-2 pb-2 text-black">${{$InvoiceDetails[0]->processing_fee_price}}</td>
                                                </tr>
                                                <tr>
                                                    <td width="25%" class="pt-2 pb-2">
                                                        Sales Tax ({{$InvoiceDetails[0]->tax_rate . '%'}}):
                                                    </td>
                                                    <td width="75%" class="pt-2 pb-2 text-black">${{$InvoiceDetails[0]->tax_rate_price}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="alert alert-success">
                                        <strong>Total:</strong>
                                        <strong class="float-right">${{round(floatval($InvoiceDetails[0]->total_bill), 2)}}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <input type="button" class="btn btn-primary float-right" id="confirmBtn"
                                           onclick="SubmitInvoiceForm(this);" value="Pay Now" />
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Get Checkout --}}
                </div>
            </form>
        </div>
    @include('layouts.partials.scripts')

    {{--Footer--}}
    <!-- <div class="container-fluid" style="background: #222222; margin-top: 150px;"> -->
        <footer id="footer">
            <div class="container-fluid" style="background: #222222;">
                <div class="row">
                    <div class="col-md-12 text-center" style="margin-top: 20px">
                        <a style="color:white" href="https://www.mysocceracademy.com/" target="_blank">HOME</a>
                        <br><br>
                        <a style="color:white" href="{{url('registration')}}">REGISTRATION</a>
                        <br><br>
                        <a style="color:white" href="https://www.mysocceracademy.com/contact/"
                           target="_blank">CONTACT</a>
                        <br><br>
                        <a style="color:white" href="{{url('login')}}">LOGIN</a>
                    </div>
                    <div class="col-md-12 text-center">
                        <br>
                        <a class="btn btn-primary icon" style="background-color: transparent;"
                           href="https://www.facebook.com/mySoccerAcademy/" role="button" target="_blank">
                            <i class="fab fa-facebook" style="font-size: 17px"></i></a>
                        <a class="btn btn-primary icon" style="background-color: transparent;"
                           href="https://instagram.com/mysocceracademy" role="button" target="_blank">
                            <i class="fab fa-instagram" style="font-size: 17px"></i></a>
                        <a class="btn btn-primary icon" style="background-color:transparent;"
                           href="https://twitter.com/socceracademy" role="button" target="_blank">
                            <i class="fab fa-twitter" style="font-size: 17px"></i></a>
                        <a class="btn btn-primary icon" style="background-color:transparent;"
                           href="#" role="button" target="_blank">{{--https://twitter.com/socceracademe--}}
                            <i class="fab fa-youtube" style="font-size: 17px"></i></a>
                    </div>
                    <div class="col-md-12 text-center mt-3 mb-5">
                        <p class="text-center text-muted mb-3" style="color: white;font-size: 15px">
                            &copy; Copyright. My Soccer Academy LLC. All Rights Reserved.
                        </p>
                        <p class="text-center text-muted mb-3" style="color: white;">
                            <a style="color:white" href="https://www.mysocceracademy.com/about/">Terms and
                                Conditions</a>&nbsp;|&nbsp;<a style="color:white;" href="#">Privacy Policy</a>
                        </p>
                        <p class="text-center text-muted mb-0" style="color: white;">
                            Developed & Powered by <a style="color:white" href="{{url('/')}}">WORX LLC</a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection
