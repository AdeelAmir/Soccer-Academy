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
<div class="modal fade" id="trainingRoomTypeModal" tabindex="200" role="dialog"
     aria-labelledby="trainingRoomTypeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog cntr" role="document" style="width: 35%;margin-top: 10%">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="trainingRoomTypeModalLabel">Training Type</h4>
            </div>
            <div class="modal-body">
                <div class="form-group text-center">
                    <button type="button" class="btn btn-light btn-lg pt-3 " name="add_video_btn" id="add_video_btn"
                            onclick="window.location.href='{{url('training-room/video/add/' . $FolderId . '/' . $RoleId)}}';"
                            style="width: 70%;">Video
                    </button>
                    <br>
                    <button type="button" class="btn btn-light btn-lg pt-3 " name="add_article_btn" id="add_article_btn"
                            onclick="window.location.href='{{url('training-room/article/add/' . $FolderId . '/' . $RoleId)}}';"
                            style="width: 70%;">Article
                    </button>
                    <br>
                    <button type="button" class="btn btn-light btn-lg pt-3 " name="add_quiz_btn" id="add_quiz_btn"
                            onclick="window.location.href='{{url('training-room/quiz/add/' . $FolderId . '/' . $RoleId)}}';"
                            style="width: 70%;">Quiz
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
