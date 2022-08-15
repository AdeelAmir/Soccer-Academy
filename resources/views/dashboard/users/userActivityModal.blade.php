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
    @media (min-width: 992px){
        .responsive {

        }

    }


    @media (max-width: 767px) {
        .responsive {
            overflow-x:auto;
        }


    }
</style>
<div class="modal fade" id="userAcitivityModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">User Activities</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                          <div class="responsive">
                            <table class="table w-100 tbl-responsive" id="userActivityTable">
                                <thead>
                                <tr class="replace-inputs">
                                  <th style="width: 5%;">#</th>
                                  <th style="width: 5%;">User</th>
                                  <th style="width: 90%;">Message</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
