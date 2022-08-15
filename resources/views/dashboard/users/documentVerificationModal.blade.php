<div class="modal fade" id="documentVerificationModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Document Verification</h4>
                </div>
                <form>
                  <input type="hidden" name="userDocumentIndex" id="userDocumentIndex" value="0">
                  <input type="hidden" name="userDocumentId" id="userDocumentId" value="0">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="comment">Comment</label>
                                    <textarea class="form-control" name="comment" id="comment" rows="5" cols="80"></textarea>
                                    <div class="mt-2 error-msg" id="comment-error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="updateDocumentVerificationStatus(1);">Approve</button>
                        <button type="button" class="btn btn-danger" onclick="updateDocumentVerificationStatus(2);">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
