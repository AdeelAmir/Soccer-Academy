<script type="text/javascript">
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if(Alert.length > 0){
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }
        MakeLevelsTable();
    });

    function MakeLevelsTable() {
        let Table = $("#levelsTable");
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
                    "url": "{{route('configuration.level.load')}}",
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

    function AddLevel() {
        $("#addLevelModal").modal('toggle');
    }

    function EditLevel(id) {
        let Values = id.split('||');
        $("#editLevelId").val(Values[1]);
        $("#editLevelTitle").val(atob(Values[2]));
        $("#editLevelPrice").val(atob(Values[3]));
        $("#editLevelDescription").val(atob(Values[4]));
        $("#editLevelSymbol").val(atob(Values[5]));

        // Disabled fields
        $("#editLevelTitle").prop('disabled', true);
        $("#editLevelSymbol").prop('disabled', true);
        $("#editLevelDescription").prop('disabled', true);
        $("#submitEditLevelForm").hide();
        $("#editLevelBtn").show();

        $("#editLevelModal").modal('toggle');
    }

    function DeleteLevel(id) {
        let Values = id.split('||');
        $("#deleteLevelId").val(Values[1]);
        $("#deleteLevelTitle").text("'" + atob(Values[2]) + "'");
        $("#deleteLevelModal").modal('toggle');
    }

    // Edit Confirmation
    function checkConfirmation() {
      $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditLevel() {
      $("#editLevelTitle").prop('disabled', false);
      $("#editLevelSymbol").prop('disabled', false);
      $("#editLevelDescription").prop('disabled', false);
      $("#editLevelBtn").hide();
      $("#submitEditLevelForm").show();
      $("#editConfirmationModal").modal('toggle');
    }
</script>
