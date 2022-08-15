<script type="text/javascript">
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if(Alert.length > 0){
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }
        MakeCategoriesTable();
    });

    function MakeCategoriesTable() {
        let Table = $("#categoriesTable");
        if(Table.length > 0){
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "ajax": {
                    "url": "{{route('configuration.categories.load')}}",
                    "type": "POST"
                },
                'columns': [
                    { data: 'id' },
                    { data: 'title' },
                    { data: 'symbol' },
                    { data: 'start_age' },
                    { data: 'status' },
                    { data: 'action', orderable: false },
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function AddCategory() {
        $("#addCategoryModal").modal('toggle');
    }

    function EditCategory(id) {
        let Values = id.split('||');
        $("#editCategoryId").val(Values[1]);
        $("#editCategoryTitle").val(atob(Values[2]));
        $("#editCategorySymbol").val(atob(Values[3]));
        $("#editCategoryDescription").val(atob(Values[4]));
        $("#editCategoryStartAge").val(atob(Values[5]));
        $("#editCategoryEndAge").val(atob(Values[6]));

        $("#editCategoryTitle").prop('disabled', true);
        $("#editCategorySymbol").prop('disabled', true);
        $("#editCategoryStartAge").prop('disabled', true);
        $("#editCategoryEndAge").prop('disabled', true);
        $("#editCategoryDescription").prop('disabled', true);
        $("#submitEditCategoryForm").hide();
        $("#editCategoryBtn").show();

        $("#editCategoryModal").modal('toggle');
    }

    function DeleteCategory(id) {
        let Values = id.split('||');
        $("#deleteCategoryId").val(Values[1]);
        $("#deleteCategoryTitle").text("'" + atob(Values[2]) + "'");
        $("#deleteCategoryModal").modal('toggle');
    }

    function ChangeCategoryStatus(Checked, id) {
        $.ajax({
            type: "post",
            url: "{{route('configuration.categories.update.status')}}",
            data: { Checked : Checked, id : id }
        }).done(function (data) {
            $('#categoriesTable').DataTable().ajax.reload();
        });
    }

    // Edit Confirmation
    function checkConfirmation() {
      $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditCategory() {
      $("#editCategoryTitle").prop('disabled', false);
      $("#editCategorySymbol").prop('disabled', false);
      $("#editCategoryStartAge").prop('disabled', false);
      $("#editCategoryEndAge").prop('disabled', false);
      $("#editCategoryDescription").prop('disabled', false);
      $("#editCategoryBtn").hide();
      $("#submitEditCategoryForm").show();
      $("#editConfirmationModal").modal('toggle');
    }
</script>
