<script type="text/javascript">
    let stripe = null;
    let options = null;
    let elements = null;
    let paymentElement = null;
    let nf = new Intl.NumberFormat('en', {
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

        // Bootstrap DatePicker
        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: 'TRUE',
            autoclose: true,
        });

        // DateTimePicker
        $('.dateTimePicker').datetimepicker({
            sideBySide: true,
            showClose: true,
        });

        // Free Class Date
        // $('.free_class_date').datepicker({
        //     format: 'mm/dd/yyyy',
        //     todayHighlight: 'TRUE',
        //     autoclose: true,
        // });

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

        $("#submitLeadBtn").on('click', function () {
            let Password = $("#_password");
            let ConfirmPassword = $("#_confirm_password");

            let Count = 0;
            if(Password.val() === '') {
                $("#_passwordErrorAlert").show().text('Password is required');
                Password.focus();
                Count++;
                return;
            }

            if(ConfirmPassword.val() === '') {
                $("#_confirmPasswordErrorAlert").show().text('Confirm Password is required');
                ConfirmPassword.focus();
                Count++;
                return;
            }

            if(Password.val() !== ConfirmPassword.val()) {
                $("#_passwordErrorAlert").show().text('Password and Confirm Password not match');
                Password.focus();
                ConfirmPassword.val('');
                Count++;
                return;
            }

            if(Count !== 0) {
                return;
            }
            $("#_passwordErrorAlert").hide();
            $("#_confirmPasswordErrorAlert").hide();
            $('form#playerRegistrationForm').submit();
        });

        let PaymentInvoiceForm = $("#invoice-payment-form");
        if(PaymentInvoiceForm.length > 0) {
            /*Make Stripe UI*/
            MakeInvoicePaymentStripeUI();
        }
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

    $('input[type=radio][name=getregister_or_schedulefreeclass]').change(function () {
        if (parseInt(this.value) === 1) {
            $(".freeClassField").hide();
        } else if (parseInt(this.value) === 2) {
            $(".freeClassField").show();
        }
    });

    function ShowParentPhone2Field() {
        $("#ParentPhoneNumber2").show();
    }

    function HideParentPhone2Field() {
        $("#parentPhone2").val('');
        $("#ParentPhoneNumber2").hide();
    }

    function ShowPlayerInformation() {
        if (($('#parentFirstName').val() || $('#parentLastName').val()) && $('#parentPhone').val() && $('#parentEmail').val() && $('#state option:selected').val() && $('#city option:selected').val() && $('#street').val() && $('#zipcode').val()) {
            $("#leadParentsInformation").hide();
            $("#leadRegistered").hide();
            $("#leadPackage").hide();
            $("#leadPlayersInformation").show();
            $(".step3").removeClass("complete").addClass("disabled");
            $(".step2").removeClass("disabled").addClass("complete");
            $(window).scrollTop(0);
        } else {
            // First Name
            if ($('#parentFirstName').val()) {
                $('#parent_f_name').hide();
            } else {
                $('#parent_f_name').hide();
                $("#parentFirstName").keyup(function () {
                    $('#parent_f_name').hide();
                });
                if ($('#parentLastName').val() === '') {
                    $('#parent_f_name').show().html("First Name or Last Name is required!").css("color", "red");
                }
            }
            // Last Name
            if ($('#parentLastName').val()) {
                $('#parent_l_name').hide();
            } else {
                $('#parent_l_name').hide();
                $("#parentLastName").keyup(function () {
                    $('#parent_l_name').hide();
                });
                if ($('#parentFirstName').val() === '') {
                    $('#parent_l_name').show().html("First Name or Last Name is required!").css("color", "red");
                }
            }
            // Phone Number 1
            if ($('#parentPhone').val() !== '') {
                $('#parent_phone1').hide();
            } else {
                $("#parentPhone").keyup(function () {
                    $('#parent_phone1').hide();
                });
                $('#parent_phone1').show().html("Phone Number 1 is required!").css("color", "red");
            }
            /*Email*/
            if ($('#parentEmail').val() !== '') {
                $('#parent_email').hide();
            } else {
                $("#parentEmail").keyup(function () {
                    $('#parent_email').hide();
                });
                $("#parent_email").show().html("Email is required!").css("color", "red");
            }
            /*State*/
            if ($('#state option:selected').val() !== '') {
                $('#parent_state').hide();
            } else {
                $("#state").change(function () {
                    $('#parent_state').hide();
                });
                $("#parent_state").show().html("State is required!").css("color", "red");
            }
            /*City*/
            if ($('#city option:selected').val() !== '') {
                $('#parent_city').hide();
            } else {
                $("#city").change(function () {
                    $('#parent_city').hide();
                });
                $("#parent_city").show().html("City is required!").css("color", "red");
            }
            /*Street*/
            if ($('#street').val() !== '') {
                $('#parent_street').hide();
            } else {
                $("#street").keyup(function () {
                    $('#parent_street').hide();
                });
                $("#parent_street").show().html("Street is required!").css("color", "red");
            }
            /*Zipcode*/
            if ($('#zipcode').val() !== '') {
                $('#parent_zipcode').hide();
            } else {
                $("#zipcode").keyup(function () {
                    $('#parent_zipcode').hide();
                });
                $("#parent_zipcode").show().html("Zipcode is required!").css("color", "red");
            }
        }
    }

    function ShowParentInformation() {
        $("#leadPlayersInformation").hide();
        $("#leadParentsInformation").show();
        $(".step2").removeClass("complete");
        $(".step2").addClass("disabled");
        $(window).scrollTop(0);
    }

    function ShowScheduleFreeClass() {
        if (($('#playerFirstName').val() || $('#playerLastName').val()) && $('#playerDOB').val() && $('#playerEmail').val() && $('#male_gender').val() && $('#playerRelationship option:selected').val()) {
            $("#leadParentsInformation").hide();
            $("#leadPlayersInformation").hide();
            $("#leadRegistered").show();
            $("#leadPackage").hide();
            $("#leadCheckout").hide();
            $(".step3").removeClass("disabled").addClass("complete");
            $(".step4").removeClass("complete").addClass("disabled");
            $(".step5").removeClass("complete").addClass("disabled");
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
                    AutoSaveLead();
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
                    AutoSaveLead();
                });
                $('#player_relationship').show().html("Relationship is required!").css("color", "red");
            }
        }
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
            if(!data.status) {
                ShowPlayerInformation();
                $("#player_dob").show().text(data.message).css("color", "red");
            } else {
                $("#player_dob").hide().text('').css("color", "#979898");
                if(!data.package_status) {
                    AutoSaveLead(12);
                    alert(data.message);
                    console.log(data.category);
                } else {
                    ShowScheduleFreeClass();
                    AutoSaveLead();
                    let Category = data.category;
                    let Package = data.package;
                    $("#categoryId").val(Category[0].id);
                    $("#packageId").val(Package[0].id);
                    for (let i = 0; i < Package.length; i++) {
                        if(Package[i].fee_Type === 'monthly') {
                            $("#monthly_registration_fee").val(Package[i].registration_fee);
                            $("#monthly_fee_day_1").val(Package[i].monthly_fee_1day);
                            $("#monthly_fee_day_2").val(Package[i].monthly_fee_2day);
                            $("#monthly_fee_day_3").val(Package[i].monthly_fee_3day);
                            $("#monthly_fee_day_4").val(Package[i].monthly_fee_4day);
                        } else if(Package[i].fee_Type === 'semi-annual') {
                            $("#semi_registration_fee").val(Package[i].registration_fee);
                            $("#semi_fee_day_1").val(Package[i].monthly_fee_1day);
                            $("#semi_fee_day_2").val(Package[i].monthly_fee_2day);
                            $("#semi_fee_day_3").val(Package[i].monthly_fee_3day);
                            $("#semi_fee_day_4").val(Package[i].monthly_fee_4day);
                        } else if(Package[i].fee_Type === 'annual') {
                            $("#annual_registration_fee").val(Package[i].registration_fee);
                            $("#annual_fee_day_1").val(Package[i].monthly_fee_1day);
                            $("#annual_fee_day_2").val(Package[i].monthly_fee_2day);
                            $("#annual_fee_day_3").val(Package[i].monthly_fee_3day);
                            $("#annual_fee_day_4").val(Package[i].monthly_fee_4day);
                        }
                    }
                    /*Check for Toddler Category*/
                    if(Category[0].id === 3 || Category[0].title === 'Toddlers') {
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
        /*Reset Account Fields*/
        $("#email").prop('required', false);
        $("#password").prop('required', false);
        $("#confirm_password").prop('required', false);
        $("#leadParentsInformation").hide();
        $("#leadPlayersInformation").hide();
        $("#leadRegistered").hide();
        $("#leadPackage").show();
        $("#leadCheckout").hide();
        $(".step4").removeClass("disabled").addClass("complete");
        $(".step5").removeClass("complete").addClass("disabled");
        $(window).scrollTop(0);
    }

    function ScheduleFreeClass() {
        $(".freeClassField").show();
        $("#submitLeadBtn").show();
        $("#getregister_or_schedulefreeclass").val(2);
    }

    function GetRegisterNow() {
        $(".freeClassField").hide();
        $("#submitLeadBtn").hide();
        /*alert('Get Register Now');*/
        $("#leadRegistered").hide();
        $("#leadPackage").show();
        $(".step4").removeClass("disabled").addClass("complete");
        $("#getregister_or_schedulefreeclass").val(1);
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

        $("#email").prop('required', true);
        $("#password").prop('required', true);
        $("#confirm_password").prop('required', true);

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
                $("#leadParentsInformation").hide();
                $("#leadPlayersInformation").hide();
                $("#leadRegistered").hide();
                $("#leadPackage").hide();
                $("#leadCheckout").show();
                $(".step5").removeClass("disabled").addClass("complete");
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

    function getFreeClassDays(class_id) {
        $.ajax({
            type: "post",
            url: "{{route('leads.freeclass.days')}}",
            data: {
                class_id: class_id,
            }
        }).done(function (data) {
            data = JSON.parse(data);
            $('#free_class_time').html('').html('<option value="">Select</option>').select2();
            $('#free_class_time').val('').prop('disabled', true);
            if ($('#free_class_date').val() != ''){
                $('#free_class_date').data('datepicker').remove();
            }
            $('#free_class_date').prop('disabled', false);
            $('.free_class_date').datepicker({
                format: 'mm/dd/yyyy',
                todayHighlight: 'FALSE',
                autoclose: true,
                startDate: new Date(),
                daysOfWeekDisabled: data,
            }).on('changeDate', function(e) {
                let class_id = $("#free_class option:selected").val();
                let free_class_date = $("#free_class_date").val();
                $.ajax({
                    type: "post",
                    url: "{{route('leads.freeclass.timing')}}",
                    data: {
                        class_id: class_id,
                        class_date: free_class_date,
                    }
                }).done(function (data) {
                    let s = data;
                    s = s.replace(/\\n/g, "\\n")
                        .replace(/\\'/g, "\\'")
                        .replace(/\\"/g, '\\"')
                        .replace(/\\&/g, "\\&")
                        .replace(/\\r/g, "\\r")
                        .replace(/\\t/g, "\\t")
                        .replace(/\\b/g, "\\b")
                        .replace(/\\f/g, "\\f");
                    // remove non-printable and other non-valid JSON chars
                    s = s.replace(/[\u0000-\u0019]+/g, "");
                    let timing = JSON.parse(s);
                    $("#free_class_time").prop('disabled',false);
                    $("#free_class_time").html('').html(timing).select2();
                });
            });
        });
    }

    function checkLeadLocation(location) {
        if (parseInt(location) === -1) {
            $("#LocationZipCodeBlock").show();
        } else {
            $("#LocationZipCodeBlock").hide();
        }
    }

    function AutoSaveLead(Status = '') {
        $.ajax({
            type: "post",
            url: "{{route('parent.information.store')}}",
            data: {
                LeadId: $("#lead_id").val(),
                ParentFirstName: $("#parentFirstName").val(),
                ParentLastName: $("#parentLastName").val(),
                ParentPhone1: $("#parentPhone").val(),
                ParentPhone2: $("#parentPhone2").val(),
                ParentEmail: $("#parentEmail").val(),
                ParentDOB: $("#parentDOB").val(),
                State: $("#state option:selected").val(),
                City: $("#city option:selected").val(),
                Street: $("#street").val(),
                Zipcode: $("#zipcode").val(),
                Status : Status,
                PlayerFirstName: $("#playerFirstName").val(),
                PlayerLastName: $("#playerLastName").val(),
                PlayerDOB: $("#playerDOB").val(),
                PlayerEmail: $("#playerEmail").val(),
                PlayerGender: $("input[type='radio']").val(),
                PlayerRelationship: $('#playerRelationship option:selected').val(),
                PlayerLocation: $('#location option:selected').val(),
                PlayerLocationZipcode: $('#locationZipcode').val(),
                Message: $("#message").val(),
                FreeClass: $("#free_class").val(),
                FreeClassDate: $("#free_class_date").val(),
                FreeClassTime: $("#free_class_time").val(),
                GetRegisterOrScheduleFreeClass: $("#getregister_or_schedulefreeclass").val(),
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.status === 'success') {
                $("#lead_id").val(data.lead_id);
                $("#error-message-alert").hide().html('');
                $("#playerInfoNextBtn").show();
            } else if(data.status === 'lead_converted') {
                let LoginUrl = '{{url('login')}}';
                $("#error-message-alert").show().html('<span>Your account already exists! To add new child, <a href="' + LoginUrl + '" class="text-white">login to your dashboard</a></span>');
                $("#playerInfoNextBtn").hide();
            }
        });
    }

    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
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
        let Email = $("#email");
        let Password = $("#password");
        let ConfirmPassword = $("#confirm_password");
        let Phone = $("#billingPhone");
        let State = $("#billingState option:selected").val();
        let City = $("#billingCity option:selected").val();
        let Street = $("#billingStreet");
        let ZipCode = $("#billingZipCode");

        let Count = 0;
        if(Email.val() === '' || !validateEmail(Email.val())) {
            $("#emailErrorAlert").show().text('Use a Valid Email Address');
            Email.focus();
            Count++;
            return;
        }

        if(Password.val() === '') {
            $("#passwordErrorAlert").show().text('Password is required');
            Password.focus();
            Count++;
            return;
        }

        if(ConfirmPassword.val() === '') {
            $("#confirmPasswordErrorAlert").show().text('Confirm Password is required');
            ConfirmPassword.focus();
            Count++;
            return;
        }

        if(Password.val() !== ConfirmPassword.val()) {
            $("#passwordErrorAlert").show().text('Password and Confirm Password not match');
            Password.focus();
            ConfirmPassword.val('');
            Count++;
            return;
        }

        /*Stripe Billing Address Work*/
        let Checked = $("#billingAddressCheckbox").prop('checked');
        let BState = '';
        let BCity = '';
        let BZip = '';
        let BStreet = '';
        if(Checked) {
            BState = $("#state option:selected").val();
            BCity = $("#city option:selected").val();
            BZip = $("#zipcode").val();
            BStreet = $("#street").val();
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
        $("#emailErrorAlert").hide();
        $("#passwordErrorAlert").hide();
        $("#confirmPasswordErrorAlert").hide();

        $(e).attr('disabled', true).val('Processing...');
        $(".loading").show();

        /*Send Data using Ajax*/
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
            url: "{{ route('stripe.order.create') }}",
            data: {
                PaymentIntentId : PaymentIntentId,
                ClientSecret : ClientSecret,
                StripeCustomerId : StripeCustomerId,
                Email: Email.val(),
                Password: Password.val(),
                LeadId : LeadId,
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
                    return_url: '{{route('stripe.order.finish')}}',
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

    function UniqueEmailCheck() {
        let Email = $("#parentEmail").val();
        if(Email !== '') {
            $.ajax({
                type: "post",
                url: "{{route('email.unique')}}",
                data: {Email: Email}
            }).done(function (data) {
                if(!data.status) {
                    $("#parent_email").show().text(data.message).css("color", "red");
                    /*$("#confirmBtn").attr('disabled', true);*/
                    $("#playerInfoNextBtn").hide();
                    $("#email").val('');
                    $("#_email").val('');
                } else {
                    $("#parent_email").hide().text('').css("color", "initial");
                    /*$("#confirmBtn").attr('disabled', false);*/
                    $("#playerInfoNextBtn").show();
                    $("#email").val(Email);
                    $("#_email").val(Email);
                }
            });
        } else {
            $("#parent_email").hide().text('').css("color", "initial");
            /*$("#confirmBtn").attr('disabled', false);*/
            $("#playerInfoNextBtn").show();
            $("#email").val('');
            $("#_email").val('');
        }
    }

    function checkMobileFormat(event, element) {
        let Phone = $(element);
        if (event.keyCode === 189 || event.keyCode === 69) {
            event.preventDefault();
        }

        if (
            (event.keyCode < 48 || event.keyCode > 57) /*Numbers Only*/
            &&
            (event.keyCode !== 8 && event.keyCode !== 9) /*Backspace and Tab*/
            &&
            (event.keyCode !== 37 && event.keyCode !== 38 && event.keyCode !== 39 && event.keyCode !== 40)) /*Keyboard Arrows*/
        {
            event.preventDefault();
        }

        if (event.keyCode !== 8 && event.keyCode !== 9) {
            let checkLength = $(element).val();
            if (checkLength.length >= 10) {
                event.preventDefault();
            }
        }
    }

    function MakeInvoicePaymentStripeUI() {
        let TotalBill = $("#invoice_total").val();
        let ParentName = $("#parent_name").val();
        let ParentPhone = $("#parent_phone").val();
        $.ajax({
            type: "post",
            url: "{{route('billing.invoices.payment-page.stripe.setup')}}",
            data: { Price : TotalBill, Name : ParentName, Phone : ParentPhone }
        }).done(function (data) {
            if(!data.status) {
                alert(data.message);
                $(".loading").hide();
            } else {
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
                $(".loading").hide();
            }
        });
    }

    function SubmitInvoiceForm(e) {
        $(e).attr('disabled', true).val('Processing...');
        $(".loading").show();

        /*Send Data using Ajax*/
        let PaymentIntentId = $("#PaymentIntentId").val();
        let ClientSecret = $("#ClientSecret").val();
        let StripeCustomerId = $("#StripeCustomerId").val();
        let InvoiceId = $("#invoice_id").val();
        let InvoiceTotal = $("#invoice_total").val();

        $.ajax({
            type: "post",
            url: "{{ route('billing.invoices.payment-page.stripe.create') }}",
            data: {
                PaymentIntentId : PaymentIntentId,
                ClientSecret : ClientSecret,
                StripeCustomerId : StripeCustomerId,
                InvoiceId: InvoiceId,
                InvoiceTotal: InvoiceTotal
            }
        }).done(function (data) {
            $("#stripeErrorAlert").hide();
            stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: '{{route('billing.invoices.payment-page.process')}}',
                    payment_method_data: {
                        billing_details: {
                            name: $("#parent_name").val(),
                            email: $("#parent_email").val(),
                            phone: $("#parent_phone").val(),
                            address: {
                                country: 'US',
                                state: $("#parent_state").val(),
                                city: $("#parent_city").val(),
                                postal_code: $("#parent_zip_code").val(),
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
