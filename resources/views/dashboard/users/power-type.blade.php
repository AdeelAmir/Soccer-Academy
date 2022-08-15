<div class="modal fade" id="powerTypeUserModal">
  <div class="modal-dialog-centered">
    <div class="modal-dialog" role="document" style="width: 450px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="powerTypeUserModalLabel">Power Type</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="powerTypeUserId" />
                <div class="form-group text-center" style="margin-top: 20px;">
                  <button type="button" class="btn btn-primary btn-lg" name="user_power_type_btn" id="user_power_type_btn"
                          style="width: 70%;" onclick="submitUserPowerType(1);">User</button>
                  <br>
                  <button type="button" class="btn btn-primary btn-lg" name="feature_power_type_btn" id="feature_power_type_btn"
                          style="width: 70%;" onclick="submitUserPowerType(2);">Feature</button>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
  </div>
</div>
