<div class="modal fade" id="holdModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Hold Membership</h4>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="hiddenHoldId" name="hiddenHoldId" value="0" />
                                <p class="mb-0">Are you sure you want to hold this membership?</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" onclick="ConfirmHoldSubscription(this);">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>