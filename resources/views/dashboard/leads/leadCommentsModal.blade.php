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
<div class="modal fade" id="leadCommentsModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr">
            <div class="modal-content">
                <form method="post" action="#" id="leadCommentsForm">
                    <input type="hidden" name="lead_comments_id" id="lead_comments_id" value="0" />
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Lead Comments</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="_lead_history_note">Comment</label>
                                    <textarea name="lead_comment" id="_lead_comment" rows="3" cols="80" class="form-control"></textarea>
                                    <div class="error pt-2" id="_lead_comment_error" style="display:none;">Comment is missing!</div>
                                </div>
                            </div>
                            <div class="col-md-12">
                              <button class="btn btn-primary float-right" type="button" onclick="SaveDashboardLeadComment();">Add</button>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="panel panel-default" style="padding: 0;">
                                <div class="panel-heading">
                                    All Comments
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="lead_comments_table" class="table w-100">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">Created At</th>
                                                    <th style="width: 5%;">#</th>
                                                    <th style="width: 30%;">User</th>
                                                    <th style="width: 65%;">Comment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                             </div>
                          </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
