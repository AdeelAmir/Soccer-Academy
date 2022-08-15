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
<div class="modal fade" id="userBanActiveModal" tabindex="200" role="dialog" aria-labelledby="userBanActiveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userBanActiveModalLabel"></h5>
                </div>
                <input type="hidden" name="BanActiveType" id="BanActiveType" value="">
                <input type="hidden" name="BanActiveUserId" id="BanActiveUserId" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="BanActiveDiv1" style="display: none;">
                            <p>Are you sure?</p>
                        </div>

                        <div class="col-md-12" id="BanActiveDiv2" style="display: none;">
                            <div class="form-group">
                                <label for="ban_active_reason">Reason</label>
                                <textarea class="form-control" name="ban_active_reason" id="ban_active_reason"></textarea>
                                <div class="error" id="ban_active_reason_error" style="display:none;">Reason is required!</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" onclick="BanActiveUser(this);">Submit</button>
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
