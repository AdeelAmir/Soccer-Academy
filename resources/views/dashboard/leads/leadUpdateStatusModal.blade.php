<style media="screen">
.modal-dialog {
  max-width: 550px;
  margin: 30px auto;
}
.error-handling{
  font-size: 12px;
  color: red;
}
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
<div class="modal fade" id="leadUpdateStatusModal" tabindex="200" role="dialog"
     aria-labelledby="leadUpdateStatusModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog cntr" role="document">
        <div class="modal-content">
            <form method="post" action="#" id="leadUpdateStatusForm">
                <input type="hidden" name="lead_id" id="updateStatus_leadId" value="0" />
                <div class="modal-header">
                    <h5 class="modal-title" id="leadUpdateStatusModalLabel">Lead Update Status</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12 grid-margin stretch-card">
                          <div class="card">
                              <div class="card-body">
                                  <div class="row">
                                    <div class="col-md-6 mb-3 mt-3">
                                        <label for="lead_status">Lead Status</label>
                                        <select name="lead_status" id="lead_status" class="form-control select2" onchange="checkLeadStatus(this.value);" required>
                                          <option value="" selected>Select Status</option>
                                          <option value="2">Incomplete</option>
                                          <option value="3">Follow Up</option>
                                          <option value="4">Assigned to Location</option>
                                          <option value="5">Invitation Free Class</option>
                                          <option value="6">Scheduled for Class</option>
                                          <option value="7">Attended Class</option>
                                          <option value="8">Registered</option>
                                          <option value="9">Set Up Account</option>
                                          <option value="10">Waiver</option>
                                          <option value="11">Active</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3 mt-3" id="_followUpBlock" style="display:none;">
                                        <label for="followUpTime" class="control-label">Follow Up Time</label>
                                        <div class='input-group dateTimePicker'>
                                            <input type='text' class="form-control" name="followUpTime" id="followUpTime" autocomplete="off" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="error-handling mt-2" id="followup_error"></div>
                                    </div>
                                    <div class="col-md-12 mb-3 mt-3" id="_attendedClassBlock" style="display:none;">
                                        <label for="attendedClassNote">Why they did not register?</label>
                                        <textarea class="form-control" id="attendedClassNote" name="attendedClassNote" rows="3"></textarea>
                                        <div class="error-handling mt-2" id="attended_class_error"></div>
                                    </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary float-right" type="button" onclick="UpdateLeadStatus();">Update</button>
                    <button class="btn btn-outline-secondary mr-2" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
