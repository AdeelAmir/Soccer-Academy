<script type="text/javascript">
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if (Alert.length > 0) {
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }
        MakeTransactionsTable();
    });

    function MakeTransactionsTable() {
        let OrderId = $("#hiddenSubscriptionId").val();
        let Table = $("#transactionsTable");
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
                    "url": "{{route('billing.transactions.load')}}",
                    "type": "POST",
                    "data": { OrderId : OrderId }
                },
                'columns': [
                    {data: 'id'},
                    {data: 'transaction_id', orderable: false},
                    {data: 'lead_number', orderable: false},
                    {data: 'bill_to'},
                    {data: 'total_amount'},
                    {data: 'amount_paid'},
                    {data: 'status'},
                    {data: 'comments', orderable: false},
                    {data: 'paid_date'}
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }
</script>