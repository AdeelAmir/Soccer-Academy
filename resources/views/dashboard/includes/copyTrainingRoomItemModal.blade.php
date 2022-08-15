<div class="modal fade" id="copyTrainingRoomItemModal" tabindex="200" role="dialog"
     aria-labelledby="copyTrainingRoomItemModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{url('training-room/copy')}}" id="copyTrainingRoomItemForm">
                @csrf
                <input type="hidden" name="id" id="trainingRoomItemId" value=""/>
                <input type="hidden" name="role" id="copy_training_room_role_id" value=""/>
                <input type="hidden" name="folder_id" id="copy_training_room_folder_id" value=""/>
                <div class="modal-header">
                    <h4 class="modal-title" id="copyTrainingRoomItemModalLabel">Copy</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="firstname">Role</label>
                            <select class="form-control" name="role" id="_role" onchange="GetFolders(this.value);"
                                    required>
                                <option value="">Select Role</option>
                                {{--<option value="2">Global Manager</option>--}}
                                <option value="3">Manager</option>
                                <option value="4">Coach</option>
                                <option value="5">Parent</option>
                                <option value="6">Player</option>
                                <option value="7">Affiliates</option>
                                <option value="8">Virtual Assistant</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="copy_folder">Folder</label>
                            <select class="form-control select2" name="folder" id="copy_folder" required>
                                <option value="">Select Folder</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
