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
<div class="modal fade" id="editLevelModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">View Level</h4>
                </div>
                <form action="{{route('configuration.level.update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="editLevelId" value="0">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group"><label for="editLevelTitle" class="control-label">Title</label>
                                    <input
                                            type="text" class="form-control" name="editLevelTitle" id="editLevelTitle"
                                            maxlength="150" required disabled></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><label for="editLevelSymbol" class="control-label">Symbol</label>
                                    <input
                                            type="text" class="form-control" name="editLevelSymbol" id="editLevelSymbol"
                                            maxlength="50" required disabled></div>
                            </div>
                            <input type="hidden" name="editLevelPrice" id="editLevelPrice" value="0">
                            <div class="col-md-12">
                                <div class="form-group no-margin">
                                    <label for="editLevelDescription" class="control-label">Description</label>
                                    <textarea class="form-control autogrow" name="editLevelDescription"
                                              id="editLevelDescription" maxlength="1000" disabled></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="button" name="editLevelBtn" id="editLevelBtn" class="btn btn-primary" onclick="checkConfirmation();">Edit</button>
                        <button type="submit" class="btn btn-info" name="submitEditLevelForm" id="submitEditLevelForm" style="display:none;">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
