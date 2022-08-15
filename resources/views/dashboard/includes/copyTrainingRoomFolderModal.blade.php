<div class="modal fade" id="copyTrainingRoomFolderModal" tabindex="200" role="dialog"
     aria-labelledby="copyTrainingRoomFolderModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{route('trainingRoomFolder.copy')}}" id="copyTrainingRoomFolderForm">
                @csrf
                <input type="hidden" name="id" id="trainingRoomFolderId" value=""/>
                <input type="hidden" name="copy_training_room_role_id" id="copy_training_room_role_id" value=""/>
                <div class="modal-header">
                    <h4 class="modal-title" id="copyTrainingRoomFolderModalLabel">Copy</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="firstname">Role</label>
                            <select class="form-control" name="role" id="_role" required>
                                <option value="">Select Role</option>
                                @if($Role == 1)
                                    {{--<option value="2">Global Manager</option>--}}
                                    <option value="3">Manager</option>
                                    <option value="4">Coach</option>
                                    <option value="5">Parent</option>
                                @endif
                                @if($Role == 1 || $Role == 4)
                                    <option value="6">Player</option>
                                @endif
                                @if($Role == 1)
                                    <option value="7">Affiliates</option>
                                    <option value="8">Virtual Assistant</option>
                                @endif
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
