<div class="modal fade" id="addFaqModal" tabindex="200" role="dialog" aria-labelledby="addFaqModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{url('training-room/faqs/add')}}" id="addFaqForm">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="addFaqModalLabel">Add Question</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label" for="question">Question</label>
                        <input type="text" name="question" id="question" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="faqAnswer">Answer</label>
                        <textarea class="form-control" name="answer" id="faqAnswer" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Add</button>
                    <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
