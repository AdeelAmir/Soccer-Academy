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
<div class="modal fade" id="addPositionModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">New Position</h4>
                </div>
                <form action="{{route('configuration.player-position.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group"><label for="addPositionTitle" class="control-label">Title</label>
                                    <input
                                            type="text" class="form-control" name="addPositionTitle" id="addPositionTitle"
                                            maxlength="150" required></div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group"><label for="addPositionSymbol" class="control-label">Symbol</label>
                                    <input
                                            type="text" class="form-control" name="addPositionSymbol" id="addPositionSymbol"
                                            maxlength="150" required></div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group"><label for="addPositionDescription" class="control-label">Description</label>
                                    <textarea class="form-control" name="addPositionDescription"
                                              id="addPositionDescription" maxlength="1000"></textarea></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
