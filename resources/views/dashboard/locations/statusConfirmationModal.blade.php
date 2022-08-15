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
<div class="modal fade" id="statusConfirmationModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Location Status</h4>
                </div>
                <form>
                    <input type="hidden" name="statusLocationId" id="statusLocationId" value="0">
                    <input type="hidden" name="statusLocationStatus" id="statusLocationStatus" value="0">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-0" id="locationStatusMessage"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" onclick="dismissStatusConfirmationModal();">No</button>
                        <button type="button" class="btn btn-primary" onclick="ConfirmChangeLocationStatus();">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
