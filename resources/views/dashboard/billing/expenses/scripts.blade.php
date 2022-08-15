<script type="text/javascript">
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if (Alert.length > 0) {
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }
        MakeExpenseTable();
        MakeParentExpenseTable();
    });

    function DestroyDataTable() {
        let Table = $("#expensesTable");
        Table.DataTable().destroy();
    }

    function MakeExpenseTable() {
        let Table = $("#expensesTable");
        let StartDate = $("#expense_start_date").val();
        let EndDate = $("#expense_end_date").val();
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
                    "url": "{{route('billing.expenses.load')}}",
                    "type": "POST",
                    "data": {
                        "StartDate": StartDate,
                        "EndDate": EndDate,
                    }
                },
                'columns': [
                    {data: 'id'},
                    {data: 'description'},
                    {data: 'total'},
                    {data: 'expense_date'},
                    {data: 'vendor'},
                    {data: 'location'},
                    {data: 'note'},
                    {data: 'action', orderable: false},
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
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

    function EditExpense(e) {
        let id = e.split('_')[1];
        window.open('{{url('billing/expenses/edit/')}}' + '/' + btoa(id), '_self');
    }

    function DeleteExpense(e) {
        let id = e.split('_')[1];
        $("#deleteExpenseId").val(id);
        $("#deleteExpenseModal").modal('toggle');
    }

    function checkExpenseCurrency() {
        let Currency = $("#currency option:selected").val();
        if (Currency === "USD") {
            $("#_currencyNameBlock").hide();
            $("#_exchangeRateBlock").hide();
        } else if (Currency === "Others") {
            $("#_currencyNameBlock").show();
            $("#_exchangeRateBlock").show();
        }
    }

    // Edit Confirmation
    function checkConfirmation() {
      $("#editConfirmationModal").modal('toggle');
    }

    function ConfirmEditExpense() {
      $("#description").prop('disabled', false);
      $("#total").prop('disabled', false);
      $("#expenseDate").prop('disabled', false);
      $("#vendor").prop('disabled', false);
      $("#location").prop('disabled', false);
      $("#currency").prop('disabled', false);
      $("#other_currency_name").prop('disabled', false);
      $("#rate").prop('disabled', false);
      $("#notes").prop('disabled', false);
      $("#editExpenseBtn").hide();
      $("#submitEditExpenseForm").show();
      $("#editConfirmationModal").modal('toggle');
    }

    // PARENT DASHBOARD TABLE
    function DestroyParentExpenseDataTable() {
        let Table = $("#parentsAllExpensesTable");
        Table.DataTable().destroy();
    }

    function MakeParentExpenseTable() {
        let Table = $("#parentsAllExpensesTable");
        let StartDate = $("#expense_start_date").val();
        let EndDate = $("#expense_end_date").val();
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
                    "url": "{{route('dashboard.parent.expenses')}}",
                    "type": "POST",
                    "data": {
                        "StartDate": StartDate,
                        "EndDate": EndDate,
                    }
                },
                'columns': [
                    {data: 'sr_no', orderable: false},
                    {data: 'id', orderable: false},
                    {data: 'expense', orderable: false},
                    {data: 'amount', orderable: false},
                    {data: 'status', orderable: false},
                    {data: 'date', orderable: false},
                ],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }
</script>
