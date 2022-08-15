<script type="text/javascript">
    let DeletedDocuments = [];

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

        @if(isset($Announcement[0]))
          let datetime = new Date('{{$Announcement[0]->expiration}}');
          datetime = moment(datetime).format('MM/DD/YYYY h:mm a');
          $('#expiration_date_time').datetimepicker({
              sideBySide: true,
              showClose: true,
              date: datetime
          });
        @else
        $('#expiration_date_time').datetimepicker({
            sideBySide: true,
            showClose: true,
        });
        @endif

        MakeAnnouncementTable();
    });

    function MakeAnnouncementTable() {
        if ($("#announcementsTable").length) {
            $("#announcementsTable").DataTable({
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
                    "url": "{{route('announcements.all')}}",
                    "type": "POST",
                },
                'columns': [
                    {data: 'sr_no'},
                    {data: 'message', orderable: false},
                    {data: 'expiration', orderable: false},
                    {data: 'status', orderable: false},
                    {data: 'action', orderable: false},
                ],
                "drawCallback": function( settings ) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function activeAnnouncement(id) {
        let values = id.split('_');
        $.ajax({
            type: "post",
            url: "{{route('announcements.active')}}",
            data: {AnnouncementId: values[1]}
        }).done(function (data) {
            if (jQuery.trim(data) === 'Success') {
                $('#announcementsTable').DataTable().ajax.reload();
            } else {
                $('#announcementsTable').DataTable().ajax.reload();
            }
        });
    }

    function deactiveAnnouncement(id) {
        let values = id.split('_');
        $.ajax({
            type: "post",
            url: "{{route('announcements.deactive')}}",
            data: {AnnouncementId: values[1]}
        }).done(function (data) {
            if (jQuery.trim(data) === 'Success') {
                $('#announcementsTable').DataTable().ajax.reload();
            } else {
                $('#announcementsTable').DataTable().ajax.reload();
            }
        });
    }

    function deleteAnnouncement(e) {
        let id = e.split('_')[1];
        $("#deleteAnnouncementId").val(id);
        $("#deleteAnnouncementModal").modal('toggle');
    }

    function editAnnouncement(e) {
        let id = e.split('_')[1];
        window.open('{{url('announcement/edit')}}' + '/' + id, '_self');
    }
</script>
