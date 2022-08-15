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
<div class="modal fade" id="editCategoryModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">View Category</h4>
                </div>
                <form action="{{route('configuration.categories.update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="editCategoryId" value="0">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label for="editCategoryTitle" class="control-label">Title</label>
                                    <input
                                            type="text" class="form-control" name="editCategoryTitle" id="editCategoryTitle"
                                            maxlength="150" required disabled></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label for="editCategorySymbol" class="control-label">Symbol</label>
                                    <input
                                            type="text" class="form-control" name="editCategorySymbol" id="editCategorySymbol"
                                            maxlength="150" required disabled></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label for="editCategoryStartAge" class="control-label">Min Age</label>
                                    <input
                                            type="number" step="any" class="form-control" name="editCategoryStartAge" id="editCategoryStartAge"
                                            maxlength="5" required disabled></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label for="editCategoryEndAge" class="control-label">Max Age</label>
                                    <input
                                            type="number" step="any" class="form-control" name="editCategoryEndAge" id="editCategoryEndAge"
                                            maxlength="5" required disabled></div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group"><label for="editCategoryDescription" class="control-label">Description</label>
                                    <textarea class="form-control" name="editCategoryDescription"
                                              id="editCategoryDescription" maxlength="1000" disabled></textarea></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="button" name="editCategoryBtn" id="editCategoryBtn" class="btn btn-primary" onclick="checkConfirmation();">Edit</button>
                        <button type="submit" class="btn btn-info" name="submitEditCategoryForm" id="submitEditCategoryForm">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
