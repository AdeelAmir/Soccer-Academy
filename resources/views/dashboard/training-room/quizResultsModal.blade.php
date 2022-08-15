<div class="modal fade" id="quizResultsModal" tabindex="200" role="dialog" aria-labelledby="quizResultsModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <input type="hidden" name="quizAssignmentId" id="quizAssignmentId" value="0" />
            <div class="modal-body text-center">
                <img id="quizResultsModalImg" src="{{asset('public/assets/images/trophy.png')}}" alt="" style="width: 200px;" />
                <h4 id="resultStatusMessage"></h4>
                <p>You have scored <span id="resultPercentage"></span></p>
            </div>
            <div class="modal-footer text-center">
                <button class="btn btn-outline-primary" type="button" id="continueBtn" data-dismiss="modal" onclick="ResultContinue();" style="display: none;">Continue</button>
                <button class="btn btn-outline-primary" type="button" id="againBtn" data-dismiss="modal" onclick="ResultTryAgain();" style="display: none;">Try Again</button>
            </div>
        </div>
    </div>
</div>

