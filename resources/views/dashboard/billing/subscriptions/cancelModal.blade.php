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
<div class="modal fade" id="cancelModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Cancel Membership</h4>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="hiddenCancelSubscriptionId" name="hiddenCancelSubscriptionId" value="0" />
                                <p class="mb-0">Are you sure you want to cancel this membership?</p>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="cancelAccountReason" class="text-black">Reason</label>
                                <textarea name="cancelAccountReason" id="cancelAccountReason" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" onclick="ConfirmCancelSubscription(this);">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
