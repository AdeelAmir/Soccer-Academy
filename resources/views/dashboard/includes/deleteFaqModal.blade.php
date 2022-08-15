<div class="modal fade" id="deleteFaqModal" tabindex="200" role="dialog" aria-labelledby="deleteFaqModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <input type="hidden" name="deleteSelectedFaqFormUrl"
                   id="deleteSelectedFaqFormUrl" value="{{url('training-room/faqs/delete')}}"/>
            <input type="hidden" name="id" id="deleteFaqId" value=""/>
            <div class="modal-header">
                <h4 class="modal-title" id="deleteExpenseModalLabel">Delete Faq</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="submit">Delete</button>
                <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
