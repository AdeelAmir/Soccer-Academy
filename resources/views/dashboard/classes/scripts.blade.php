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

        $('.attendance_datepicker').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight:'TRUE',
            autoclose: true,
            endDate: new Date(new Date().setDate(new Date().getDate() + 30)),
        });

        $('.evaluation_datepicker').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight:'TRUE',
            autoclose: true,
        })
        .on('changeDate', function (ev) {
            let class_id = $("#class_id").val();
            let evaluation_date = $("#evaluation_date").val();
            $.ajax({
                type: "post",
                url: "{{route('classes.evaluation.player')}}",
                data: { ClassId : class_id, EvaluationDate: evaluation_date }
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
                let players = JSON.parse(s);
                $("#evaluation_player").html('').html(players).select2();
            });
        });

        $('#expiration_date_time').datetimepicker({
            sideBySide: true,
            showClose: true,
        });

        // Edit Class Page
        if ($("#EditClassPage").length > 0) {
            $(".hide-data-repeater-btn").attr('disabled', true);
        }

        MakeClassesTable();
        MakeClassPlayersTable();
        MakeParentEvaluationReportTable();
        MakePlayerEvaluationReportTable();
    });

    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 45  && charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
        } else {
            return true;
        }
    }

    function UserFilterBackButton() {
        $("#beforeTablePage").removeClass("col-md-1");
        $("#tablePage").removeClass('col-md-12').addClass('col-md-9');
        $("#filterPage").show().removeClass("col-md-2").addClass("col-md-3");
    }

    function UserFilterCloseButton() {
        $("#beforeTablePage").addClass("col-md-1");
        $("#tablePage").removeClass('col-md-9').addClass('col-md-12');
        $("#filterPage").hide().removeClass("col-md-3").addClass("col-md-2");
    }

    function DestroyDataTable() {
        let Table = $("#classesTable");
        Table.DataTable().destroy();
    }

    function MakeClassesTable() {
        let Table = $("#classesTable");
        if (Table.length > 0) {
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 25,
                "lengthMenu": [
                    [25, 50, 100, 200],
                    ['25', '50', '100', '200']
                ],
                "ajax": {
                    "url": "{{route('classes.load')}}",
                    "type": "POST",
                    "data": {
                        Location: JSON.stringify($("#class_location").val()),
                    }
                },
                'columns': [
                    {data: 'created_at', bVisible: false},
                    {data: 'id'},
                    {data: 'category'},
                    {data: 'package'},
                    {data: 'location'},
                    {data: 'status'},
                    {data: 'action', orderable: false},
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function MakePlayerEvaluationReportTable() {
        let Table = $("#playerEvaluationReportTable");
        if (Table.length > 0) {
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 5,
                "lengthMenu": [
                    [5, 10, 20, 40],
                    ['5', '10', '20', '40']
                ],
                "ajax": {
                    "url": "{{route('dashboard.player.evaluationreport')}}",
                    "type": "POST",
                },
                'columns': [
                    {data: 'sr_no', orderable: false},
                    {data: 'id', orderable: false},
                    {data: 'player', orderable: false},
                    {data: 'grade', orderable: false},
                    {data: 'date', orderable: false},
                ],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function EditClass(e) {
        let id = e.split('||')[1];
        window.open('{{url('classes/edit/')}}' + '/' + btoa(id), '_self');
    }

    function DeleteClass(e) {
        let id = e.split('||')[1];
        $("#deleteClassId").val(id);
        $("#deleteClassModal").modal('toggle');
    }

    function SetRegistrationMonthlyFee() {
        let Values = [];
        Values = $("select#days").val();
        if(Values !== null){
            $("#registrationFee").val($("#registration_fee").val());
            if (Values.length === 1) {
                $("#monthlyFee").val($("#monthly_fee_1day").val());
            } else if (Values.length === 2) {
                $("#monthlyFee").val($("#monthly_fee_2day").val());
            } else if (Values.length === 3) {
                $("#monthlyFee").val($("#monthly_fee_3day").val());
            } else if (Values.length === 4) {
                $("#monthlyFee").val($("#monthly_fee_4day").val());
            }
        } else {
            $("#registrationFee").val(0);
            $("#monthlyFee").val(0);
        }
    }

    function CheckClassFeeStatus(Checked) {
        if (Checked) {
            $("#registrationFee").val(0);
            $("#monthlyFee").val(0);
        } else {
            SetRegistrationMonthlyFee();
        }
    }

    function ChangeClassStatus(Checked, id) {
        if (Checked === true) {
          $("#classStatusMessage").text('Are you sure you want to turned on the class?');
          $("#statusClassId").val(id);
          $("#statusClassStatus").val(Checked);
        } else {
          $("#classStatusMessage").text('Are you sure you want to turned off the class?');
          $("#statusClassId").val(id);
          $("#statusClassStatus").val(Checked);
        }
        $("#statusConfirmationModal").modal('toggle');
    }

    function dismissStatusConfirmationModal(){
        $("#statusConfirmationModal").modal('toggle');
        $('#classesTable').DataTable().ajax.reload();
    }

    function ConfirmChangeClassStatus() {
        var id = $("#statusClassId").val();
        var Checked = $("#statusClassStatus").val();
        $.ajax({
            type: "post",
            url: "{{route('classes.update.status')}}",
            data: { Checked : Checked, id : id }
        }).done(function (data) {
            $('#classesTable').DataTable().ajax.reload();
            $("#statusConfirmationModal").modal('toggle');
        });
    }

    // Edit Confirmation
    function checkConfirmation() {
      $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditClass() {
      $("#title").prop('disabled', false);
      $("#coach").prop('disabled', false);
      $("#category").prop('disabled', false);
      $("#location").prop('disabled', false);
      $("#time").prop('disabled', false);
      $("#days").prop('disabled', false);
      $("#is_free").prop('disabled', false);
      $(".hide-data-repeater-btn").attr('disabled', false);
      $("#editClassBtn").hide();
      $("#submitEditClassForm").show();
      $("#editConfirmationModal").modal('toggle');
    }

    /* CLASS ATTENDENCE - START */
    function ViewClassAttendence(e) {
        let id = e.split('||')[1];
        window.open('{{url('classes/attendance/')}}' + '/' + btoa(id), '_self');
    }
    /* CLASS ATTENDENCE - END */

    /* CLASS PLAYERS - START */
    function ViewClassPlayers(e) {
        let id = e.split('||')[1];
        $("#assignClassId").val(id);
        $("#assignPlayerModal").modal('toggle');
    }
    /* CLASS PLAYERS - END */

    /* CLASS EVALUATION - START */
    function ClassEvaluation(e) {
        let id = e.split('||')[1];
        window.open('{{url('classes/evaluation/')}}' + '/' + id, '_self');
    }

    function MakeClassPlayersTable() {
        let Table = $("#classPlayersTable");
        if (Table.length > 0) {
            let evaluation_class_id = $("#evaluation_class_id").val();
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 25,
                "lengthMenu": [
                    [25, 50, 100, 200],
                    ['25', '50', '100', '200']
                ],
                "ajax": {
                    "url": "{{route('classes.players.load')}}",
                    "type": "POST",
                    "data": {id : evaluation_class_id},
                },
                'columns': [
                    {data: 'id', orderable: false},
                    {data: 'report_no', orderable: false},
                    {data: 'player', orderable: false},
                    {data: 'evaluation_date', orderable: false},
                    {data: 'grade', orderable: false},
                    {data: 'report_pdf', orderable: false},
                    {data: 'action', orderable: false},
                ],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function openAddEvaluationModal() {
        $("#addEvaluationModal").modal("toggle");
    }

    function DeleteClassPlayerEvaluation(e) {
        let evaluation_id = e.split('||')[1];
        $("#deleteEvaluationId").val(evaluation_id);
        $("#deleteEvaluationModal").modal("toggle");
    }

    function EditClassPlayerEvaluation(e) {
        let evaluation_id = e.split('||')[1];
        window.open('{{url('classes/evaluation/edit/')}}' + '/' + evaluation_id, '_self');
    }

    function checkEvaluationConfirmation() {
      $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditEvaluation() {
      $('#respective').prop('disabled', false);
      $('#attention').prop('disabled', false);
      $('#concentration').prop('disabled', false);
      $('#leadership').prop('disabled', false);
      $('#energetic').prop('disabled', false);
      $('#discipline').prop('disabled', false);
      $('#running').prop('disabled', false);
      $('#passing_receiving').prop('disabled', false);
      $('#kicking').prop('disabled', false);
      $('#ball_control').prop('disabled', false);
      $('#shooting').prop('disabled', false);
      $('#balance').prop('disabled', false);
      $("#editEvaluationBtn").hide();
      $("#submitEditPlayerEvaluationBtn").show();
      $("#editConfirmationModal").modal('toggle');
    }
    /* CLASS EVALUATION - END */

    /* PARENT EVALUATION REPORTS - START */
    function MakeParentEvaluationReportTable() {
        let Table = $("#parentsEvaluationReportTable");
        if (Table.length > 0) {
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 25,
                "lengthMenu": [
                    [25, 50, 100, 200],
                    ['25', '50', '100', '200']
                ],
                "ajax": {
                    "url": "{{route('dashboard.parent.evaluationreport')}}",
                    "type": "POST",
                },
                'columns': [
                    {data: 'sr_no', orderable: false},
                    {data: 'id', orderable: false},
                    {data: 'player', orderable: false},
                    {data: 'grade', orderable: false},
                    {data: 'date', orderable: false},
                ],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }
    /* PARENT EVALUATION REPORTS - END */

    /* Coach Classes - Start */
    function checkAnnouncementFor(value)
    {
        if (value === '1') {
            $("#announcementPlayersBlock").hide();
            $("#announcementClassesBlock").show();
            $("#announcement_classes").prop("required", true);
            $("#announcement_player").prop("required", false);
        } else if (value == '2') {
            $("#announcementClassesBlock").hide();
            $("#announcementPlayersBlock").show();
            $("#announcement_player").prop("required", true);
            $("#announcement_classes").prop("required", false);
        } else {
            $("#announcementPlayersBlock").hide();
            $("#announcementClassesBlock").hide();
            $("#announcement_classes").prop("required", false);
            $("#announcement_player").prop("required", false);
        }
    }

    function openPlayerAnnouncementModal()
    {
        $("#playerAnnouncementModal").modal("toggle");
    }
    /* Coach Classes - End */

    /* Assign Class Player - Start */
    function checkAssignPlayerType(type)
    {
        let class_id = $("#assignClassId").val();
        if (parseInt(type) === 1) {
            $("#GuessPlayerStartDate").hide();
            $("#GuessPlayerEndDate").hide();
            $.ajax({
                type: "post",
                url: "{{route('classes.assign.player')}}",
                data: { id : class_id, type: type }
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
                let players = JSON.parse(s);
                $("#assign_class_player").html('').html(players).select2();
                $("#ClassPlayers").show();
            });
        } else if (parseInt(type) === 2) {
            $("#GuessPlayerStartDate").show();
            $("#GuessPlayerEndDate").show();
            $.ajax({
                type: "post",
                url: "{{route('classes.assign.player')}}",
                data: { id : class_id, type: type }
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
                let players = JSON.parse(s);
                $("#assign_class_player").html('').html(players).select2();
                $("#ClassPlayers").show();
            });
        }
    }
    /* Assign Class Player - End */

    let FilterCheck = 0;

    function ResetCount() {
        FilterCheck = 0;
    }

    function SubmitFilterAttendanceForm() {
        if (FilterCheck === 0) {
            $("#classAttendanceEdit").submit();
        }
        FilterCheck++;
    }
</script>
