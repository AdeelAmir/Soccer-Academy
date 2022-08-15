<div class="modal fade" id="deleteLevelModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Delete Level</h4>
                </div>
                <form action="{{route('configuration.level.delete')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="deleteLevelId" value="0">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-0">Are you sure you want to delete this level <b id="deleteLevelTitle"></b>?</p>
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