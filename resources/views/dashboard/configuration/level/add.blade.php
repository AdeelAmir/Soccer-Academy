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
<div class="modal fade" id="addLevelModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">New Level</h4>
                </div>
                <form action="{{route('configuration.level.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group"><label for="addLevelTitle" class="control-label">Title</label>
                                    <input
                                            type="text" class="form-control" name="addLevelTitle" id="addLevelTitle"
                                            maxlength="150" required></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><label for="addLevelSymbol" class="control-label">Symbol</label>
                                    <input
                                            type="text" class="form-control" name="addLevelSymbol" id="addLevelSymbol"
                                            maxlength="5" required></div>
                            </div>
                            <input type="hidden" name="addLevelPrice" id="addLevelPrice" value="0">
                            <div class="col-md-12">
                                <div class="form-group no-margin">
                                    <label for="addLevelDescription" class="control-label">Description</label>
                                    <textarea class="form-control autogrow" name="addLevelDescription"
                                              id="addLevelDescription" maxlength="1000"></textarea>
                                </div>
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
