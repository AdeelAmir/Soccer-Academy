<div class="modal fade" id="statusConfirmationModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Class Status</h4>
                </div>
                <form>
                    <input type="hidden" name="statusClassId" id="statusClassId" value="0">
                    <input type="hidden" name="statusClassStatus" id="statusClassStatus" value="0">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-0" id="classStatusMessage"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" onclick="dismissStatusConfirmationModal();">No</button>
                        <button type="button" class="btn btn-primary" onclick="ConfirmChangeClassStatus();">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
