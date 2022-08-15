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
<div class="modal fade" id="assignPlayerModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Edit Class Player</h4>
                </div>
                <form action="{{route('classes.assign.player.update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="assignClassId" value="0">
                    <div class="modal-body mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="coach">Player Type</label>
                                <select class="form-control" name="player_type" id="player_type" onclick="checkAssignPlayerType(this.value);">
                                  <option value="">Select</option>
                                  <option value="1">Normal</option>
                                  <option value="2">Guess</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2" id="ClassPlayers" style="display:none;">
                                <label for="coach">Players</label>
                                <select class="form-control" name="assign_class_player[]" id="assign_class_player" multiple>

                                </select>
                            </div>
                            <div class="col-md-6 mt-2" id="GuessPlayerStartDate" style="display:none;">
                                <label for="start_date">Start Date</label>
                                <input type="text" class="form-control datepicker" name="start_date" id="start_date">
                            </div>
                            <div class="col-md-6 mt-2" id="GuessPlayerEndDate" style="display:none;">
                                <label for="end_date">End Date</label>
                                <input type="text" class="form-control datepicker" name="end_date" id="end_date">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
