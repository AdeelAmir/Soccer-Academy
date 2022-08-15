<script type="text/javascript">
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if(Alert.length > 0){
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }
        MakePositionsTable();
    });

    function MakePositionsTable() {
        let Table = $("#positionsTable");
        if(Table.length > 0){
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
                    "url": "{{route('configuration.player-position.load')}}",
                    "type": "POST"
                },
                'columns': [
                    { data: 'id' },
                    { data: 'title' },
                    { data: 'symbol' },
                    { data: 'action', orderable: false },
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function AddPosition() {
        $("#addPositionModal").modal('toggle');
    }

    function EditPosition(id) {
        let Values = id.split('||');
        $("#editPositionId").val(Values[1]);
        $("#editPositionTitle").val(atob(Values[2]));
        $("#editPositionSymbol").val(atob(Values[3]));
        $("#editPositionDescription").val(atob(Values[4]));

        $("#editPositionTitle").prop('disabled', true);
        $("#editPositionSymbol").prop('disabled', true);
        $("#editPositionDescription").prop('disabled', true);
        $("#submitEditPositionForm").hide();
        $("#editPositionBtn").show();

        $("#editPositionModal").modal('toggle');
    }

    function DeletePosition(id) {
        let Values = id.split('||');
        $("#deletePositionId").val(Values[1]);
        $("#deletePositionTitle").text("'" + atob(Values[2]) + "'");
        $("#deletePositionModal").modal('toggle');
    }

    // Edit Confirmation
    function checkConfirmation() {
      $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditLevel() {
      $("#editPositionTitle").prop('disabled', false);
      $("#editPositionSymbol").prop('disabled', false);
      $("#editPositionDescription").prop('disabled', false);
      $("#editPositionBtn").hide();
      $("#submitEditPositionForm").show();
      $("#editConfirmationModal").modal('toggle');
    }
</script>
