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

        // Edit Lead Page
        if ($("#EditLeadPage").length > 0) {
            $(".hide-data-repeater-btn").attr('disabled', true);
            $('.free_class_date').datepicker({
                format: 'mm/dd/yyyy',
                todayHighlight: 'FALSE',
                autoclose: true,
                startDate: new Date(),
                daysOfWeekDisabled: DaysExcluded,
            }).on('changeDate', function(e) {
                let class_id = $("#free_class option:selected").val();
                let free_class_date = $("#free_class_date").val();
                $.ajax({
                    type: "post",
                    url: "{{route('leads.freeclass.timing')}}",
                    data: {
                        class_id: class_id,
                        class_date: free_class_date,
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
                    let timing = JSON.parse(s);
                    $("#free_class_time").prop('disabled',false);
                    $("#free_class_time").html('').html(timing).select2();
                });
            });
        }

        $('.collapse').collapse();
        MakeLeadsTable();
        MakeLeadCommentsTable();
    });

    function getFreeClassDays(class_id) {
        $.ajax({
            type: "post",
            url: "{{route('leads.freeclass.days')}}",
            data: {
                class_id: class_id,
            }
        }).done(function (data) {
            data = JSON.parse(data);
            $('#free_class_date').data('datepicker').remove();
            $('#free_class_date').val('');
            $('#free_class_time').html('').html('<option value="">Select</option>').select2();
            $('.free_class_date').datepicker({
                format: 'mm/dd/yyyy',
                todayHighlight: 'FALSE',
                autoclose: true,
                startDate: new Date(),
                daysOfWeekDisabled: data,
            }).on('changeDate', function(e) {
                let class_id = $("#free_class option:selected").val();
                let free_class_date = $("#free_class_date").val();
                $.ajax({
                    type: "post",
                    url: "{{route('leads.freeclass.timing')}}",
                    data: {
                        class_id: class_id,
                        class_date: free_class_date,
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
                    let timing = JSON.parse(s);
                    $("#free_class_time").prop('disabled',false);
                    $("#free_class_time").html('').html(timing).select2();
                });
            });
        });
    }

    function LoadStateCountyCity() {
        let state = '';
        if ($("#state").length) {
            state = $("#state option:selected").val();
        }
        if ($("#citySection").length) {
            $("#citySection").show();
        }
        LoadCities(state);
    }

    function limitKeypress(event, value, maxLength) {
        if (value !== undefined && value.toString().length >= maxLength) {
            event.preventDefault();
        }
    }

    function limitZipCodeCheck() {
        let value = $('#zipcode').val();
        if (value.toString().length < 5) {
            $('#zipcode').focus();
        }
    }

    function LoadCities(state) {
        $.ajax({
            type: "post",
            url: "{{route('common.load.cities')}}",
            data: {State: state}
        }).done(function (data) {
            data = JSON.parse(data);
            if ($("#city").length > 0) {
                $("#city").html('').html(data).select2();
            }
        });
    }

    $('input[type=radio][name=getregister_or_schedulefreeclass]').change(function() {
        if (parseInt(this.value) === 1) {
            $(".freeClassField").hide();
        }
        else if (parseInt(this.value) === 2) {
            $(".freeClassField").show();
        }
    });

    function ShowParentPhone2Field() {
      $("#ParentPhoneNumber2").show();
    }

    function HideParentPhone2Field() {
      $("#parentPhone2").val('');
      $("#ParentPhoneNumber2").hide();
    }

    function ShowPlayerInformation() {
        if (($('#parentFirstName').val() || $('#parentLastName').val()) && $('#parentPhone').val()) {
          $("#leadParentsInformation").hide();
          $("#leadScheduleFreeClass").hide();
          $("#leadGetRegistered").hide();
          $("#leadPlayersInformation").show();
          $(".step3").removeClass("complete");
          $(".step3").addClass("disabled");
          $(".step2").removeClass("disabled");
          $(".step2").addClass("complete");
          $(window).scrollTop(0);
        }
        else {
          // First Name
          if ($('#parentFirstName').val()) {
              $('#parent_f_name').hide();
          }
          else {
              $('#parent_f_name').hide();
              $("#parentFirstName").keyup(function(){
                  $('#parent_f_name').hide();
              });
              if($('#parentLastName').val() === '')
              {
                $('#parent_f_name').show();
                $("#parent_f_name").html("First Name or Last Name is required!").css("color","red");
              }
          }
          // Last Name
          if ($('#parentLastName').val())
          {
              $('#parent_l_name').hide();
          }
          else
          {
              $('#parent_l_name').hide();
              $("#parentLastName").keyup(function(){
                  $('#parent_l_name').hide();
              });
              if ($('#parentFirstName').val() === '') {
                $('#parent_l_name').show();
                $("#parent_l_name").html("First Name or Last Name is required!").css("color","red");
              }
          }
          // Phone Number 1
          if ($('#parentPhone').val() !== '')
          {
              $('#parent_phone1').hide();
          }
          else
          {
              $("#parentPhone").keyup(function(){
                  $('#parent_phone1').hide();
              });
              $('#parent_phone1').show();
              $("#parent_phone1").html("Phone Number 1 is required!").css("color","red");
          }
        }
    }

    function ShowParentInformation() {
        $("#leadPlayersInformation").hide();
        $("#leadParentsInformation").show();
        $(".step2").removeClass("complete");
        $(".step2").addClass("disabled");
        $(window).scrollTop(0);
    }

    function ShowScheduleFreeClass() {
        $("#leadPlayersInformation").hide();
        $("#leadGetRegistered").hide();
        $("#leadScheduleFreeClass").show();
        $(".step4").removeClass("complete");
        $(".step4").addClass("disabled");
        $(".step3").removeClass("disabled");
        $(".step3").addClass("complete");
        $(window).scrollTop(0);
    }

    function ShowGetRegistered() {
        $("#leadScheduleFreeClass").hide();
        $("#leadGetRegistered").show();
        $(".step4").removeClass("disabled");
        $(".step4").addClass("complete");
        $(window).scrollTop(0);
    }

    function getFreeClassTiming(class_id) {
        console.log(class_id);
        $.ajax({
            type: "post",
            url: "{{route('leads.freeclass.timing')}}",
            data: {
                class_id: class_id,
            }
        }).done(function (data) {
            console.log(data);
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
            let timing = JSON.parse(s);
            $("#free_class_time").html('').html(timing);
        });
    }

    function checkLeadLocation(location) {
        if (parseInt(location) === -1){
            $("#LocationZipCodeBlock").show();
        } else {
            $("#LocationZipCodeBlock").hide();
        }
    }

    function AutoSaveLead() {
        $.ajax({
            type: "post",
            url: "{{route('leads.store')}}",
            data: {
                LeadId: $("#lead_id").val(),
                ParentFirstName: $("#parentFirstName").val(),
                ParentLastName: $("#parentLastName").val(),
                ParentPhone1: $("#parentPhone").val(),
                ParentPhone2: $("#parentPhone2").val(),
                ParentEmail: $("#parentEmail").val(),
                State: $("#state option:selected").val(),
                City: $("#city option:selected").val(),
                Street: $("#street").val(),
                Zipcode: $("#zipcode").val(),
                Message: $("#message").val(),
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.status === 'success') {
                $("#lead_id").val(data.lead_id);
            }
        });
    }

    /* Leads Table - Start */
    function MakeLeadsTable() {
        let Table = $("#leadsTable");
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
                    "url": "{{route('leads.load')}}",
                    "type": "POST",
                },
                'columns': [
                    {data: 'created_at', bVisible: false},
                    {data: 'checkbox', orderable: false},
                    {data: 'id', orderable: false},
                    {data: 'affiliate', orderable: false},
                    {data: 'parent', orderable: false},
                    {data: 'status', orderable: false},
                    {data: 'action', orderable: false},
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            }).on('page.dt', function () {
                $("#allLeadsCheckBox").prop('checked', false);
                // $("#deleteAllUsersBtn").hide();
            }).on('length.dt', function (e, settings, len) {
                $("#allLeadsCheckBox").prop('checked', false);
                // $("#deleteAllUsersBtn").hide();
            }).on('draw.dt', function () {
                $(".allLeadsCheckBox").addClass('d-none');
                $(".allLeadsActionCheckBoxColumn").addClass('w-0');
            });
        }
    }
    /* Leads Table - End */

    let leadActionCheckboxCounter = 0;
    function HandleLeadAction() {
      if (leadActionCheckboxCounter === 0) {
        $(".allLeadsCheckBox").removeClass('d-none');
        $(".allLeadsActionCheckBoxColumn").removeClass('w-0');
        $(".allLeadsActionCheckBoxColumn").attr('style', 'padding', '10px');
        $("#deleteAllLeadsBtn").show();
        leadActionCheckboxCounter = 1;
      } else {
        $(".allLeadsCheckBox").addClass('d-none');
        $(".allLeadsActionCheckBoxColumn").addClass('w-0');
        $(".allLeadsActionCheckBoxColumn").attr('style', 'padding', '0');
        $("#deleteAllLeadsBtn").hide();
        leadActionCheckboxCounter = 0;
      }
    }

    function CheckAllLeadRecords(e) {
        let Status = $(e).prop('checked');
        if(Status){
            /*check all*/
            $(".checkAllBox").each(function (i, obj) {
                $(obj).prop('checked', true);
            });
            $("#deleteAllLeadsBtn").show();
        }
        else{
            /*un check all*/
            $(".checkAllBox").each(function (i, obj) {
                $(obj).prop('checked', false);
            });
            $("#deleteAllLeadsBtn").hide();
        }
    }

    function CheckIndividualLeadCheckbox() {
        let count = 0;
        $(".checkAllBox").each(function (i, obj) {
            if($(obj).prop('checked')){
                count++;
            }
        });
        if(count === 0){
            /*Not Selected*/
            $("#deleteAllLeadsBtn").hide();
        }
        else{
            /*Some Selected*/
            $("#deleteAllLeadsBtn").show();
        }
    }

    function DeleteMultipleLeads() {
        $("#deleteLeadModal").modal('toggle');
        let deleteSelectedLeadsFormUrl = $("#deleteSelectedLeadsFormUrl").val();
        $('#leadsForm').attr('action', deleteSelectedLeadsFormUrl);
    }

    function showLeadUpdateStatus(id) {
        let values = id.split("_");
        $("#updateStatus_leadId").val(values[1]);
        $("#leadUpdateStatusModal").modal("toggle");
    }

    function HideFields() {
        $("#followup_error").html('');
        $("#attended_class_error").html('');

        $("#_followUpBlock").hide();
        $("#_attendedClassBlock").hide();
    }

    function checkLeadStatus(status) {
        HideFields();
        if (status === '3') {
           $("#_followUpBlock").show();
        } else if (status === '7') {
           $("#_attendedClassBlock").show();
        }
    }

    function UpdateLeadStatus() {
        let lead_status = $("#lead_status option:selected").val();
        if (lead_status === '3' && $("#followUpTime").val() === '') {
            $("#followup_error").html('').html('Follow up time is not selected!');
            return;
        } else if (lead_status === '7' && $("#attendedClassNote").val() === '') {
            $("#attended_class_error").html('').html('Reason is missing!');
            return;
        } else {
            $("#followup_error").html('');
            $("#attended_class_error").html('');
            // AJAX TO UPDATE LEAD STATUS
            $.ajax({
                type: "post",
                url: "{{route('leads.update.status')}}",
                data: {
                    lead_id: $("#updateStatus_leadId").val(),
                    lead_status: $("#lead_status option:selected").val(),
                    attended_class_note: $("#attendedClassNote").val(),
                    followup_time: $("#followUpTime").val(),
                }
            }).done(function (data) {
                if (data === 'Success') {
                    $("#leadUpdateStatusModal").modal("toggle");
                    $('#leadsTable').DataTable().ajax.reload();
                } else {
                    alert("An unhandled exception occured");
                    $("#leadUpdateStatusModal").modal("toggle");
                }
            });
        }
    }

    function EditLead(id){
        let values = id.split("||");
        let url = '<?= url("leads/edit/"); ?>' + "/" + values[1];
        window.location.href = url;
    }

    function checkConfirmation() {
        $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditLead() {
        $(".enableEdit").prop('disabled', false);
        $(".hide-data-repeater-btn").attr('disabled', false);
        $("#editLeadBtn").hide();
        $("#submitLeadFormBtn").show();
        $("#editConfirmationModal").modal('toggle');
    }

    function showLeadComments(id) {
        let values = id.split('||')[1];
        $("#lead_comments_id").val(values);
        $("#leadCommentsModal").modal('toggle');
        MakeDashboardLeadCommentsTable();
    }

    function MakeDashboardLeadCommentsTable()
    {
        let Table = $("#lead_comments_table");
        if (Table.length > 0) {
            Table.DataTable().clear().destroy();
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
                    "url": "{{route('leads.comments.load')}}",
                    "type": "POST",
                    "data": {LeadId : $("#lead_comments_id").val()}
                },
                'columns': [
                    {data: 'created_at', bVisible: false},
                    {data: 'id', orderable: false},
                    {data: 'user_id', orderable: false},
                    {data: 'history_note', orderable: false},
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function MakeLeadCommentsTable()
    {
        let Table = $("#editlead_comments_table");
        if (Table.length > 0) {
            Table.DataTable().clear().destroy();
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
                    "url": "{{route('leads.comments.load')}}",
                    "type": "POST",
                    "data": {LeadId : $("#lead_id").val()}
                },
                'columns': [
                    {data: 'created_at', bVisible: false},
                    {data: 'id', orderable: false},
                    {data: 'user_id', orderable: false},
                    {data: 'history_note', orderable: false},
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function SaveDashboardLeadComment()
    {
        if ($("#_lead_comment").val() === '') {
            $("#_lead_comment_error").show();
            return;
        }
        $.ajax({
            type: "post",
            url: "{{route('leads.comments.store')}}",
            data: {
                LeadId: $("#lead_comments_id").val(),
                Comment: $("#_lead_comment").val(),
            }
        }).done(function (data) {
            if (data === 'Success') {
                $("#_lead_comment").val('');
                $("#_lead_comment_error").hide();
                MakeDashboardLeadCommentsTable();
            } else {
                alert("An unhandled exception occured");
                $("#leadCommentsModal").modal("toggle");
            }
        });
    }

    function SaveEditLeadComment()
    {
        if ($("#lead_comment").val() === '') {
            $("#lead_comment_error").show();
            return;
        }
        $.ajax({
            type: "post",
            url: "{{route('leads.comments.store')}}",
            data: {
                LeadId: $("#lead_id").val(),
                Comment: $("#lead_comment").val(),
            }
        }).done(function (data) {
            if (data === 'Success') {
                $("#lead_comment").val('');
                $("#lead_comment_error").hide();
                MakeLeadCommentsTable();
            } else {
                alert("An unhandled exception occured");
                $("#leadCommentsModal").modal("toggle");
            }
        });
    }
</script>
