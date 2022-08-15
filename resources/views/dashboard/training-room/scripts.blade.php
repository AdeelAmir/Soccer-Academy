<script type="text/javascript">
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if (Alert.length > 0) {
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }

        // Bootstrap DatePicker
        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight:'TRUE',
            autoclose: true,
        });

        // DateTimePicker
        $('.dateTimePicker').datetimepicker({
            sideBySide: true,
            showClose: true,
        });
        MakeTrainingRoomFolderTable();
        MakeTrainingRoomTable();
        MakeTrainingRoomFaqsTable();
    });

    /* Training Room - Start */
    function openTrainingRoom(id) {
        let values = id.split("_");
        window.location.href = "{{ url('training-room/folders/')}}" + "/" + values[1];
    }

    function deleteTrainingRoomFolder(id) {
        let values = id.split('_');
        let training_room_role_id = $("#training_room_role_id").val();
        $("#delete_training_room_role_id").val(training_room_role_id);
        $("#deleteTrainingRoomFolderId").val(values[1]);
        $("#deleteTrainingRoomFolderModal").modal('toggle');
    }

    function copyTrainingRoomFolder(id) {
        let values = id.split('_');
        let training_room_role_id = $("#training_room_role_id").val();
        $("#copy_training_room_role_id").val(training_room_role_id);
        $("#trainingRoomFolderId").val(values[1]);
        $("#copyTrainingRoomFolderModal").modal('toggle');
    }

    function openTrainingRoomTypeModal(e) {
        $("#trainingRoomTypeModal").modal('toggle');
    }

    function deleteTrainingRoom(id) {
        let values = id.split('_');
        $("#delete_training_room_role_id").val($("#training_room_role_id").val());
        $("#delete_training_room_folder_id").val($("#training_room_folder_id").val());
        $("#deleteTrainingRoomId").val(values[1]);
        $("#deleteTrainingRoomModal").modal('toggle');
    }

    function copyTrainingRoomItem(id) {
        let values = id.split('_');
        $("#trainingRoomItemId").val(values[1]);
        $("#copy_training_room_role_id").val($("#training_room_role_id").val());
        $("#copy_training_room_folder_id").val($("#training_room_folder_id").val());
        $("#copyTrainingRoomItemModal").modal('toggle');
    }

    function GetFolders(RoleId) {
        <?php $Url = url('training-room/folders/get'); ?>
        $.ajax({
            type: "post",
            url: "{{$Url}}",
            data: {
                Role: RoleId
            }
        }).done(function (data) {
            let s = data;
            s = s.replace(/\\n/g, "\\n")
                .replace(/\\'/g, "\\'")
                .replace(/\\"/g, '\\"')
                .replace(/\\&/g, "\\&")
                .replace(/\\r/g, "\\r")
                .replace(/\\t/g, "\\t")
                .replace(/\\b/g, "\\b")
                .replace(/\\f/g, "\\f");
            // remove non-printable and other non-valid JSON chars
            s = s.replace(/[\u0000-\u0019]+/g,"");
            let Details = JSON.parse(s);
            $("#copy_folder").html('').html(Details);
        });
    }


    function MakeTrainingRoomFolderTable() {
        if ($("#admin_training_room_folders").length > 0) {
            let TrainingRoomRoleId = $("#training_room_role_id").val();
            $("#admin_training_room_folders").DataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 50,
                "lengthMenu": [
                    [50, 100, 200, 400],
                    ['50', '100', '200', '400']
                ],
                "ajax": {
                    "url": "{{route('trainingRoom.load')}}",
                    "type": "POST",
                    "data": { "TrainingRoomRoleId": TrainingRoomRoleId }
                },
                'columns': [
                    {data: 'id', orderable: false},
                    {data: 'name', orderable: false},
                    {data: 'picture', orderable: false},
                    {data: 'required', orderable: false},
                    {data: 'action', orderable: false},
                ],
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function MakeTrainingRoomTable() {
        if ($("#admin_training_room").length) {
            $("#admin_training_room").DataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 50,
                "lengthMenu": [
                    [50, 100, 200, 400],
                    ['50', '100', '200', '400']
                ],
                "ajax": {
                    "url": "{{route('load.all')}}",
                    "type": "POST",
                    "data": {
                        "TrainingRoomFolderId": $("#training_room_folder_id").val(),
                        "TrainingRoomRoleId": $("#training_room_role_id").val()
                    }
                },
                'columns': [
                    {data: 'id', orderable: false},
                    {data: 'type', orderable: false},
                    {data: 'title', orderable: false},
                    {data: 'action', orderable: false},
                ],
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    //Faqs Script-Start
    function MakeTrainingRoomFaqsTable() {
        if ($("#admin_training_room_faqs").length) {
            $("#admin_training_room_faqs").DataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 50,
                "lengthMenu": [
                    [50, 100, 200, 400],
                    ['50', '100', '200', '400']
                ],
                "ajax": {
                    "url": "{{url('training-room/faqs/all')}}",
                    "type": "POST",
                    "data": {}
                },
                'columns': [
                    {data: 'checkbox', orderable: false},
                    {data: 'id'},
                    {data: 'question'},
                    {data: 'answer'},
                    {data: 'action', orderable: false},
                ],
                'order': [1, 'desc'],
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            }).on('page.dt', function () {
                $("#allFaqCheckBox").prop('checked', false);
                $("#deleteAllQuestionBtn").hide();
            }).on('length.dt', function (e, settings, len) {
                $("#allFaqCheckBox").prop('checked', false);
                $("#deleteAllQuestionBtn").hide();
            }).on('draw.dt', function () {
                $(".allFaqCheckBox").addClass('d-none');
                $(".allFaqActionCheckBoxColumn").addClass('w-0');
            });
        }
    }
    let faqActionCheckboxCounter = 0;
    function HandleFaqAction() {
        if (faqActionCheckboxCounter === 0) {
            $(".allFaqCheckBox").removeClass('d-none');
            $(".allFaqActionCheckBoxColumn").removeClass('w-0');
            $(".allFaqActionCheckBoxColumn").attr('style', 'padding', '10px');
            faqActionCheckboxCounter = 1;
        } else {
            $(".allFaqCheckBox").addClass('d-none');
            $(".allFaqActionCheckBoxColumn").addClass('w-0');
            $(".allFaqActionCheckBoxColumn").attr('style', 'padding', '0');
            $("#deleteAllFaqBtn").hide();
            faqActionCheckboxCounter = 0;
        }
    }

    function CheckAllFaqRecords(e) {
        let Status = $(e).prop('checked');
        if(Status){
            /*check all*/
            $(".checkAllBox").each(function (i, obj) {
                $(obj).prop('checked', true);
            });
            $("#deleteAllFaqBtn").show();
        }
        else{
            /*un check all*/
            $(".checkAllBox").each(function (i, obj) {
                $(obj).prop('checked', false);
            });
            $("#deleteAllFaqBtn").hide();
        }
    }

    function CheckIndividualFaqCheckbox() {
        let count = 0;
        $(".checkAllBox").each(function (i, obj) {
            if($(obj).prop('checked')){
                count++;
            }
        });
        if(count === 0){
            /*Not Selected*/
            $("#deleteAllFaqBtn").hide();
        }
        else{
            /*Some Selected*/
            $("#deleteAllFaqBtn").show();
        }
    }

    function DeleteMultipleFaq() {
        let deleteSelectedFaqFormUrl = $("#deleteSelectedFaqFormUrl").val();
        $('#faqForm').attr('action', deleteSelectedFaqFormUrl);
        $("#deleteFaqModal").modal('toggle');
    }


    function OpenAddFaqModal() {
        $("#addFaqModal").modal('toggle');
    }

    function EditFaq(id) {
        id = id.split('_')[1];
        $("#editFaqId").val(id);
        $.ajax({
            type: "post",
            url: "{{url('/faq/details')}}",
            data: {Id: id}
        }).done(function (data) {
            data = JSON.parse(data);
            $("#question1").val(data[0].question);
            EditFaqAnswerEditor.destroy();
            if ($("#faqAnswer1").length) {
                ClassicEditor.create(document.querySelector('#faqAnswer1'), {
                    ckfinder: {
                        uploadUrl: 'http://localhost/eliteempire/public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
                    },
                    link: {
                        addTargetToExternalLinks: true
                    },
                })
                    .then(editor => {
                        EditFaqAnswerEditor = editor;
                        editor.setData(data[0].answer);
                    })
                    .catch(error => {

                    });
            }
            $("#editFaqModal").modal('toggle');
        });
    }

    function DeleteMultipleFaq() {
        let deleteSelectedFaqFormUrl = $("#deleteSelectedFaqFormUrl").val();
        $('#faqForm').attr('action', deleteSelectedFaqFormUrl);
        $("#deleteFaqModal").modal('toggle');
    }

    function MoveFaqSearchIcon(e) {
        if (e === 1) {
            $(".searchIcon").removeClass('searchIcon1').addClass('searchIcon2');
        } else {
            if(!$("#searchFaq").focus()){
                $(".searchIcon").removeClass('searchIcon2').addClass('searchIcon1');
            }
        }
    }

    function SearchFaqActive(e) {
        $(e).addClass('active');
        $(".searchIcon").removeClass('searchIcon1').addClass('searchIcon2');
    }

    function SearchFaqBlur(e) {
        if($(e).val() === ''){
            $(e).removeClass('active');
            $(".searchIcon").removeClass('searchIcon2').addClass('searchIcon1');
        }
    }

    function OpenQuestionAnswerModal(id) {
        ClassicEditor.defaultConfig = {
            toolbar: {
                items: [
                ]
            },
            image: {
                toolbar: [
                    'imageStyle:full','imageStyle:side','|','imageTextAlternative'
                ]
            },
            table: {
                contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
            },
            language: 'en'
        };
        $.ajax({
            type: "post",
            url: "{{url('/faq/details')}}",
            data: {Id: id}
        }).done(function (data) {
            data = JSON.parse(data);
            $("#question1").val(data[0].question);
            Answer = data;
            EditFaqAnswerEditor.destroy();
            if ($("#faqAnswer1").length) {
                ClassicEditor.create(document.querySelector('#faqAnswer1'), {
                    ckfinder: {
                        uploadUrl: 'http://localhost/eliteempire/public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
                    },
                    link: {
                        addTargetToExternalLinks: true
                    },
                })
                    .then(editor => {
                        EditFaqAnswerEditor = editor;
                        editor.setData(data[0].answer);
                    })
                    .catch(error => {

                    });
            }
            $("#editFaqModal").modal('toggle');
        });
    }

    function SearchFaq(e) {
        let Value = $(e).val();
        if(Value !== ''){
            $("#mainFaqDiv").css('display', 'none');
            $.ajax({
                type: "post",
                url: "{{url('training/faqs/search')}}",
                data: {
                    Text: Value
                }
            }).done(function (data) {
                data = JSON.parse(data);
                let Rows = '';
                for(let k = 0; k < data.length; k++){
                    if(data.length === k+1){
                        Rows += '<div class="col-md-12">' +
                            // '       <p class="mb-1" onclick="OpenQuestionAnswerModal(this);" style="font-size: 16px; font-weight: bold; cursor: pointer;" data-answer="' + data[k].answer + '">Q.&nbsp;&nbsp;' + data[k].question + '</p>' +
                            '       <p class="mb-1" onclick="OpenQuestionAnswerModal('+ data[k].id +');" style="font-size: 16px; font-weight: bold; cursor: pointer;color:black">Q.&nbsp;&nbsp;' + data[k].question + '</p>' +
                            '   </div>';
                    }
                    else{
                        Rows += '<div class="col-md-12 mb-3">' +
                            // '       <p class="mb-1" onclick="OpenQuestionAnswerModal(this);" style="font-size: 16px; font-weight: bold; cursor: pointer;" data-answer="' + data[k].answer + '">Q.&nbsp;&nbsp;' + data[k].question + '</p>' +
                            '       <p class="mb-1" onclick="OpenQuestionAnswerModal('+ data[k].id +');" style="font-size: 16px; font-weight: bold; cursor: pointer;color:black">Q.&nbsp;&nbsp;' + data[k].question + '</p>' +
                            '   </div>';
                    }
                }
                $("#searchResultsFaqDiv").css('display', 'block').html('').html(Rows);
            });
        }
        else{
            $("#mainFaqDiv").css('display', 'none');
            $("#searchResultsFaqDiv").css('display', 'none');
        }
    }

    function SearchFolder(e) {
        <?php
        $Url = url('training/course/search');
        ?>
        let Value = $(e).val();
        if (Value !== '') {
            $("#TrainingRoomFolders").css('display', 'none');
            $.ajax({
                type: "post",
                url: "{{$Url}}",
                data: {
                    Text: Value
                }
            }).done(function (data) {
                let s = data;
                s = s.replace(/\\n/g, "\\n")
                    .replace(/\\'/g, "\\'")
                    .replace(/\\"/g, '\\"')
                    .replace(/\\&/g, "\\&")
                    .replace(/\\r/g, "\\r")
                    .replace(/\\t/g, "\\t")
                    .replace(/\\b/g, "\\b")
                    .replace(/\\f/g, "\\f");
                // remove non-printable and other non-valid JSON chars
                s = s.replace(/[\u0000-\u0019]+/g, "");
                let Record = JSON.parse(s);
                if (Record.total_record > 0) {
                    let Courses = JSON.parse(Record.courses);
                    $("#TrainingRoomFolders").css('display', 'none');
                    $("#searchResultsCourseDiv").css('display', 'flex').html('').html(Courses);
                } else {
                    $("#searchResultsCourseDiv").css('display', 'none');
                    $("#TrainingRoomFolders").css('display', 'flex');
                }
            });
        } else {
            $("#searchResultsCourseDiv").css('display', 'none');
            $("#TrainingRoomFolders").css('display', 'flex');
        }
    }

    function MarkVideoAsComplete(StepCount) {
        <?php
        $Url = url('training/assignment/complete');
        ?>
        let AssignmentId = $("#assignmentId_" + StepCount).val();
        $.ajax({
            type: "post",
            url: "{{$Url}}",
            data: {
                id: AssignmentId,
                courseid: $("#training_course_id").val()
            }
        }).done(function (data) {
            data = JSON.parse(data);
            window.location.reload();
        });
    }

    function MarkArticleAsComplete(StepCount) {
        <?php
        $Url = url('training/assignment/complete');
        ?>
        let AssignmentId = $("#assignmentId_" + StepCount).val();
        $.ajax({
            type: "post",
            url: "{{$Url}}",
            data: {
                id: AssignmentId,
                courseid: $("#training_course_id").val()
            }
        }).done(function (data) {
            data = JSON.parse(data);
            window.location.reload();
        });
    }

    function MarkQuizAsComplete(StepCount) {
        let AssignmentId = $("#assignmentId_" + StepCount).val();
        let TotalQuestions = parseInt($("#questionsCount" + StepCount).val());
        let CorrectCount = 0;
        for (let i = 0; i < TotalQuestions; i++) {
            let Selected = $("input[name=question" + StepCount + i + "]:checked").val();
            let Answer = $("#questionAnswer" + StepCount + i).val();
            if (Selected === undefined) {
                $("#quizQuestionDiv" + StepCount + i).css('border', '1px solid red');
            } else {
                if (Selected === Answer) {
                    $("#quizQuestionDiv" + StepCount + i).css('border', 'none');
                    CorrectCount++;
                } else {
                    $("#quizQuestionDiv" + StepCount + i).css('border', '1px solid red');
                }
            }
        }

        let Percentage = ((CorrectCount) / TotalQuestions) * 100;
        if (Percentage >= 70) {
            let success_message = [];
            success_message[0] = "✅ Yes that's right, keep it up!";
            success_message[1] = "✅ Correct! You nailed it.";
            success_message[2] = "✅ Perfect! Your hard work is paying off.";
            let random = getRndInteger(0, 3);
            if (random > 2) {
                random = 2;
            }
            $("#quizResultsModalImg").attr("src", "{{asset('public/assets/images/trophy.png')}}");
            $("#resultStatusMessage").text(success_message[random]);
            $("#continueBtn").css("display", "initial");
            $("#againBtn").css("display", "none");
        } else {
            let error_message = [];
            error_message[0] = "❌ Not quite right";
            error_message[1] = "❌ Keep trying - mistakes can help us grow.";
            let random = getRndInteger(0, 2);
            if (random > 1) {
                random = 1;
            }
            $("#quizResultsModalImg").attr("src", "{{asset('public/assets/images/sad-emoji.png')}}");
            $("#resultStatusMessage").text(error_message[random]);
            $("#continueBtn").css("display", "none");
            $("#againBtn").css("display", "initial");
        }
        $("#resultPercentage").text(Math.round(parseFloat(Percentage)) + "%.");
        $("#quizAssignmentId").val(AssignmentId);
        $("#quizResultsModal").modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function getRndInteger(min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    }

    function ResultContinue() {
        <?php
        $Url = url('training/assignment/complete')
        ?>
        let AssignmentId = $("#quizAssignmentId").val();
        $.ajax({
            type: "post",
            url: "{{$Url}}",
            data: {
                id: AssignmentId,
                courseid: $("#training_course_id").val()
            }
        }).done(function (data) {
            data = JSON.parse(data);
            window.location.reload();
        });
    }

    function ResultTryAgain() {
        location.reload();
    }

    $(document).ready(function () {
        shuffle();
    });

    function shuffle() {
        let container = document.getElementsByClassName("question-options-div");
        for (let k = 0; k < container.length; k++) {
            let elementsArray = Array.prototype.slice.call(container[k].getElementsByClassName('question-option-label'));
            elementsArray.forEach(function (element) {
                container[k].removeChild(element);
            });
            shuffleArray(elementsArray);
            elementsArray.forEach(function (element) {
                container[k].appendChild(element);
            });
        }
    }

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            let j = Math.floor(Math.random() * (i + 1));
            let temp = array[i];
            array[i] = array[j];
            array[j] = temp;
        }
        return array;
    }
</script>