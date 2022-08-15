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
            width: 33.33%;
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

    <div class="loading" style="display: none;">Loading</div>
    <input type="hidden" name="leadId" id="leadId" value=""/>
    <input type="hidden" name="packageId" id="packageId" value=""/>
    <input type="hidden" name="categoryId" id="categoryId" value=""/>
    <input type="hidden" name="categoryTitle" id="categoryTitle" value=""/>
    <input type="hidden" name="selectedPackageType" id="selectedPackageType" value=""/>
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

    <input type="hidden" name="parentFirstName" id="parentFirstName" value="{{$ParentUserDetails[0]->firstName}}"/>
    <input type="hidden" name="parentLastName" id="parentLastName" value="{{$ParentUserDetails[0]->lastName}}"/>
    <input type="hidden" name="parentPhone" id="parentPhone" value="{{$ParentUserDetails[0]->phone1}}"/>
    <input type="hidden" name="parentEmail" id="parentEmail"
           value="{{\Illuminate\Support\Facades\Auth::user()->email}}"/>

    <input type="hidden" name="parentState" id="parentState" value="{{$ParentUserDetails[0]->state}}"/>
    <input type="hidden" name="parentCity" id="parentCity" value="{{$ParentUserDetails[0]->city}}"/>
    <input type="hidden" name="parentStreet" id="parentStreet" value="{{$ParentUserDetails[0]->street}}"/>
    <input type="hidden" name="parentZipCode" id="parentZipCode" value="{{$ParentUserDetails[0]->zipcode}}"/>
    <input type="hidden" name="billingPhone" id="billingPhone" value="0"/>

    <input type="hidden" name="monthly_registration_fee" id="monthly_registration_fee" value=""/>
    <input type="hidden" name="monthly_fee_day_1" id="monthly_fee_day_1" value=""/>
    <input type="hidden" name="monthly_fee_day_2" id="monthly_fee_day_2" value=""/>
    <input type="hidden" name="monthly_fee_day_3" id="monthly_fee_day_3" value=""/>
    <input type="hidden" name="monthly_fee_day_4" id="monthly_fee_day_4" value=""/>

    <input type="hidden" name="semi_registration_fee" id="semi_registration_fee" value=""/>
    <input type="hidden" name="semi_fee_day_1" id="semi_fee_day_1" value=""/>
    <input type="hidden" name="semi_fee_day_2" id="semi_fee_day_2" value=""/>
    <input type="hidden" name="semi_fee_day_3" id="semi_fee_day_3" value=""/>
    <input type="hidden" name="semi_fee_day_4" id="semi_fee_day_4" value=""/>

    <input type="hidden" name="annual_registration_fee" id="annual_registration_fee" value=""/>
    <input type="hidden" name="annual_fee_day_1" id="annual_fee_day_1" value=""/>
    <input type="hidden" name="annual_fee_day_2" id="annual_fee_day_2" value=""/>
    <input type="hidden" name="annual_fee_day_3" id="annual_fee_day_3" value=""/>
    <input type="hidden" name="annual_fee_day_4" id="annual_fee_day_4" value=""/>

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
                            <div class="text-center bs-wizard-stepnum">&nbsp;</div>
                            <div class="progress">
                                <div class="progress-bar"></div>
                            </div>
                            <a href="#" class="bs-wizard-dot"></a>
                            <div class="bs-wizard-info text-center">Player's Information</div>
                        </div>

                        <div class="col-xs-12 bs-wizard-step step2 disabled">
                            <div class="text-center bs-wizard-stepnum">&nbsp;</div>
                            <div class="progress">
                                <div class="progress-bar"></div>
                            </div>
                            <a href="#" class="bs-wizard-dot"></a>
                            <div class="bs-wizard-info text-center">Package Selection</div>
                        </div>

                        <div class="col-xs-12 bs-wizard-step step3 disabled">
                            <div class="text-center bs-wizard-stepnum">&nbsp;</div>
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

    {{-- Players Information --}}
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="panel panel-default" id="leadPlayersInformation">
                <div class="panel-body">
                    <h2 class="panel-title mb-4">
                        PLAYER INFORMATION
                    </h2>

                    <div class="custom-row"
                         style="box-shadow: 0 2px 4px 0 rgb(0 0 0 / 10%), 0 1px 10px 0 rgb(0 0 0 / 10%);">
                        <div class="custom-col-3 mt-2">
                            <label for="playerFirstName"><strong>First Name</strong></label>
                            <input type="text" name="playerFirstName" id="playerFirstName"
                                   class="form-control"
                                   placeholder="First Name" autocomplete="off"/>
                            <div style="margin-top: 7px;" id="player_f_name"></div>
                        </div>
                        <div class="custom-col-3 mt-2">
                            <label for="playerLastName"><strong>Last Name</strong></label>
                            <input type="text" name="playerLastName" id="playerLastName"
                                   class="form-control"
                                   placeholder="Last Name" autocomplete="off"/>
                            <div style="margin-top: 7px;" id="player_l_name"></div>
                        </div>
                        <div class="custom-col-3 mt-2">
                            <label for="playerDOB">Date of Birth</label>
                            <input class="form-control datepicker" name="playerDOB"
                                   id="playerDOB"
                                   autocomplete="off"
                                   placeholder="MM/DD/YYYY" autocomplete="off"/>
                            <div style="margin-top: 7px;" id="player_dob"></div>
                        </div>
                        <div class="custom-col-3 mt-2">
                            <label for="playerEmail"><strong>Email</strong></label>
                            <input type="email" name="playerEmail" id="playerEmail" class="form-control"
                                   onfocusout="UniquePlayerEmailCheck();"
                                   placeholder="Email"
                                   onblur="UniquePlayerEmailCheck();"/>
                            <div class="alert alert-danger" id="playerEmailErrorAlert"
                                 style="display: none;"></div>
                        </div>
                        <div class="custom-col-3 mt-2">
                            <div class="form-group">
                                <label class="control-label" for="playerGender">Gender</label>
                                <div class="mt-2">
                                    <label class="radio-inline">
                                        <input type="radio" name="playerGender" id="male_gender"
                                               value="Male" checked>Male
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="playerGender" id="female_gender"
                                               value="Female">Female
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="custom-col-3 mt-2">
                            <div class="form-group">
                                <label class="control-label"
                                       for="playerRelationship">Relationship</label>
                                <select class="form-control" name="playerRelationship"
                                        id="playerRelationship">
                                    <option value="" selected>Select</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Father">Father</option>
                                    <option value="Legal Guardian">Legal Guardian</option>
                                </select>
                                <div style="margin-top: 5px;" id="player_relationship"></div>
                            </div>
                        </div>
                        <div class="custom-col-3 mt-2">
                            <div class="form-group">
                                <label class="control-label" for="location">Locations</label>
                                <select class="form-control select2" name="location"
                                        id="location"
                                        onchange="checkLeadLocation(this.value);">
                                    <option value="">Select</option>
                                    <option value="-1">I don't know</option>
                                    @foreach($Locations as $index => $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="custom-col-3 mt-2" id="LocationZipCodeBlock" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="locationZipcode">Zip code</label>
                                <input type="number" name="locationZipcode" id="locationZipcode"
                                       class="form-control"
                                       data-validate="minlength[5]"
                                       onkeypress="limitKeypress(event,this.value,5)"
                                       onblur="limitZipCodeCheck(this.id);"
                                       placeholder="Zip Code"/>
                            </div>
                        </div>
                    </div>

                    <div class="custom-row">
                        <div class="custom-col-12 mt-3">
                            <div class="form-group">
                                <label class="control-label" for="message">Message</label>
                                <textarea name="message" id="message" class="form-control" rows="5"
                                          cols="80"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <input type="button" class="btn btn-primary float-right"
                                   id="packageDetailsBtn" value="Next"
                                   onclick="GetPackageDetails();"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Players Information --}}

    {{-- Get Package --}}
    <div class="row my-5">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div id="leadPackage" style="display: none;">
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
                                            Same as Parent Address
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
                                            id="planDiscountSummary">-$0
                                        </td>
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
                                            <input type="text" name="couponCode" id="couponCode" class="form-control"
                                                   placeholder="Coupon Code"/>
                                        </td>
                                        <td width="75%" class="pt-2 pb-2">
                                            <button type="button" class="btn btn-primary mb-0"
                                                    onclick="ApplyCode(this);">Apply
                                            </button>
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

                    <div class="col-md-12 mb-5">
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

    @include('dashboard.billing.subscriptions.new.scripts')
@endsection
