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

        /* Step Form Progress bar start */
        .icon {
            border: 1px solid grey;
        }

        .bs-wizard {
            /*margin-top: 20px;*/
        }

        .bs-wizard {
            border-bottom: solid 1px #e0e0e0;
            padding: 0 0 10px 0;
        }

        .bs-wizard > .bs-wizard-step {
            padding: 0;
            position: relative;
            width: 20%;
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

        .StepBar {
            margin-top: -50px;
        }

        .grid-margin {
            margin-bottom: 0;
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

        /* End of step form progress bar */
    </style>
    <?php
    $MagicNumbers = \Illuminate\Support\Facades\DB::table('magic_numbers')
        ->first();
    ?>
    <div class="loading" style="display: none;">Loading</div>
    <div id="page-container">
        <div id="content-wrap">
            <form action="{{route('parent.information.update')}}"
                  method="post" enctype="multipart/form-data" id="playerRegistrationForm">
                @csrf

                <input type="hidden" name="lead_id" id="lead_id" value=""/>
                <input type="hidden" name="packageId" id="packageId" value=""/>
                <input type="hidden" name="categoryId" id="categoryId" value=""/>
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

                <div class="container-fluid pt-5">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <img src="{{ asset('public/assets/images/Logo.jpg')}}" alt="logo-small"
                                 style="width: 125px;" class="img-fluid mb-3">
                        </div>

                        <div class="col-md-12">
                            <h3 class="text-center mt-4 mb-0">REGISTRATION</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-2 col-md-8">
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
                            @elseif(\Illuminate\Support\Facades\Session::has('payment-success'))
                                <div class="alert alert-success" id="message-alert">
                                    Thank you for your booking with us. Your information has been submitted and payment
                                    have been processed.
                                </div>
                            @elseif(\Illuminate\Support\Facades\Session::has('payment-error'))
                                <div class="alert alert-danger" id="message-alert">
                                    An unhandled error occurred while processing your payment. Please try later.
                                </div>
                            @endif
                                <div class="alert alert-danger" id="error-message-alert" style="display: none;"></div>
                        </div>
                    </div>

                    {{--Step Bar--}}
                    <section class="contact-area mb-3">
                        <div class="container">
                            <div class="row" style="margin:auto;">
                                <div class="col-lg-offset-2 col-lg-8">
                                    <div class="row bs-wizard" style="border-bottom:0;">
                                        <div class="col-xs-12 bs-wizard-step step1 complete"><!-- complete -->
                                            <div class="text-center bs-wizard-stepnum">&nbsp;{{--Step 1--}}</div>
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                            <a href="#" class="bs-wizard-dot"></a>
                                            <div class="bs-wizard-info text-center">Parent's Information</div>
                                        </div>

                                        <div class="col-xs-12 bs-wizard-step step2 disabled"><!-- disabled -->
                                            <div class="text-center bs-wizard-stepnum">&nbsp;{{--Step 2--}}</div>
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                            <a href="#" class="bs-wizard-dot"></a>
                                            <div class="bs-wizard-info text-center">Player's Information</div>
                                        </div>

                                        <div class="col-xs-12 bs-wizard-step step3 disabled"><!-- disabled -->
                                            <div class="text-center bs-wizard-stepnum">&nbsp;{{--Step 3--}}</div>
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                            <a href="#" class="bs-wizard-dot"></a>
                                            <div class="bs-wizard-info text-center">Get Registered</div>
                                        </div>

                                        <div class="col-xs-12 bs-wizard-step step4 disabled"><!-- disabled -->
                                            <div class="text-center bs-wizard-stepnum">&nbsp;{{--Step 4--}}</div>
                                            <div class="progress">
                                                <div class="progress-bar"></div>
                                            </div>
                                            <a href="#" class="bs-wizard-dot"></a>
                                            <div class="bs-wizard-info text-center">Package Selection</div>
                                        </div>

                                        <div class="col-xs-12 bs-wizard-step step5 disabled"><!-- disabled -->
                                            <div class="text-center bs-wizard-stepnum">&nbsp;{{--Step 5--}}</div>
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
                    </section>
                    {{--Step Bar--}}

                    {{-- Parents Information --}}
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <div class="panel panel-default" id="leadParentsInformation">
                                <div class="panel-body">
                                    <h2 class="panel-title mb-4">
                                        PARENTS INFORMATION
                                    </h2>
                                    <div class="custom-row">
                                        <div class="custom-col-3 mt-2">
                                            <label for="parentFirstName"><strong>First Name</strong></label>
                                            <input type="text" name="parentFirstName" id="parentFirstName"
                                                   class="form-control"
                                                   placeholder="First Name" autocomplete="off"
                                                   onblur="AutoSaveLead();"/>
                                            <div style="margin-top: 7px;" id="parent_f_name"></div>
                                        </div>
                                        <div class="custom-col-3 mt-2">
                                            <label for="parentLastName"><strong>Last Name</strong></label>
                                            <input type="text" name="parentLastName" id="parentLastName"
                                                   class="form-control"
                                                   placeholder="Last Name" autocomplete="off" onblur="AutoSaveLead();"/>
                                            <div style="margin-top: 7px;" id="parent_l_name"></div>
                                        </div>
                                        <div class="custom-col-3 mt-2">
                                            <label for="parentPhone" class="w-100"><strong>Phone Number 1</strong><i
                                                        class="fa fa-plus-circle float-right" style="cursor: pointer;"
                                                        onclick="ShowParentPhone2Field();"></i></label>
                                            <input type="text" name="parentPhone" id="parentPhone" class="form-control"
                                                   placeholder="Numbers Only" autocomplete="off"
                                                   onkeydown="checkMobileFormat(event, this);"
                                                   onblur="AutoSaveLead();"/>
                                            <div style="margin-top: 7px;" id="parent_phone1"></div>
                                        </div>
                                        <div class="custom-col-3 mt-2" id="ParentPhoneNumber2" style="display: none;">
                                            <label for="parentPhone2" class="w-100">Phone Number 2<i
                                                        class="fa fa-trash float-right" style="cursor: pointer;"
                                                        onclick="HideParentPhone2Field();"></i></label>
                                            <input type="text" name="parentPhone2" id="parentPhone2"
                                                   class="form-control" placeholder="Numbers Only"
                                                   autocomplete="off"
                                                   onkeydown="checkMobileFormat(event, this);"
                                                   onblur="AutoSaveLead();"/>
                                        </div>
                                        <div class="custom-col-3 mt-2">
                                            <label for="parentEmail"><strong>Email</strong></label>
                                            <input type="email" name="parentEmail" id="parentEmail" class="form-control"
                                                   placeholder="Email" onblur="AutoSaveLead(); UniqueEmailCheck();"
                                                   onfocusout="UniqueEmailCheck();"/>
                                            <div style="margin-top: 7px;" id="parent_email"></div>
                                        </div>
                                        <div class="custom-col-3 mt-2">
                                            <label for="parentDOB">Date of Birth</label>
                                            <input class="form-control datepicker" name="parentDOB"
                                                   id="parentDOB"
                                                   onblur="AutoSaveLead();"
                                                   placeholder="MM/DD/YYYY" autocomplete="off"/>
                                            <div style="margin-top: 7px; display: none;" id="parent_dob"></div>
                                        </div>
                                        <div class="custom-col-3 mt-2">
                                            <div class="form-group">
                                                <label class="control-label" for="state">State</label>
                                                <select name="state" id="state" class="form-control select2"
                                                        onchange="LoadStateCountyCity();AutoSaveLead();">
                                                    <option value="">Select State</option>
                                                    @foreach($States as $state)
                                                        <option value="{{$state->name}}">{{$state->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div style="margin-top: 7px;" id="parent_state"></div>
                                            </div>
                                        </div>
                                        <div class="custom-col-3 mt-2" id="citySection" style="display: none;">
                                            <div class="form-group">
                                                <label class="control-label" for="city">City</label>
                                                <select name="city" id="city" class="form-control"
                                                        onchange="AutoSaveLead();">
                                                    <option value="" selected>Select City</option>
                                                </select>
                                                <div style="margin-top: 7px;" id="parent_city"></div>
                                            </div>
                                        </div>
                                        <div class="custom-col-3 mt-2">
                                            <div class="form-group">
                                                <label class="control-label" for="street">Street</label>
                                                <input class="form-control" name="street" id="street"
                                                       onblur="AutoSaveLead();"
                                                       placeholder="Street" autocomplete="off"
                                                       onblur="AutoSaveLead();"/>
                                                <div style="margin-top: 7px;" id="parent_street"></div>
                                            </div>
                                        </div>
                                        <div class="custom-col-3 mt-2">
                                            <div class="form-group">
                                                <label class="control-label" for="zipcode">Zip code</label>
                                                <input type="number" name="zipcode" id="zipcode"
                                                       class="form-control"
                                                       data-validate="minlength[5]"
                                                       onkeypress="limitKeypress(event,this.value,5)"
                                                       onblur="limitZipCodeCheck();AutoSaveLead();"
                                                       autocomplete="off"
                                                       placeholder="Zip Code" onblur="AutoSaveLead();"/>
                                                <div style="margin-top: 7px;" id="parent_zipcode"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <input type="button" class="btn btn-primary float-right"
                                                   id="playerInfoNextBtn" value="Next"
                                                   onclick="AutoSaveLead();ShowPlayerInformation();"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Parents Information --}}

                    {{-- Players Information --}}
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <div class="panel panel-default" id="leadPlayersInformation" style="display: none;">
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
                                                   onblur="AutoSaveLead();"
                                                   placeholder="First Name" autocomplete="off"/>
                                            <div style="margin-top: 7px;" id="player_f_name"></div>
                                        </div>
                                        <div class="custom-col-3 mt-2">
                                            <label for="playerLastName"><strong>Last Name</strong></label>
                                            <input type="text" name="playerLastName" id="playerLastName"
                                                   class="form-control"
                                                   onblur="AutoSaveLead();"
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
                                                   onblur="UniquePlayerEmailCheck(); AutoSaveLead();"/>
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
                                                        id="playerRelationship" onchange="AutoSaveLead();">
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
                                                        onchange="checkLeadLocation(this.value);AutoSaveLead();">
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
                                                       onblur="limitZipCodeCheck();AutoSaveLead();"
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
                                            <input type="button" class="btn btn-primary float-left" value="Back"
                                                   onclick="ShowParentInformation();"/>
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

                    {{-- Get Registered --}}
                    <input type="hidden" name="getregister_or_schedulefreeclass" id="getregister_or_schedulefreeclass"
                           value="">
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <div class="panel panel-default" id="leadRegistered" style="display: none;">
                                <div class="panel-body">
                                    <div class="custom-row mb-3">
                                        <div class="custom-col-12 mt-2">
                                            <div class="form-group">
                                                <div class="mt-2">
                                                    <div class="row text-center">
                                                        <div class="col-12 mb-3">
                                                            <div class="btn-group" role="group"
                                                                 aria-label="Basic example" onclick="GetRegisterNow();">
                                                                <button type="button"
                                                                        class="btn btn-lg button-green-1 mb-0">Register
                                                                    Now
                                                                </button>
                                                                <button type="button" class="btn button-green-2 mb-0"><i
                                                                            class="fas fa-angle-right"></i></button>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="btn-group" role="group"
                                                                 aria-label="Basic example"
                                                                 onclick="ScheduleFreeClass();">
                                                                <button type="button"
                                                                        class="btn btn-lg button-blue-1 mb-0">Try Free
                                                                    Class
                                                                </button>
                                                                <button type="button" class="btn button-blue-2 mb-0"><i
                                                                            class="fas fa-angle-right"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="custom-col-3 mt-2 freeClassField" style="display:none;">
                                            <div class="form-group">
                                                <label class="control-label" for="free_class">Free Class</label>
                                                <select class="form-control select2" name="free_class"
                                                        id="free_class" onchange="getFreeClassDays(this.value);">
                                                    <option value="">Select</option>
                                                    @foreach($FreeClasses as $index => $class)
                                                        <option value="{{$class->id}}">{{$class->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="custom-col-3 mt-2 freeClassField" style="display:none;">
                                            <div class="form-group">
                                                <label class="control-label" for="free_class_date">Free Class
                                                    Date</label>
                                                <input class="form-control free_class_date" name="free_class_date"
                                                       id="free_class_date" autocomplete="off"
                                                       placeholder="MM/DD/YYYY" disabled="disabled"/>
                                            </div>
                                        </div>

                                        <div class="custom-col-3 mt-2 freeClassField" style="display:none;">
                                            <div class="form-group">
                                                <label class="control-label" for="free_class_time">Free Class
                                                    Time</label>
                                                <select class="form-control select2" name="free_class_time"
                                                        id="free_class_time" disabled="disabled">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row freeClassField" style="display: none;">
                                        <div class="col-md-12 mt-0 mb-0">
                                            <h2 style="font-size: large;">
                                                Account Information
                                            </h2>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="email" class="form-label">Email address <span
                                                        class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="_email" name="_email"
                                                   placeholder="Email" readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="_password" class="form-label">Password <span
                                                        class="text-red">*</span></label>
                                            <input type="password" class="form-control" id="_password" name="_password"
                                                   placeholder="Password">
                                            <div class="alert alert-danger" id="_passwordErrorAlert"
                                                 style="display: none;"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label" for="_confirm_password">Confirm Password <span
                                                        class="text-red">*</span></label>
                                            <input type="password" class="form-control" id="_confirm_password"
                                                   name="_confirm_password"
                                                   placeholder="Confirm Password">
                                            <div class="alert alert-danger" id="_confirmPasswordErrorAlert"
                                                 style="display: none;"></div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <input type="button" class="btn btn-primary float-left" value="Back"
                                                   onclick="ShowPlayerInformation();"/>
                                            <input type="button" class="btn btn-primary float-right"
                                                   value="Get Register"
                                                   onclick="ShowGetRegistered();" style="display:none;"/>
                                            <input type="button" class="btn btn-primary float-right" id="submitLeadBtn"
                                                   value="Submit" style="display: none;"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Get Registered --}}

                    {{-- Get Package --}}
                    <div class="row">
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
                                                            onclick="AutoSaveLead(); ShowCheckoutPage(this, 'monthly', 'monthlyPackagePrice');">
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
                                                            onclick="AutoSaveLead(); ShowCheckoutPage(this, 'annual', 'annualPackagePrice');">
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
                                                            onclick="AutoSaveLead(); ShowCheckoutPage(this, 'semi', 'semiPackagePrice');">
                                                        Select
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <input type="button" class="btn btn-primary w-10 float-left" value="Back"
                                               onclick="ShowScheduleFreeClass();"/>
                                        {{--<input type="button" class="btn btn-primary w-10 float-right" value="Next" onclick="ShowCheckoutPage();" />--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Get Package --}}

                    {{-- Get Checkout --}}
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <div id="leadCheckout" style="display: none;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <h2 class="panel-title">
                                                Account Information
                                            </h2>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label for="email" class="form-label">Email address <span
                                                                    class="text-red">*</span></label>
                                                        <input type="email" class="form-control" id="email" name="email"
                                                               placeholder="Email" readonly>
                                                        <div class="alert alert-danger" id="emailErrorAlert"
                                                             style="display: none;"></div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="password" class="form-label">Password <span
                                                                    class="text-red">*</span></label>
                                                        <input type="password" class="form-control" id="password"
                                                               name="password"
                                                               placeholder="Password">
                                                        <div class="alert alert-danger" id="passwordErrorAlert"
                                                             style="display: none;"></div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label" for="confirm_password">Confirm
                                                            Password <span class="text-red">*</span></label>
                                                        <input type="password" class="form-control"
                                                               id="confirm_password" name="confirm_password"
                                                               placeholder="Confirm Password">
                                                        <div class="alert alert-danger" id="confirmPasswordErrorAlert"
                                                             style="display: none;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
