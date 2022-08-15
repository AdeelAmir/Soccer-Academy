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
<div class="modal fade" id="userBanModal"  role="dialog" aria-labelledby="userBanModalLabel"
     aria-hidden="true">
    <div class="container d-flex align-items-center">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userBanModalLabel">Ban Selected User</h5>
                    </div>
                    <input type="hidden" name="banSelectedUsersFormUrl" id="banSelectedUsersFormUrl" value="{{route('users.ban')}}" />
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="ban_reason">Reason</label>
                                    <textarea class="form-control" name="ban_reason" id="ban_reason"></textarea>
                                    <div class="error" id="ban_reason_error" style="display:none;">Reason is required!</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" onclick="submitBanForm();">Ban</button>
                        <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
