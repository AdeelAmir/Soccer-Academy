<script type="text/javascript">
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if (Alert.length > 0) {
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }

        // Edit User Page
        if ($("#EditPackagePage").length > 0) {
            $(".hide-data-repeater-btn").attr('disabled', true);
        }
        MakePackagesTable();
    });

    function MakePackagesTable() {
        let Table = $("#packagesTable");
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
                    "url": "{{route('packages.load')}}",
                    "type": "POST",
                },
                'columns': [
                    {data: 'created_at', bVisible: false},
                    {data: 'id'},
                    {data: 'title'},
                    {data: 'limit'},
                    {data: 'invitation'},
                    {data: 'start_date'},
                    {data: 'end_date'},
                    {data: 'action', orderable: false},
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function EditPackage(e) {
        let id = e.split('||')[1];
        window.open('{{url('billing/packages/edit/')}}' + '/' + btoa(id), '_self');
    }

    function checkConfirmation() {
      $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditPackage() {
      $("#title").prop('disabled', false);
      $("#limit").prop('disabled', false);
      $("#invitation").prop('disabled', false);
      $("#startDate").prop('disabled', false);
      $("#endDate").prop('disabled', false);
      $("#level").prop('disabled', false);
      $("#category").prop('disabled', false);
      $("#location").prop('disabled', false);
      $(".hide-data-repeater-btn").attr('disabled', false);
      $("#editPackageBtn").hide();
      $("#submitBtn").show();
      $("#editConfirmationModal").modal('toggle');
    }

    function DeletePackage(e) {
        let id = e.split('||')[1];
        $("#deletePackageId").val(id);
        $("#deletePackageModal").modal('toggle');
    }

    function SetClassItemCost() {
        let Class = $("#class option:selected");
        let Id = Class.val();
        let Registration = Class.attr('data-registration');
        let Monthly = Class.attr('data-monthly');
        $("#hiddenRegistrationFee").val(Registration);
        $("#hiddenMonthlyFee").val(Monthly);
        $("#hiddenClassCost").val(parseFloat(Registration) + parseFloat(Monthly));
    }

    function ShowFeesModal() {
        $("#feesModal").modal('toggle');
    }
</script>
