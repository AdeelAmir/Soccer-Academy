<style>
    .cntr {
        bottom: 0;
        left: 0;
        margin: auto;
        max-height: 500px;
        max-width: 600px;
        min-width: 300px;
        position: fixed;
        right: 0;
        top: 0;
    }
</style>
<div class="modal fade" id="PayNowModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Pay Now</h4>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" name="PayNowModalInvoiceId" id="PayNowModalInvoiceId" value="">
                        <input type="hidden" name="PayNowModalInvoiceTotal" id="PayNowModalInvoiceTotal" value="">
                        <input type="hidden" name="PaymentIntentId" id="PaymentIntentId" value="">
                        <input type="hidden" name="ClientSecret" id="ClientSecret" value="">
                        <input type="hidden" name="StripeCustomerId" id="StripeCustomerId" value="">

                        <input type="hidden" name="parent_name" id="parent_name" value="" />
                        <input type="hidden" name="parent_email" id="parent_email" value="" />
                        <input type="hidden" name="parent_phone" id="parent_phone" value="" />
                        <input type="hidden" name="parent_state" id="parent_state" value="" />
                        <input type="hidden" name="parent_city" id="parent_city" value="" />
                        <input type="hidden" name="parent_zip_code" id="parent_zip_code" value="" />

                        <div class="row" id="PayNowModalLoading">
                            <div class="col-md-12">
                                Loading...
                            </div>
                        </div>

                        <div class="row" id="PayNowModalStripe" style="display: none;">
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-success">
                                    <strong>Total:</strong>
                                    <strong class="float-right" id="payNowModalAmount"></strong>
                                </div>
                            </div>

                            <div class="col-md-12">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="PayNowModalBtn" onclick="ConfirmPayNow(this);">Pay Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
