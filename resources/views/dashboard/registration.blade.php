@extends('dashboard.layouts.app')
@section('content')
    <!-- Loader CSS -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/loader.css') }}">

    <style>
        /* Step Form Progress bar start */
        .bs-wizard {
            /*margin-top: 20px;*/
        }

        .bs-wizard {
            /*border-bottom: solid 1px #e0e0e0;*/
            padding: 0 0 10px 0;
        }

        .bs-wizard > .bs-wizard-step {
            padding: 0;
            position: relative;
            width: 50%;
        }

        .bs-wizard > .bs-wizard-step > .bs-wizard-dot {
            background: #104090;
        }

        .bs-wizard > .bs-wizard-step .bs-wizard-stepnum {
            color: #595959;
            font-size: 16px;
        }

        .bs-wizard > .bs-wizard-step .bs-wizard-info {
            color: #999;
            font-size: 14px;
        }

        .bs-wizard > .bs-wizard-step > .bs-wizard-dot {
            position: absolute;
            width: 30px;
            height: 30px;
            display: block;
            top: 45px;
            left: 50%;
            margin-top: -15px;
            margin-left: -15px;
            border-radius: 50%;
        }

        .bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {
            content: ' ';
            width: 14px;
            height: 14px;
            background: #ffffff;
            border-radius: 50px;
            position: absolute;
            top: 8px;
            left: 8px;
        }

        .bs-wizard > .bs-wizard-step > .progress {
            position: relative;
            border-radius: 0;
            height: 8px;
            box-shadow: none;
            margin: 19px 0;
        }

        .bs-wizard > .bs-wizard-step > .progress > .progress-bar {
            width: 0;
            box-shadow: none;
        }

        .bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {
            width: 100%;
        }

        .bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {
            width: 50%;
        }

        .bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {
            width: 0%;
        }

        .bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {
            width: 100%;
        }

        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {
            background-color: #f5f5f5;
        }

        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {
            opacity: 0;
        }

        .bs-wizard > .bs-wizard-step:first-child > .progress {
            left: 50%;
            width: 50%;
        }

        .bs-wizard > .bs-wizard-step:last-child > .progress {
            width: 50%;
        }

        .bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot {
            pointer-events: none;
        }

        .progress-bar {
            float: left;
            width: 0;
            height: 100%;
            font-size: 12px;
            line-height: 18px;
            color: #fff;
            text-align: center;
            background-color: #104090;
            -webkit-box-shadow: inset 0 -1px 0 rgb(0 0 0 / 15%);
            box-shadow: inset 0 -1px 0 rgb(0 0 0 / 15%);
            -webkit-transition: width .6s ease;
            -o-transition: width .6s ease;
            transition: width .6s ease;
        }

        @media screen and (max-width: 768px) {
            .bs-wizard > .bs-wizard-step {
                padding: 0;
                position: relative;
                width: 20%;
            }

            .bs-wizard > .bs-wizard-step .bs-wizard-info {
                color: #999;
                font-size: 11px;
            }
        }

        .btn:hover, .btn:focus, .btn:active {
            color: rgba(255, 255, 255, 0.9);
            box-shadow: none;
        }

        .package-price {
            border-radius: 100%;
            height: 90px;
            width: 90px;
            display: flex;
            align-items: center;
            font-size: 23px;
            box-shadow: 0 2px 4px 0 rgb(0 0 0 / 10%), 0 1px 10px 0 rgb(0 0 0 / 10%);
        }

        .package-heading-1 {
            color: #666666;
        }

        .package-heading-2 {
            color: #e66055;
        }

        .package-heading-3 {
            color: #02769d;
        }

        .package-color-1 {
            background-color: #666666;
            color: #ffffff;
        }

        .package-color-2 {
            background-color: #e66055;
            color: #ffffff;
        }

        .package-color-3 {
            background-color: #02769d;
            color: #ffffff;
        }

        .package-flag {
            position: absolute;
            top: 12px;
            right: 12px;
        }
    </style>

    <?php
    $MagicNumbers = \Illuminate\Support\Facades\DB::table('magic_numbers')
        ->first();
    ?>
    <div class="loading" style="display: none;">Loading</div>
    <input type="hidden" name="leadId" id="leadId" value="{{$LeadConversion[0]->lead_id}}" />
    <input type="hidden" name="packageId" id="packageId" value="{{$Package[0]->id}}" />
    <input type="hidden" name="categoryId" id="categoryId" value="{{$Category[0]->id}}" />
    <input type="hidden" name="categoryTitle" id="categoryTitle" value="{{$Category[0]->title}}" />
    <input type="hidden" name="selectedPackageType" id="selectedPackageType" value="2"/>
    <input type="hidden" name="selectedDays" id="selectedDays" value="2"/>
    <input type="hidden" name="selectedPackageSubPrice" id="selectedPackageSubPrice" value="">
    <input type="hidden" name="selectedPackagePrice" id="selectedPackagePrice" value="">
    <input type="hidden" name="PaymentIntentId" id="PaymentIntentId" value="">
    <input type="hidden" name="ClientSecret" id="ClientSecret" value="">
    <input type="hidden" name="StripeCustomerId" id="StripeCustomerId" value="">
    <input type="hidden" name="ProcessingFee" id="ProcessingFee" value="{{$MagicNumbers->processing_fee}}">
    <input type="hidden" name="TaxRate" id="TaxRate" value="{{$MagicNumbers->tax_rate}}">
    <input type="hidden" name="CouponCodeId" id="CouponCodeId" value="">
    <input type="hidden" name="CouponAmount" id="CouponAmount" value="0">

    <input type="hidden" name="parentFirstName" id="parentFirstName" value="{{$ParentUserDetails[0]->firstName}}" />
    <input type="hidden" name="parentLastName" id="parentLastName" value="{{$ParentUserDetails[0]->lastName}}" />
    <input type="hidden" name="parentPhone" id="parentPhone" value="{{$ParentUserDetails[0]->phone1}}" />
    <input type="hidden" name="parentEmail" id="parentEmail" value="{{\Illuminate\Support\Facades\Auth::user()->email}}" />

    <input type="hidden" name="parentState" id="parentState" value="{{$ParentUserDetails[0]->state}}" />
    <input type="hidden" name="parentCity" id="parentCity" value="{{$ParentUserDetails[0]->city}}" />
    <input type="hidden" name="parentStreet" id="parentStreet" value="{{$ParentUserDetails[0]->street}}" />
    <input type="hidden" name="parentZipCode" id="parentZipCode" value="{{$ParentUserDetails[0]->zipcode}}" />
    <input type="hidden" name="billingPhone" id="billingPhone" value="0"/>

    @foreach($Package as $index => $item)
        @if($item->fee_Type == 'monthly')
            <input type="hidden" name="monthly_registration_fee" id="monthly_registration_fee" value="{{$Package[$index]->registration_fee}}" />
            <input type="hidden" name="monthly_fee_day_1" id="monthly_fee_day_1" value="{{$Package[$index]->monthly_fee_1day}}" />
            <input type="hidden" name="monthly_fee_day_2" id="monthly_fee_day_2" value="{{$Package[$index]->monthly_fee_2day}}" />
            <input type="hidden" name="monthly_fee_day_3" id="monthly_fee_day_3" value="{{$Package[$index]->monthly_fee_3day}}" />
            <input type="hidden" name="monthly_fee_day_4" id="monthly_fee_day_4" value="{{$Package[$index]->monthly_fee_4day}}" />
        @elseif($item->fee_Type == 'semi-annual')
            <input type="hidden" name="semi_registration_fee" id="semi_registration_fee" value="{{$Package[$index]->registration_fee}}" />
            <input type="hidden" name="semi_fee_day_1" id="semi_fee_day_1" value="{{$Package[$index]->monthly_fee_1day}}" />
            <input type="hidden" name="semi_fee_day_2" id="semi_fee_day_2" value="{{$Package[$index]->monthly_fee_2day}}" />
            <input type="hidden" name="semi_fee_day_3" id="semi_fee_day_3" value="{{$Package[$index]->monthly_fee_3day}}" />
            <input type="hidden" name="semi_fee_day_4" id="semi_fee_day_4" value="{{$Package[$index]->monthly_fee_4day}}" />
        @elseif($item->fee_Type == 'annual')
            <input type="hidden" name="annual_registration_fee" id="annual_registration_fee" value="{{$Package[$index]->registration_fee}}"/>
            <input type="hidden" name="annual_fee_day_1" id="annual_fee_day_1" value="{{$Package[$index]->monthly_fee_1day}}" />
            <input type="hidden" name="annual_fee_day_2" id="annual_fee_day_2" value="{{$Package[$index]->monthly_fee_2day}}" />
            <input type="hidden" name="annual_fee_day_3" id="annual_fee_day_3" value="{{$Package[$index]->monthly_fee_3day}}" />
            <input type="hidden" name="annual_fee_day_4" id="annual_fee_day_4" value="{{$Package[$index]->monthly_fee_4day}}" />
        @endif
    @endforeach

    <div class="row">
        <div class="col-md-12">
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
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center mt-0 mb-0">COMPLETE REGISTRATION</h3>
        </div>

        <div class="col-md-12 mt-3">
            <div class="row m-auto">
                <div class="col-lg-offset-2 col-lg-8">
                    <div class="row bs-wizard">
                        <div class="col-xs-12 bs-wizard-step step1 complete">
                            <div class="text-center bs-wizard-stepnum">&nbsp;{{--Step 1--}}</div>
                            <div class="progress">
                                <div class="progress-bar"></div>
                            </div>
                            <a href="#" class="bs-wizard-dot"></a>
                            <div class="bs-wizard-info text-center">Package Selection</div>
                        </div>

                        <div class="col-xs-12 bs-wizard-step step2 disabled">
                            <div class="text-center bs-wizard-stepnum">&nbsp;{{--Step 1--}}</div>
                            <div class="progress">
                                <div class="progress-bar"></div>
                            </div>
                            <a href="#" class="bs-wizard-dot"></a>
                            <div class="bs-wizard-info text-center">Checkout</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Get Package --}}
    <div class="row my-5">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div id="leadPackage">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-md-4 mb-3">
                        <div class="panel panel-default mb-0">
                            <div class="panel-body pt-0">

                                <h2 class="text-center package-heading-1">
                                    Standard
                                </h2>

                                <div class="mt-3" style="display: flex; align-items: center;">
                                    <h2 class="m-auto text-center package-price package-color-1">
                                                <span class="m-auto">
                                                    <span id="monthlyPackagePrice"></span>
                                                    <sub style="font-size: small; margin-left: -2px;">/mo</sub>
                                                </span>
                                    </h2>
                                </div>

                                <h3 class="mt-3 text-center package-heading-1"
                                    style="font-size: medium;">
                                    Monthly
                                </h3>

                                <div class="mt-4" style="display: flex; align-items: center;">
                                    <select class="form-control" name="monthlyPackageDaySelect"
                                            id="monthlyPackageDaySelect"
                                            onchange="AdjustPackagePrice('monthly', 'monthlyPackagePrice', this.value);"></select>
                                </div>
                                <div class="mt-4" style="display: none/*flex*/; align-items: center;">
                                    <div class="m-auto" style="width: 100%;">
                                        <table border="0">
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    Registration Fee: <span
                                                            id="monthlyPackageRegistration"></span>
                                                </td>
                                            </tr>
                                            <tr id="monthly1DayFeeRow" style="display: none;">
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    1 Days Fee: <span id="monthlyPackage1DayFee"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    2 Days Fee: <span id="monthlyPackage2DayFee"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    3 Days Fee: <span id="monthlyPackage3DayFee"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    4 Days Fee: <span id="monthlyPackage4DayFee"></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-4" style="display: flex; align-items: center;">
                                    <button type="button" class="btn package-color-1 m-auto"
                                            id="package1Btn" style="width: 60%;"
                                            onclick="ShowCheckoutPage(this, 'monthly', 'monthlyPackagePrice');">
                                        Select
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="panel panel-default mb-0" style="height: 350px;">
                            <div class="panel-body pt-0">
                                <span class="badge badge-danger package-flag">Save 60%</span>
                                <h2 class="text-center package-heading-2">
                                    Best Value
                                </h2>

                                <div class="mt-3" style="display: flex; align-items: center;">
                                    <h2 class="m-auto text-center package-price package-color-2">
                                                <span class="m-auto">
                                                    <span id="annualPackagePrice"></span>
                                                    <sub style="font-size: small; margin-left: -2px;">/mo</sub>
                                                </span>
                                    </h2>
                                </div>

                                <h3 class="mt-3 text-center package-heading-2"
                                    style="font-size: medium;">
                                    Annual
                                </h3>

                                <div class="mt-4" style="display: flex; align-items: center;">
                                    <select class="form-control" name="annualPackageDaySelect"
                                            id="annualPackageDaySelect"
                                            onchange="AdjustPackagePrice('annual', 'annualPackagePrice', this.value);"></select>
                                </div>
                                <div class="mt-4" style="display: none/*flex*/; align-items: center;">
                                    <div class="m-auto" style="width: 100%;">
                                        <table border="0">
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    Registration Fee: <span
                                                            id="annualPackageRegistration"></span>
                                                </td>
                                            </tr>
                                            <tr id="annual1DayFeeRow" style="display: none;">
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    1 Days Fee: <span id="annualPackage1DayFee"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    2 Days Fee: <span id="annualPackage2DayFee"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    3 Days Fee: <span id="annualPackage3DayFee"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    4 Days Fee: <span id="annualPackage4DayFee"></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-4" style="display: flex; align-items: center;">
                                    <button type="button" class="btn package-color-2 m-auto"
                                            id="package3Btn" style="width: 60%;"
                                            onclick="ShowCheckoutPage(this, 'annual', 'annualPackagePrice');">
                                        Select
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="panel panel-default mb-0">
                            <div class="panel-body pt-0">
                                <span class="badge badge-danger package-flag">Save 40%</span>
                                <h2 class="text-center package-heading-3">
                                    Most Popular
                                </h2>

                                <div class="mt-3" style="display: flex; align-items: center;">
                                    <h2 class="m-auto text-center package-price package-color-3">
                                                <span class="m-auto">
                                                    <span id="semiPackagePrice"></span>
                                                    <sub style="font-size: small; margin-left: -2px;">/mo</sub>
                                                </span>
                                    </h2>
                                </div>

                                <h3 class="mt-3 text-center package-heading-3"
                                    style="font-size: medium;">
                                    Semi Annual
                                </h3>

                                <div class="mt-4" style="display: flex; align-items: center;">
                                    <select class="form-control" name="semiPackageDaySelect"
                                            id="semiPackageDaySelect"
                                            onchange="AdjustPackagePrice('semi', 'semiPackagePrice', this.value);"></select>
                                </div>
                                <div class="mt-4" style="display: none/*flex*/; align-items: center;">
                                    <div class="m-auto" style="width: 100%;">
                                        <table border="0">
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    Registration Fee: <span
                                                            id="semiPackageRegistration"></span>
                                                </td>
                                            </tr>
                                            <tr id="semi1DayFeeRow" style="display: none;">
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    1 Days Fee: <span id="semiPackage1DayFee"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    2 Days Fee: <span id="semiPackage2DayFee"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    3 Days Fee: <span id="semiPackage3DayFee"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" class="pt-2 pb-2">
                                                    <i class="fas fa-dot-circle"></i>
                                                </td>
                                                <td width="80%" class="pt-2 pb-2">
                                                    4 Days Fee: <span id="semiPackage4DayFee"></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-4" style="display: flex; align-items: center;">
                                    <button type="button" class="btn package-color-3 m-auto"
                                            id="package2Btn" style="width: 60%;"
                                            onclick="ShowCheckoutPage(this, 'semi', 'semiPackagePrice');">
                                        Select
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Get Package --}}

    {{-- Get Checkout --}}
    <div class="row my-5">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div id="leadCheckout" style="display: none;">
                <div class="row">
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

                                    <div class="col-md-12 mt-3">
                                        Billing Address
                                        <label class="form-check-label color-custom-primary primaryColor float-right"
                                               for="billingAddressCheckbox">
                                            <input class="form-check-input" type="checkbox"
                                                   name="billingAddressCheckbox"
                                                   id="billingAddressCheckbox">
                                            Same as Physical Address
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="billingAddressSection">
                        <div class="panel panel-default">
                            <h2 class="panel-title">
                                Billing Address
                            </h2>
                            <div class="panel-body">
                                <div class="custom-row">
                                    {{--<div class="custom-col-3 mt-2">
                                        <label for="billingPhone"><strong>Phone Number</strong></label>
                                        <input type="number" name="billingPhone" id="billingPhone" class="form-control"
                                               placeholder="Enter Your Phone Number" maxlength="20" autocomplete="off" />
                                    </div>--}}
                                    <input type="hidden" name="billingPhone" id="billingPhone"
                                           value="0"/>

                                    <div class="custom-col-3 mt-2">
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="billingState">State</label>
                                            <select name="billingState" id="billingState"
                                                    class="form-control select2"
                                                    onchange="LoadBillingCities(this.value);">
                                                <option value="">Select State</option>
                                                @foreach($States as $state)
                                                    <option value="{{$state->name}}">{{$state->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="custom-col-3 mt-2" id="billingCitySection"
                                         style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label" for="billingCity">City</label>
                                            <select name="billingCity" id="billingCity"
                                                    class="form-control">
                                                <option value="" selected>Select City</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="custom-col-3 mt-2">
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="billingStreet">Street</label>
                                            <input class="form-control" name="billingStreet"
                                                   id="billingStreet"
                                                   placeholder="Street" autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="custom-col-3 mt-2">
                                        <div class="form-group">
                                            <label class="control-label" for="billingZipCode">Zip
                                                code</label>
                                            <input type="number" name="billingZipCode"
                                                   id="billingZipCode"
                                                   class="form-control"
                                                   data-validate="minlength[5]"
                                                   onkeypress="limitKeypress(event,this.value,5)"
                                                   onblur="limitBillingZipCodeCheck();"
                                                   autocomplete="off"
                                                   placeholder="Zip Code"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--Summary Card--}}
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <h2 class="panel-title">
                                Purchase Information
                            </h2>
                            <div class="panel-body">
                                <table border="0" style="width: 100%;">
                                    <tr>
                                        <td width="25%" class="pt-2 pb-2">
                                            Plan:
                                        </td>
                                        <td width="75%" class="pt-2 pb-2 text-black"
                                            id="planNameSummary"></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" class="pt-2 pb-2">
                                            Period:
                                        </td>
                                        <td width="75%" class="pt-2 pb-2 text-black"
                                            id="planDurationSummary"></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" class="pt-2 pb-2">
                                            Price:
                                        </td>
                                        <td width="75%" class="pt-2 pb-2 text-black"
                                            id="planPriceSummary"></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" class="pt-2 pb-2">
                                            Registration Fee:
                                        </td>
                                        <td width="75%" class="pt-2 pb-2 text-black"
                                            id="planRegistrationSummary"></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" class="pt-2 pb-2">
                                            Discount:
                                        </td>
                                        <td width="75%" class="pt-2 pb-2 text-danger"
                                            id="planDiscountSummary">-$0</td>
                                    </tr>
                                    <tr>
                                        <td width="25%" class="pt-2 pb-2">
                                            Tax:
                                        </td>
                                        <td width="75%" class="pt-2 pb-2 text-black"
                                            id="planTaxSummary"></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" class="pt-2 pb-2">
                                            Processing Fee:
                                        </td>
                                        <td width="75%" class="pt-2 pb-2 text-black"
                                            id="planProcessingFeeSummary"></td>
                                    </tr>
                                    <tr>
                                        <td width="25%" class="pt-2 pb-2">
                                            <input type="text" name="couponCode" id="couponCode" class="form-control" placeholder="Coupon Code" />
                                        </td>
                                        <td width="75%" class="pt-2 pb-2">
                                            <button type="button" class="btn btn-primary mb-0" onclick="ApplyCode(this);">Apply</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <div class="alert alert-success">
                            <strong>Total:</strong>
                            <strong class="float-right" id="planTotalSummary"></strong>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="form-check-label color-custom-primary primaryColor"
                               for="termsConditions">
                            <input class="form-check-input" type="checkbox" name="termsConditions"
                                   id="termsConditions">
                            I confirm that I have read and agree to the My Soccer Academy Terms of
                            Service and Privacy Policy and I understand that the My Soccer Academy
                            services are provided on a subscription basis and are set to auto-renew 1st
                            day of every month.
                        </label>
                    </div>

                    <div class="col-md-12 mb-5">
                        <label class="form-check-label color-custom-primary primaryColor"
                               for="subscribe">
                            <input class="form-check-input" type="checkbox" name="subscribe"
                                   id="subscribe">
                            I would like to receive information about service updates and new features,
                            special offers, and educational content by email.
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="button" class="btn btn-primary float-left" value="Back"
                               onclick="ShowGetRegistered();"/>
                        <input type="button" class="btn btn-primary float-right" id="confirmBtn"
                               onclick="SubmitForm(this);" value="Pay Now" disabled="disabled"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Get Checkout --}}

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        let nf = new Intl.NumberFormat('en', { //en-AE
            /*style: 'currency',
            currency: 'AED',*/
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        $(document).ready(function () {
            let Alert = $("#message-alert");
            if (Alert.length > 0) {
                setTimeout(function () {
                    Alert.slideUp();
                }, 10000);
            }

            $("#billingAddressCheckbox").on('change', function () {
                let Checked = $(this).prop('checked');
                if(Checked) {
                    $("#billingAddressSection").hide();
                } else {
                    $("#billingAddressSection").show();
                }
            });

            $("#termsConditions").on('change', function () {
                let Checked = $(this).prop('checked');
                if(Checked) {
                    $("#confirmBtn").attr('disabled', false);
                } else {
                    $("#confirmBtn").attr('disabled', true);
                }
            });

            /*Load Package Details*/
            GetPackageDetails();
        });

        function LoadStateCountyCity() {
            let state = '';
            if ($("#state").length) {
                state = $("#state option:selected").val();
            }
            if ($("#citySection").length) {
                $("#citySection").show();
            }
            LoadCities(state);
        }

        function limitKeypress(event, value, maxLength) {
            if (value !== undefined && value.toString().length >= maxLength) {
                event.preventDefault();
            }
        }

        function limitZipCodeCheck() {
            let value = $('#zipcode').val();
            if (value.toString().length < 5) {
                $('#zipcode').focus();
            }
        }

        function limitBillingZipCodeCheck() {
            let value = $('#billingZipCode').val();
            if (value.toString().length < 5) {
                $('#billingZipCode').focus();
            }
        }

        function LoadCities(state) {
            $.ajax({
                type: "post",
                url: "{{route('common.load.cities')}}",
                data: {State: state}
            }).done(function (data) {
                data = JSON.parse(data);
                if ($("#city").length > 0) {
                    $("#city").html('').html(data).select2();
                }
            });
        }

        function LoadBillingCities(state) {
            $("#billingCitySection").show();
            $.ajax({
                type: "post",
                url: "{{route('common.load.cities')}}",
                data: {State: state}
            }).done(function (data) {
                data = JSON.parse(data);
                $("#billingCity").html('').html(data).select2();
            });
        }

        function GetPackageDetails() {
            /*Check for Toddler Category*/
            let CategoryId = $("#categoryId").val();
            let CategoryTitle = $("#categoryTitle").val();
            let PackageId = $("#packageId").val();

            if(CategoryId === 3 || CategoryTitle === 'Toddlers') {
                $("#monthly1DayFeeRow").show();
                $("#semi1DayFeeRow").show();
                $("#annual1DayFeeRow").show();
                let Options = '<option value="1">1 Days Class</option><option value="2" selected>2 Days Class</option><option value="3">3 Days Class</option><option value="4">4 Days Class</option>';
                $("#monthlyPackageDaySelect").html(Options);
                $("#semiPackageDaySelect").html(Options);
                $("#annualPackageDaySelect").html(Options);
            } else {
                $("#monthly1DayFeeRow").hide();
                $("#semi1DayFeeRow").hide();
                $("#annual1DayFeeRow").hide();
                let Options = '<option value="2" selected>2 Days Class</option><option value="3">3 Days Class</option><option value="4">4 Days Class</option>';
                $("#monthlyPackageDaySelect").html(Options);
                $("#semiPackageDaySelect").html(Options);
                $("#annualPackageDaySelect").html(Options);
            }
            SetPackagePrices();
        }

        function SetPackagePrices() {
            let MonthlyRegistration = $("#monthly_registration_fee").val();
            /*$("#monthlyPackagePrice").text('$' + (parseFloat(MonthlyRegistration) + parseFloat($("#monthly_fee_day_2").val())) );*/
            $("#monthlyPackagePrice").text('$' + $("#monthly_fee_day_2").val() );
            $("#monthlyPackageRegistration").text('$' + MonthlyRegistration);
            $("#monthlyPackage1DayFee").text('$' + $("#monthly_fee_day_1").val());
            $("#monthlyPackage2DayFee").text('$' + $("#monthly_fee_day_2").val());
            $("#monthlyPackage3DayFee").text('$' + $("#monthly_fee_day_3").val());
            $("#monthlyPackage4DayFee").text('$' + $("#monthly_fee_day_4").val());

            let SemiRegistration = $("#semi_registration_fee").val();
            /*$("#semiPackagePrice").text('$' + (parseFloat(SemiRegistration) + parseFloat($("#semi_fee_day_2").val())) );*/
            $("#semiPackagePrice").text('$' + $("#semi_fee_day_2").val() );
            $("#semiPackageRegistration").text('$' + SemiRegistration);
            $("#semiPackage1DayFee").text('$' + $("#semi_fee_day_1").val());
            $("#semiPackage2DayFee").text('$' + $("#semi_fee_day_2").val());
            $("#semiPackage3DayFee").text('$' + $("#semi_fee_day_3").val());
            $("#semiPackage4DayFee").text('$' + $("#semi_fee_day_4").val());

            let AnnualRegistration = $("#annual_registration_fee").val();
            /*$("#annualPackagePrice").text('$' + (parseFloat(AnnualRegistration) + parseFloat($("#annual_fee_day_2").val())) );*/
            $("#annualPackagePrice").text('$' + $("#annual_fee_day_2").val());
            $("#annualPackageRegistration").text('$' + AnnualRegistration);
            $("#annualPackage1DayFee").text('$' + $("#annual_fee_day_1").val());
            $("#annualPackage2DayFee").text('$' + $("#annual_fee_day_2").val());
            $("#annualPackage3DayFee").text('$' + $("#annual_fee_day_3").val());
            $("#annualPackage4DayFee").text('$' + $("#annual_fee_day_4").val());
        }

        function AdjustPackagePrice(Type, PackagePriceId, Value) {
            $("#selectedDays").val(Value);
            if(Type === 'monthly') {
                let MonthlyRegistration = $("#monthly_registration_fee").val();
                /*$("#" + PackagePriceId).text('$' + (parseFloat(MonthlyRegistration) + parseFloat($("#monthly_fee_day_" + Value).val())) );*/
                $("#" + PackagePriceId).text('$' + $("#monthly_fee_day_" + Value).val() );
            } else if(Type === 'semi') {
                let SemiRegistration = $("#semi_registration_fee").val();
                /*$("#" + PackagePriceId).text('$' + (parseFloat(SemiRegistration) + parseFloat($("#semi_fee_day_" + Value).val())) );*/
                $("#" + PackagePriceId).text('$' + $("#semi_fee_day_" + Value).val() );
            } else if(Type === 'annual') {
                let AnnualRegistration = $("#annual_registration_fee").val();
                /*$("#" + PackagePriceId).text('$' + (parseFloat(AnnualRegistration) + parseFloat($("#annual_fee_day_" + Value).val())) );*/
                $("#" + PackagePriceId).text('$' + $("#annual_fee_day_" + Value).val() );
            }
        }

        function ShowGetRegistered() {
            $("#leadPackage").show();
            $("#leadCheckout").hide();
            $(".step1").removeClass("disabled").addClass("complete");
            $(".step2").removeClass("complete").addClass("disabled");
            $(window).scrollTop(0);
        }

        function ShowCheckoutPage(e, PackageType, PackagePriceId) {
            $(e).attr('disabled', true).text('Processing...');
            $(".loading").show();
            /*Disable Remaining Buttons*/
            $("#package1Btn").attr('disabled', true);
            $("#package2Btn").attr('disabled', true);
            $("#package3Btn").attr('disabled', true);
            /*Setup Stripe UI*/
            let PackageId = $("#packageId").val();
            let CategoryId = $("#categoryId").val();
            let SelectedDays = $("#selectedDays").val();
            /*let Price = $("#" + PackagePriceId).text();*/
            let Price = '$' + CalculatePackagePrice(PackageType, SelectedDays);
            $("#selectedPackageType").val(PackageType);
            $("#selectedPackagePrice").val(Price);

            $.ajax({
                type: "post",
                url: "{{route('stripe.setup')}}",
                data: { Price : Price, FirstName : $("#parentFirstName").val(), LastName : $("#parentLastName").val(), Phone : $("#parentPhone").val() }
            }).done(function (data) {
                $(e).attr('disabled', false).text('Select');
                $(".loading").hide();
                /*Enable Remaining Buttons*/
                $("#package1Btn").attr('disabled', false);
                $("#package2Btn").attr('disabled', false);
                $("#package3Btn").attr('disabled', false);
                if(data.status) {
                    $("#leadPackage").hide();
                    $("#leadCheckout").show();
                    $(".step2").removeClass("disabled").addClass("complete");
                    $(window).scrollTop(0);
                    SetSummaryCard(PackageType, SelectedDays);
                    /*Stripe*/
                    $("#PaymentIntentId").val(data.payment_intent);
                    $("#ClientSecret").val(data.client_secret);
                    $("#StripeCustomerId").val(data.customer_id);
                    stripe = Stripe('{{env('STRIPE_PUBLIC_KEY')}}');
                    options = {
                        clientSecret: data.client_secret,
                        // Fully customizable with appearance API.
                        appearance: {
                            theme: 'stripe',
                            variables: {
                                colorPrimary: '#0570de',
                                colorBackground: '#fff',
                                colorText: '#555',
                                colorDanger: '#df1b41',
                                // See all possible variables below
                            }
                        },
                    };
                    // Set up Stripe.js and Elements to use in checkout form, passing the client secret obtained in step 2
                    elements = stripe.elements(options);
                    // Create and mount the Payment Element
                    paymentElement = elements.create('payment', {
                        fields: {
                            billingDetails: {
                                address: {
                                    country: 'never',
                                }
                            }
                        }
                    });
                    paymentElement.mount('#payment-element');
                } else {
                    alert(data.message);
                }
            });
        }

        /**
         * @return {string}
         */
        function CalculatePackagePrice(Type, SelectedDays) {
            let SubPrice = 0;
            let Registration = 0;
            let TotalPrice = 0;
            let Tax = parseFloat($("#TaxRate").val());
            let ProcessingFee = parseFloat($("#ProcessingFee").val());

            if(Type === 'monthly') {
                SubPrice = $("#monthly_fee_day_" + SelectedDays).val();
                Registration = $("#monthly_registration_fee").val();
                $("#selectedPackageSubPrice").val(SubPrice);
                TotalPrice = parseFloat(SubPrice) + parseFloat(Registration);
            } else if(Type === 'semi') {
                SubPrice = $("#semi_fee_day_" + SelectedDays).val();
                Registration = $("#semi_registration_fee").val();
                $("#selectedPackageSubPrice").val(parseFloat(SubPrice) * 6);
                TotalPrice = (parseFloat(SubPrice) * 6) + parseFloat(Registration);
            } else if(Type === 'annual') {
                SubPrice = $("#annual_fee_day_" + SelectedDays).val();
                Registration = $("#annual_registration_fee").val();
                $("#selectedPackageSubPrice").val(parseFloat(SubPrice) * 12);
                TotalPrice = (parseFloat(SubPrice) * 12) + parseFloat(Registration);
            }
            TotalPrice += (TotalPrice * Tax) / 100;
            TotalPrice += (TotalPrice * ProcessingFee) / 100;
            return TotalPrice.toFixed(2);
        }

        function SetSummaryCard(Type, SelectedDays) {
            let SubPrice = 0;
            let Registration = 0;
            let TotalPrice = 0;
            let Tax = parseFloat($("#TaxRate").val());
            let ProcessingFee = parseFloat($("#ProcessingFee").val());

            if(Type === 'monthly') {
                $("#planNameSummary").text('Standard');
                $("#planDurationSummary").text('1 Month');
                SubPrice = $("#monthly_fee_day_" + SelectedDays).val();
                Registration = $("#monthly_registration_fee").val();
                $("#planPriceSummary").text('$' + SubPrice + '/mo');
                $("#planRegistrationSummary").text('$' + Registration);
                TotalPrice = parseFloat(SubPrice) + parseFloat(Registration);
            } else if(Type === 'semi') {
                $("#planNameSummary").text('Most Popular');
                $("#planDurationSummary").text('6 Months');
                SubPrice = $("#semi_fee_day_" + SelectedDays).val();
                Registration = $("#semi_registration_fee").val();
                $("#planPriceSummary").text('$' + SubPrice + '/mo');
                $("#planRegistrationSummary").text('$' + Registration);
                TotalPrice = (parseFloat(SubPrice) * 6) + parseFloat(Registration);
            } else if(Type === 'annual') {
                $("#planNameSummary").text('Best Value');
                $("#planDurationSummary").text('12 Months');
                SubPrice = $("#annual_fee_day_" + SelectedDays).val();
                Registration = $("#annual_registration_fee").val();
                $("#planPriceSummary").text('$' + SubPrice + '/mo');
                $("#planRegistrationSummary").text('$' + Registration);
                TotalPrice = (parseFloat(SubPrice) * 12) + parseFloat(Registration);
            }
            let TaxAmount = (TotalPrice * Tax) / 100;
            TotalPrice += TaxAmount;
            let ProcessingFeeAmount = (TotalPrice * ProcessingFee) / 100;
            TotalPrice += ProcessingFeeAmount;

            $("#couponCode").val('');
            $("#planDiscountSummary").text('-$0');
            $("#planTaxSummary").text('$' + nf.format(TaxAmount));
            $("#planProcessingFeeSummary").text('$' + nf.format(ProcessingFeeAmount));
            /*$("#planTaxSummary").text(Tax + '%');
            $("#planProcessingFeeSummary").text(ProcessingFee + '%');*/
            $("#planTotalSummary").text(nf.format(TotalPrice) + ' USD');
        }

        function ApplyCode(e) {
            let CouponCode = $("#couponCode").val();
            if(CouponCode !== '') {
                $(e).attr('disabled', true).text('Processing...');
                $(".loading").show();
                let PaymentIntentId = $("#PaymentIntentId").val();
                let ClientSecret = $("#ClientSecret").val();
                let StripeCustomerId = $("#StripeCustomerId").val();
                let LeadId = $("#lead_id").val();
                let PackageId = $("#packageId").val();
                let CategoryId = $("#categoryId").val();
                let SelectedDays = $("#selectedDays").val();
                let PackageType = $("#selectedPackageType").val();
                let SubPrice = $("#selectedPackageSubPrice").val();
                let Tax = $("#TaxRate").val();
                let ProcessingFee = $("#ProcessingFee").val();
                let Price = $("#selectedPackagePrice").val();
                let Subscribe = $("#subscribe").prop('checked');
                let RegistrationFee = 0;
                if(PackageType === 'monthly') {
                    RegistrationFee = $("#monthly_registration_fee").val();
                } else if(PackageType === 'semi') {
                    RegistrationFee = $("#semi_registration_fee").val();
                } else if(PackageType === 'annual') {
                    RegistrationFee = $("#annual_registration_fee").val();
                }

                $.ajax({
                    type: "post",
                    url: "{{ route('stripe.order.coupon.apply') }}",
                    data: {
                        PaymentIntentId : PaymentIntentId,
                        ClientSecret : ClientSecret,
                        StripeCustomerId : StripeCustomerId,
                        LeadId : LeadId,
                        PackageId : PackageId,
                        CategoryId : CategoryId,
                        SelectedDays : SelectedDays,
                        PackageType : PackageType,
                        RegistrationFee : RegistrationFee,
                        SubPrice : SubPrice,
                        Tax : Tax,
                        ProcessingFee : ProcessingFee,
                        Price : Price,
                        CouponCode : CouponCode
                    }
                }).done(function (data) {
                    $(e).attr('disabled', false).text('Apply');
                    $(".loading").hide();
                    if(data.status) {
                        $("#planDiscountSummary").text('-$' + nf.format(data.DiscountPrice));
                        $("#planTaxSummary").text('$' + nf.format(data.NewTax));
                        $("#planProcessingFeeSummary").text('$' + nf.format(data.NewProcessingFee));
                        $("#planTotalSummary").text(nf.format(data.NewPrice) + ' USD');
                        $("#CouponCodeId").val(data.CouponCodeId);
                        $("#CouponAmount").val(data.DiscountPrice);
                        $("#selectedPackagePrice").val('$' + data.NewPrice);
                    } else {
                        alert(data.message);
                    }
                });
            }
        }

        function SubmitForm(e) {
            let Phone = $("#billingPhone");
            let State = $("#billingState option:selected").val();
            let City = $("#billingCity option:selected").val();
            let Street = $("#billingStreet");
            let ZipCode = $("#billingZipCode");
            let leadId = $("#leadId").val();

            let Count = 0;

            /*Stripe Billing Address Work*/
            let Checked = $("#billingAddressCheckbox").prop('checked');
            let BState = '';
            let BCity = '';
            let BZip = '';
            let BStreet = '';
            if(Checked) {
                BState = $("#parentState").val();
                BCity = $("#parentCity").val();
                BZip = $("#parentZipCode").val();
                BStreet = $("#parentStreet").val();
            } else {
                BState = State;
                BCity = City;
                BZip = ZipCode.val();
                BStreet = Street.val();

                if(Phone.val() === '') {
                    Phone.focus();
                    Count++;
                    return;
                }

                if(State === '') {
                    $("#billingState").focus();
                    Count++;
                    return;
                }

                if(City === '') {
                    $("#billingCity").focus();
                    Count++;
                    return;
                }

                if(Street.val() === '') {
                    Street.focus();
                    Count++;
                    return;
                }

                if(ZipCode.val() === '') {
                    ZipCode.focus();
                    Count++;
                    return;
                }
            }

            if(Count !== 0) {
                return;
            }

            $(e).attr('disabled', true).val('Processing...');
            $(".loading").show();

            /*Send Data using Ajax*/
            let PaymentIntentId = $("#PaymentIntentId").val();
            let ClientSecret = $("#ClientSecret").val();
            let StripeCustomerId = $("#StripeCustomerId").val();
            let PackageId = $("#packageId").val();
            let CategoryId = $("#categoryId").val();
            let SelectedDays = $("#selectedDays").val();
            let PackageType = $("#selectedPackageType").val();
            let SubPrice = $("#selectedPackageSubPrice").val();
            let Tax = $("#TaxRate").val();
            let ProcessingFee = $("#ProcessingFee").val();
            let Price = $("#selectedPackagePrice").val();
            let Subscribe = $("#subscribe").prop('checked');
            let CouponCode = $("#CouponCodeId").val();
            let CouponAmount = $("#CouponAmount").val();

            let RegistrationFee = 0;
            if(PackageType === 'monthly') {
                RegistrationFee = $("#monthly_registration_fee").val();
            } else if(PackageType === 'semi') {
                RegistrationFee = $("#semi_registration_fee").val();
            } else if(PackageType === 'annual') {
                RegistrationFee = $("#annual_registration_fee").val();
            }

            $.ajax({
                type: "post",
                url: "{{ route('dashboard.stripe.order.create') }}",
                data: {
                    PaymentIntentId : PaymentIntentId,
                    ClientSecret : ClientSecret,
                    StripeCustomerId : StripeCustomerId,
                    LeadId : leadId,
                    PackageId : PackageId,
                    CategoryId : CategoryId,
                    SelectedDays : SelectedDays,
                    PackageType : PackageType,
                    SubPrice : SubPrice,
                    Tax : Tax,
                    ProcessingFee : ProcessingFee,
                    Price : Price,
                    Phone: Phone.val(),
                    State: BState,
                    City: BCity,
                    Street: BStreet,
                    ZipCode: BZip,
                    Subscribe : Subscribe,
                    RegistrationFee : RegistrationFee,
                    CouponCode : CouponCode,
                    CouponAmount : CouponAmount
                }
            }).done(function (data) {
                $("#stripeErrorAlert").hide();
                stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: '{{route('dashboard.stripe.order.finish')}}',
                        payment_method_data: {
                            billing_details: {
                                name: $("#parentFirstName").val() + " " + $("#parentLastName").val(),
                                email: $("#parentEmail").val(),
                                phone: $("#parentPhone").val(),
                                address: {
                                    country: 'US',
                                    state: BState,
                                    city: BCity,
                                    postal_code: BZip,
                                    line1: ' ',
                                    line2: ' ',
                                },
                            }
                        },
                    },
                }).then(function (result) {
                    // console.log(result);
                    if (result.error) {
                        // Inform the customer that there was an error.
                        $("#stripeErrorAlert").show();
                        $("#stripeErrorAlertMessage").html(result.error.message);
                        /*$(e).attr('disabled', false).html('Confirm');*/
                        $(e).attr('disabled', false).val('Pay Now');
                        $(".loading").hide();
                    }
                });
            });
        }
    </script>
@endsection