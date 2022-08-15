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
<div class="modal fade" id="userActiveModal" tabindex="200" role="dialog" aria-labelledby="userActiveModalLabel"
     aria-hidden="true">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userActiveModalLabel">Active Selected User</h5>
                </div>
                <input type="hidden" name="activeSelectedUsersFormUrl" id="activeSelectedUsersFormUrl" value="{{route('users.active')}}" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>Are you sure?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Active</button>
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
