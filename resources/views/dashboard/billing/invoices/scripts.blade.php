<script type="text/javascript">
    let DeletedDocuments = [];
    let stripe = null;
    let options = null;
    let elements = null;
    let paymentElement = null;

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
            todayHighlight:'TRUE',
            autoclose: true,
        });

        // Edit Invoice Page
        if ($("#EditInvoicePage").length > 0) {
            $(".hide-data-repeater-btn").attr('disabled', true);
        }

        MakeInvoicesTable();
    });

    function checkBillTo()
    {
      let bill_to = $("#bill_to option:selected").val();
      if (bill_to == -1) {
        $("#fullNameSection").show();
        $("#stateSection").show();
        // $("#citySection").show();
        $("#streetSection").show();
        $("#zipcodeSection").show();
        $("#playerSection").show();
      } else {
        $("#fullNameSection").hide();
        $("#stateSection").hide();
        $("#citySection").hide();
        $("#streetSection").hide();
        $("#zipcodeSection").hide();
        $("#playerSection").hide();
      }
    }

    function checkDueType()
    {
       var due_type = $("#due_type option:selected").val();
       if (due_type === 'on receipt') {
          $(".sendDateBlock").hide();
          $(".dueDateBlock").hide();
       } else if (due_type === 'pay now') {
          $(".sendDateBlock").hide();
          $(".dueDateBlock").hide();
       } else if (due_type === 'on a specific date') {
         $(".sendDateBlock").show();
         $(".dueDateBlock").show();
       }
    }

    function MakeInvoicesTable() {
        let Table = $("#invoicesTable");
        if (Table.length > 0) {
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 25,
                "lengthMenu": [
                    [25, 50, 100, 200],
                    ['25', '50', '100', '200']
                ],
                "ajax": {
                    "url": "{{route('billing.invoices.load')}}",
                    "type": "POST"
                },
                'columns': [
                    {data: 'id'},
                    {data: 'invoice_no'},
                    {data: 'title'},
                    {data: 'bill_to', orderable: false},
                    {data: 'due', orderable: false},
                    {data: 'send_date', orderable: false},
                    {data: 'total_bill', orderable: false},
                    {data: 'pdf', orderable: false},
                    {data: 'status', orderable: false},
                    {data: 'action', orderable: false},
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function DeleteInvoice(e) {
        let id = e.split('_')[1];
        $("#deleteInvoiceId").val(id);
        $("#deleteInvoiceModal").modal('toggle');
    }

    function EditInvoice(e) {
        let id = e.split('_')[1];
        window.open('{{url('billing/invoices/edit/')}}' + '/' + btoa(id), '_self');
    }

    // Edit Confirmation
    function checkConfirmation() {
      $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditInvoice() {
      $("#title").prop('disabled', false);
      $("#bill_to").prop('disabled', false);
      $("#firstName").prop('disabled', false);
      $("#middleName").prop('disabled', false);
      $("#lastName").prop('disabled', false);
      $("#state").prop('disabled', false);
      $("#city").prop('disabled', false);
      $("#street").prop('disabled', false);
      $("#zipcode").prop('disabled', false);
      $("#player").prop('disabled', false);
      $("#due_type").prop('disabled', false);
      $("#sendDate").prop('disabled', false);
      $("#dueDate").prop('disabled', false);
      $("#discount").prop('disabled', false);
      $("#message").prop('disabled', false);
      $(".hide-data-repeater-btn").attr('disabled', false);
      $("#editInvoiceBtn").hide();
      $("#submitEditInvoiceForm").show();
      $("#editConfirmationModal").modal('toggle');
    }

    // Load State and Cities
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

    function PayNow(id) {
        let Object = $("#" + id);
        let InvoiceId = Object.attr('data-id');
        let Amount = Object.attr('data-amount');
        let ParentName = Object.attr('data-parent-name');
        let ParentPhone = Object.attr('data-parent-phone');
        let ParentEmail = Object.attr('data-parent-email');
        let ParentState = Object.attr('data-parent-state');
        let ParentCity = Object.attr('data-parent-city');
        let ParentZipCode = Object.attr('data-parent-zip');
        $("#PayNowModalLoading").show();
        $("#PayNowModalStripe").hide();
        $("#payNowModalAmount").html('$' + Amount);

        $("#parent_name").val(ParentName);
        $("#parent_email").val(ParentEmail);
        $("#parent_phone").val(ParentPhone);
        $("#parent_state").val(ParentState);
        $("#parent_city").val(ParentCity);
        $("#parent_zip_code").val(ParentZipCode);

        $("#PayNowModalBtn").hide();
        $("#PayNowModal").modal('toggle');

        $.ajax({
            type: "post",
            url: "{{route('billing.invoices.payment-page.stripe.setup')}}",
            data: { Price : Amount, Name : ParentName, Phone : ParentPhone }
        }).done(function (data) {
            if(!data.status) {
                alert(data.message);
                $("#PayNowModalLoading").hide();
                $("#PayNowModalStripe").show();
            } else {
                $("#PayNowModalInvoiceId").val(InvoiceId);
                $("#PayNowModalInvoiceTotal").val(Amount);
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
                $("#PayNowModalLoading").hide();
                $("#PayNowModalStripe").show();
                $("#PayNowModalBtn").show();
            }
        });
    }

    function ConfirmPayNow(e) {
        $("#PayNowModalLoading").show();
        $("#PayNowModalStripe").hide();
        $(e).attr('disabled', true).val('Processing...');

        /*Send Data using Ajax*/
        let InvoiceId = $("#PayNowModalInvoiceId").val();
        let InvoiceTotal = $("#PayNowModalInvoiceTotal").val();
        let PaymentIntentId = $("#PaymentIntentId").val();
        let ClientSecret = $("#ClientSecret").val();
        let StripeCustomerId = $("#StripeCustomerId").val();

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
                    return_url: '{{route('billing.invoices.payment-page.process', array('true'))}}',
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
                }
            });
        });
    }
</script>
