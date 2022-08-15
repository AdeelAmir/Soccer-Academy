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
<div class="modal fade" id="addCategoryModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr modal-sm categoryModalSetting">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">New Category</h4>
                </div>
                <form action="{{route('configuration.categories.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label for="addCategoryTitle" class="control-label">Title</label>
                                    <input
                                            type="text" class="form-control" name="addCategoryTitle" id="addCategoryTitle"
                                            maxlength="150" required></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label for="addCategorySymbol" class="control-label">Symbol</label>
                                    <input
                                            type="text" class="form-control" name="addCategorySymbol" id="addCategorySymbol"
                                            maxlength="5" required></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label for="addCategoryStartAge" class="control-label">Min Age</label>
                                    <input
                                            type="number" step="any" class="form-control" name="addCategoryStartAge" id="addCategoryStartAge"
                                            maxlength="5" required></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label for="addCategoryEndAge" class="control-label">Max Age</label>
                                    <input
                                            type="number" step="any" class="form-control" name="addCategoryEndAge" id="addCategoryEndAge"
                                            maxlength="5" required></div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group"><label for="addCategoryDescription" class="control-label">Description</label>
                                    <textarea class="form-control" name="addCategoryDescription"
                                              id="addCategoryDescription" maxlength="1000"></textarea></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
