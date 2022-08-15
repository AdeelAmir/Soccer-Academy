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
<div class="modal fade" id="userBroadcastModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Broadcast</h4>
                </div>
                <input type="hidden" name="broadcastSelectedUsersFormUrl" id="broadcastSelectedUsersFormUrl" value="{{route('broadcasts.send')}}"/>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12">
                      <label for="broadcast_message">Message</label>
                      <textarea class="form-control" name="broadcast_message" id="broadcast_message" rows="5"></textarea>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
