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
<div class="modal fade" id="editPositionModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">View Player Position</h4>
                </div>
                <form action="{{route('configuration.player-position.update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="editPositionId" value="0">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group"><label for="editPositionTitle" class="control-label">Title</label>
                                    <input
                                            type="text" class="form-control" name="editPositionTitle" id="editPositionTitle"
                                            maxlength="150" required disabled></div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group"><label for="editPositionSymbol" class="control-label">Symbol</label>
                                    <input
                                            type="text" class="form-control" name="editPositionSymbol" id="editPositionSymbol"
                                            maxlength="150" required disabled></div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group"><label for="editPositionDescription" class="control-label">Description</label>
                                    <textarea class="form-control" name="editPositionDescription"
                                              id="editPositionDescription" maxlength="1000" disabled></textarea></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="button" name="editPositionBtn" id="editPositionBtn" class="btn btn-primary" onclick="checkConfirmation();">Edit</button>
                        <button type="submit" class="btn btn-info" name="submitEditPositionForm" id="submitEditPositionForm" style="display:none;">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
