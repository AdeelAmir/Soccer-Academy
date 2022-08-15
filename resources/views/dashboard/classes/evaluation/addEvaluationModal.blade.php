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
<div class="modal fade" id="addEvaluationModal" tabindex="200" role="dialog" aria-labelledby="addEvaluationModalLabel"
     aria-hidden="true">
    <div class="modal-dialog-centered">
        <div class="modal-dialog cntr" role="document">
            <div class="modal-content">
                <form method="post" action="{{route('classes.evaluation.add')}}" id="addEvaluationForm">
                    @csrf
                    <input type="hidden" name="class_id" id="class_id" value="{{$ClassId}}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEvaluationModalLabel">Add Evaluation</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="evaluation_date">Evaluation Date</label>
                                    <input class="form-control evaluation_datepicker" name="evaluation_date"
                                           id="evaluation_date" data-validate="required"
                                           required
                                           autocomplete="off"
                                           placeholder="MM/DD/YYYY" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="evaluation_player">Select Player</label>
                                    <select class="form-control select2" name="evaluation_player" id="evaluation_player" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
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
</div>
