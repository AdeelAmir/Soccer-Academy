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
            if (Checked) {
                $("#billingAddressSection").hide();
            } else {
                $("#billingAddressSection").show();
            }
        });

        $("#termsConditions").on('change', function () {
            let Checked = $(this).prop('checked');
            if (Checked) {
                $("#confirmBtn").attr('disabled', false);
            } else {
                $("#confirmBtn").attr('disabled', true);
            }
        });
    });

    function LoadStateCountyCity() {
        let state = '';
        if ($("#state").length) {
            state = $("#state option:selected").val();
        }
        if ($("#citySection").length > 0) {
            $("#citySection").show();
        }
        LoadCities(state);
    }

    function limitKeypress(event, value, maxLength) {
        if (value !== undefined && value.toString().length >= maxLength) {
            event.preventDefault();
        }
    }

    function limitZipCodeCheck(id) {
        let value = $('#' + id).val();
        if (value.toString().length < 5) {
            $('#' + id).focus();
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
        let Dob = $('#playerDOB').val();
        $.ajax({
            type: "post",
            url: "{{route('player.package.fetch')}}",
            data: {
                PlayerDOB: Dob
            }
        }).done(function (data) {
            if (!data.status) {
                $("#player_dob").show().text(data.message).css("color", "red");
            } else {
                $("#player_dob").hide().text('').css("color", "#979898");
                if (!data.package_status) {
                    alert(data.message);
                    console.log(data.category);
                } else {
                    ShowScheduleFreeClass();
                    let Category = data.category;
                    let Package = data.package;
                    $("#categoryId").val(Category[0].id);
                    $("#packageId").val(Package[0].id);
                    for (let i = 0; i < Package.length; i++) {
                        if (Package[i].fee_Type === 'monthly') {
                            $("#monthly_registration_fee").val(Package[i].registration_fee);
                            $("#monthly_fee_day_1").val(Package[i].monthly_fee_1day);
                            $("#monthly_fee_day_2").val(Package[i].monthly_fee_2day);
                            $("#monthly_fee_day_3").val(Package[i].monthly_fee_3day);
                            $("#monthly_fee_day_4").val(Package[i].monthly_fee_4day);
                        } else if (Package[i].fee_Type === 'semi-annual') {
                            $("#semi_registration_fee").val(Package[i].registration_fee);
                            $("#semi_fee_day_1").val(Package[i].monthly_fee_1day);
                            $("#semi_fee_day_2").val(Package[i].monthly_fee_2day);
                            $("#semi_fee_day_3").val(Package[i].monthly_fee_3day);
                            $("#semi_fee_day_4").val(Package[i].monthly_fee_4day);
                        } else if (Package[i].fee_Type === 'annual') {
                            $("#annual_registration_fee").val(Package[i].registration_fee);
                            $("#annual_fee_day_1").val(Package[i].monthly_fee_1day);
                            $("#annual_fee_day_2").val(Package[i].monthly_fee_2day);
                            $("#annual_fee_day_3").val(Package[i].monthly_fee_3day);
                            $("#annual_fee_day_4").val(Package[i].monthly_fee_4day);
                        }
                    }
                    /*Check for Toddler Category*/
                    if (Category[0].id === 3 || Category[0].title === 'Toddlers') {
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
            }
        });
    }

    function ShowScheduleFreeClass() {
        if (($('#playerFirstName').val() || $('#playerLastName').val()) && $('#playerDOB').val() && $('#playerEmail').val() && $('#male_gender').val() && $('#playerRelationship option:selected').val()) {
            $("#leadParentsInformation").hide();
            $("#leadPlayersInformation").hide();
            $("#leadPackage").show();
            $("#leadCheckout").hide();
            $(".step2").removeClass("disabled").addClass("complete");
            $(".step3").removeClass("complete").addClass("disabled");
            $(".step4").removeClass("complete").addClass("disabled");
            $(window).scrollTop(0);
        } else {
            // Player First Name
            if ($('#playerFirstName').val()) {
                $('#player_f_name').hide();
            } else {
                $('#player_f_name').hide();
                $("#playerFirstName").keyup(function () {
                    $('#player_f_name').hide();
                });
                if ($('#playerLastName').val() === '') {
                    $('#player_f_name').show();
                    $("#player_f_name").html("First Name or Last Name is required!").css("color", "red");
                }
            }
            // Player Last Name
            if ($('#playerLastName').val()) {
                $('#player_l_name').hide();
            } else {
                $('#player_l_name').hide();
                $("#playerLastName").keyup(function () {
                    $('#player_l_name').hide();
                });
                if ($('#playerFirstName').val() === '') {
                    $('#player_l_name').show();
                    $("#player_l_name").html("First Name or Last Name is required!").css("color", "red");
                }
            }
            // Player Email
            if ($('#playerEmail').val()) {
                $('#playerEmailErrorAlert').hide();
            } else {
                $('#playerEmailErrorAlert').hide();
                $("#playerEmail").keyup(function () {
                    $('#playerEmailErrorAlert').hide();
                });
                if ($('#playerEmail').val() === '') {
                    $('#playerEmailErrorAlert').show().html("Email is required!");
                }
            }
            // Date of Birth
            if ($('#playerDOB').val() !== '') {
                $('#player_dob').hide();
            } else {
                $('#playerDOB').on('change', function() {
                    $('#player_dob').hide();
                });
                $('#player_dob').show();
                $("#player_dob").html("Date of birth is required!").css("color", "red");
            }
            // Player Relationship
            if ($('#playerRelationship option:selected').val() !== '') {
                $('#player_relationship').hide();
            } else {
                $('#playerRelationship').on('change', function() {
                    $('#player_relationship').hide();
                });
                $('#player_relationship').show().html("Relationship is required!").css("color", "red");
            }
        }
    }

    function SetPackagePrices() {
        let MonthlyRegistration = $("#monthly_registration_fee").val();
        /*$("#monthlyPackagePrice").text('$' + (parseFloat(MonthlyRegistration) + parseFloat($("#monthly_fee_day_2").val())) );*/
        $("#monthlyPackagePrice").text('$' + $("#monthly_fee_day_2").val());
        $("#monthlyPackageRegistration").text('$' + MonthlyRegistration);
        $("#monthlyPackage1DayFee").text('$' + $("#monthly_fee_day_1").val());
        $("#monthlyPackage2DayFee").text('$' + $("#monthly_fee_day_2").val());
        $("#monthlyPackage3DayFee").text('$' + $("#monthly_fee_day_3").val());
        $("#monthlyPackage4DayFee").text('$' + $("#monthly_fee_day_4").val());

        let SemiRegistration = $("#semi_registration_fee").val();
        /*$("#semiPackagePrice").text('$' + (parseFloat(SemiRegistration) + parseFloat($("#semi_fee_day_2").val())) );*/
        $("#semiPackagePrice").text('$' + $("#semi_fee_day_2").val());
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
        if (Type === 'monthly') {
            let MonthlyRegistration = $("#monthly_registration_fee").val();
            /*$("#" + PackagePriceId).text('$' + (parseFloat(MonthlyRegistration) + parseFloat($("#monthly_fee_day_" + Value).val())) );*/
            $("#" + PackagePriceId).text('$' + $("#monthly_fee_day_" + Value).val());
        } else if (Type === 'semi') {
            let SemiRegistration = $("#semi_registration_fee").val();
            /*$("#" + PackagePriceId).text('$' + (parseFloat(SemiRegistration) + parseFloat($("#semi_fee_day_" + Value).val())) );*/
            $("#" + PackagePriceId).text('$' + $("#semi_fee_day_" + Value).val());
        } else if (Type === 'annual') {
            let AnnualRegistration = $("#annual_registration_fee").val();
            /*$("#" + PackagePriceId).text('$' + (parseFloat(AnnualRegistration) + parseFloat($("#annual_fee_day_" + Value).val())) );*/
            $("#" + PackagePriceId).text('$' + $("#annual_fee_day_" + Value).val());
        }
    }

    function ShowGetRegistered() {
        $("#leadPlayersInformation").hide();
        $("#leadPackage").show();
        $("#leadCheckout").hide();
        $(".step1").removeClass("disabled").addClass("complete");
        $(".step2").removeClass("disabled").addClass("complete");
        $(".step3").removeClass("complete").addClass("disabled");
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
            data: {
                Price: Price,
                FirstName: $("#parentFirstName").val(),
                LastName: $("#parentLastName").val(),
                Phone: $("#parentPhone").val()
            }
        }).done(function (data) {
            $(e).attr('disabled', false).text('Select');
            $(".loading").hide();
            /*Enable Remaining Buttons*/
            $("#package1Btn").attr('disabled', false);
            $("#package2Btn").attr('disabled', false);
            $("#package3Btn").attr('disabled', false);
            if (data.status) {
                $("#leadPackage").hide();
                $("#leadCheckout").show();
                $(".step1").removeClass("disabled").addClass("complete");
                $(".step2").removeClass("disabled").addClass("complete");
                $(".step3").removeClass("disabled").addClass("complete");
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

        if (Type === 'monthly') {
            SubPrice = $("#monthly_fee_day_" + SelectedDays).val();
            Registration = $("#monthly_registration_fee").val();
            $("#selectedPackageSubPrice").val(SubPrice);
            TotalPrice = parseFloat(SubPrice) + parseFloat(Registration);
        } else if (Type === 'semi') {
            SubPrice = $("#semi_fee_day_" + SelectedDays).val();
            Registration = $("#semi_registration_fee").val();
            $("#selectedPackageSubPrice").val(parseFloat(SubPrice) * 6);
            TotalPrice = (parseFloat(SubPrice) * 6) + parseFloat(Registration);
        } else if (Type === 'annual') {
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

        if (Type === 'monthly') {
            $("#planNameSummary").text('Standard');
            $("#planDurationSummary").text('1 Month');
            SubPrice = $("#monthly_fee_day_" + SelectedDays).val();
            Registration = $("#monthly_registration_fee").val();
            $("#planPriceSummary").text('$' + SubPrice + '/mo');
            $("#planRegistrationSummary").text('$' + Registration);
            TotalPrice = parseFloat(SubPrice) + parseFloat(Registration);
        } else if (Type === 'semi') {
            $("#planNameSummary").text('Most Popular');
            $("#planDurationSummary").text('6 Months');
            SubPrice = $("#semi_fee_day_" + SelectedDays).val();
            Registration = $("#semi_registration_fee").val();
            $("#planPriceSummary").text('$' + SubPrice + '/mo');
            $("#planRegistrationSummary").text('$' + Registration);
            TotalPrice = (parseFloat(SubPrice) * 6) + parseFloat(Registration);
        } else if (Type === 'annual') {
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
        if (CouponCode !== '') {
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
            if (PackageType === 'monthly') {
                RegistrationFee = $("#monthly_registration_fee").val();
            } else if (PackageType === 'semi') {
                RegistrationFee = $("#semi_registration_fee").val();
            } else if (PackageType === 'annual') {
                RegistrationFee = $("#annual_registration_fee").val();
            }

            $.ajax({
                type: "post",
                url: "{{ route('stripe.order.coupon.apply') }}",
                data: {
                    PaymentIntentId: PaymentIntentId,
                    ClientSecret: ClientSecret,
                    StripeCustomerId: StripeCustomerId,
                    LeadId: LeadId,
                    PackageId: PackageId,
                    CategoryId: CategoryId,
                    SelectedDays: SelectedDays,
                    PackageType: PackageType,
                    RegistrationFee: RegistrationFee,
                    SubPrice: SubPrice,
                    Tax: Tax,
                    ProcessingFee: ProcessingFee,
                    Price: Price,
                    CouponCode: CouponCode
                }
            }).done(function (data) {
                $(e).attr('disabled', false).text('Apply');
                $(".loading").hide();
                if (data.status) {
                    $("#planDiscountSummary").text('-$' + nf.format(data.DiscountPrice));
                    $("#planTaxSummary").text('$' + nf.format(data.NewTax));
                    $("#planProcessingFeeSummary").text('$' + nf.format(data.NewProcessingFee));
                    $("#planTotalSummary").text(nf.format(data.NewPrice) + ' USD');
                    $("#CouponCodeId").val(data.CouponCodeId);
                    $("#CouponAmount").val(nf.format(data.DiscountPrice));
                    $("#selectedPackagePrice").val('$' + nf.format(data.NewPrice));
                } else {
                    alert(data.message);
                }
            });
        }
    }

    function SubmitForm(e) {
        /*Player Information*/
        let PlayerFirstName = $("#playerFirstName").val();
        let PlayerLastName = $("#playerLastName").val();
        let PlayerDOB = $("#playerDOB").val();
        let PlayerEmail = $("#playerEmail").val();
        let PlayerGender = $("input[name='playerGender']:checked").val();
        let PlayerRelationship = $("#playerRelationship option:selected").val();
        let PlayerLocation = $("#location option:selected").val();
        let PlayerLocationZipcode = $("#locationZipcode").val();
        let Message = $("#message").val();
        let PlayerData = {
            'FirstName' : PlayerFirstName,
            'LastName' : PlayerLastName,
            'Dob' : PlayerDOB,
            'Email' : PlayerEmail,
            'Gender' : PlayerGender,
            'Relationship' : PlayerRelationship,
            'Location' : PlayerLocation,
            'Zipcode' : PlayerLocationZipcode,
            'Message' : Message
        };

        let Phone = $("#billingPhone");
        let State = $("#billingState option:selected").val();
        let City = $("#billingCity option:selected").val();
        let Street = $("#billingStreet");
        let ZipCode = $("#billingZipCode");

        let Count = 0;

        /*Stripe Billing Address Work*/
        let Checked = $("#billingAddressCheckbox").prop('checked');
        let BState = '';
        let BCity = '';
        let BZip = '';
        let BStreet = '';
        if (Checked) {
            BState = $("#parentState").val();
            BCity = $("#parentCity").val();
            BZip = $("#parentZipCode").val();
            BStreet = $("#parentStreet").val();
        } else {
            BState = State;
            BCity = City;
            BZip = ZipCode.val();
            BStreet = Street.val();

            if (Phone.val() === '') {
                Phone.focus();
                Count++;
                return;
            }

            if (State === '') {
                $("#billingState").focus();
                Count++;
                return;
            }

            if (City === '') {
                $("#billingCity").focus();
                Count++;
                return;
            }

            if (Street.val() === '') {
                Street.focus();
                Count++;
                return;
            }

            if (ZipCode.val() === '') {
                ZipCode.focus();
                Count++;
                return;
            }
        }

        if (Count !== 0) {
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
        let CouponCode = $("#CouponCodeId").val();
        let CouponAmount = $("#CouponAmount").val();

        let RegistrationFee = 0;
        if (PackageType === 'monthly') {
            RegistrationFee = $("#monthly_registration_fee").val();
        } else if (PackageType === 'semi') {
            RegistrationFee = $("#semi_registration_fee").val();
        } else if (PackageType === 'annual') {
            RegistrationFee = $("#annual_registration_fee").val();
        }

        $.ajax({
            type: "post",
            url: "{{ route('dashboard.memberships.store') }}",
            data: {
                PaymentIntentId: PaymentIntentId,
                ClientSecret: ClientSecret,
                StripeCustomerId: StripeCustomerId,
                PackageId: PackageId,
                CategoryId: CategoryId,
                SelectedDays: SelectedDays,
                PackageType: PackageType,
                SubPrice: SubPrice,
                Tax: Tax,
                ProcessingFee: ProcessingFee,
                Price: Price,
                Phone: Phone.val(),
                State: BState,
                City: BCity,
                Street: BStreet,
                ZipCode: BZip,
                RegistrationFee: RegistrationFee,
                CouponCode: CouponCode,
                CouponAmount: CouponAmount,
                PlayerData : JSON.stringify(PlayerData)
            }
        }).done(function (data) {
            $("#stripeErrorAlert").hide();
            stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: '{{route('dashboard.memberships.finish')}}',
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

    function UniquePlayerEmailCheck() {
        let Email = $("#playerEmail").val();
        if(Email !== '') {
            $.ajax({
                type: "post",
                url: "{{route('email.unique')}}",
                data: { Email: Email }
            }).done(function (data) {
                if(!data.status) {
                    $("#playerEmailErrorAlert").show().text(data.message);
                    $("#packageDetailsBtn").hide();
                } else {
                    $("#playerEmailErrorAlert").hide().text('');
                    $("#packageDetailsBtn").show();
                }
            });
        } else {
            $("#playerEmailErrorAlert").hide().text('');
            $("#packageDetailsBtn").show();
        }
    }

    function checkLeadLocation(location) {
        if (parseInt(location) === -1) {
            $("#LocationZipCodeBlock").show();
        } else {
            $("#LocationZipCodeBlock").hide();
        }
    }
</script>
