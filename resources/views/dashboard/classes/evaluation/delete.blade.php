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
<div class="modal fade" id="deleteEvaluationModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr">
            <div class="modal-content">
              <form method="post" action="{{route('classes.evaluation.delete')}}" id="deleteEvaluationForm">
                  @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Delete</h4>
                </div>
                <input type="hidden" name="id" id="deleteEvaluationId" value="0">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="mb-0">Are you sure?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
