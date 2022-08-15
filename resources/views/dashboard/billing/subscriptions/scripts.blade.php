<script type="text/javascript">
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if (Alert.length > 0) {
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }
        MakeSubscriptionsTable();
    });

    function MakeSubscriptionsTable() {
        let Table = $("#subscriptionsTable");
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
                    "url": "{{route('billing.subscriptions.load')}}",
                    "type": "POST"
                },
                'columns': [
                    {data: 'id'},
                    {data: 'parent', orderable: false},
                    {data: 'player', orderable: false},
                    {data: 'package', orderable: false},
                    {data: 'package_type', orderable: false},
                    {data: 'status', orderable: false},
                    {data: 'register_date', orderable: false},
                    {data: 'next_billing', orderable: false},
                    {data: 'action', orderable: false}
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function SuspendSubscription(e) {
        let SubscriptionId = $(e).attr('id').split('_')[1];
        $("#hiddenSubscriptionId").val(SubscriptionId);
        $("#suspendModal").modal('toggle');
    }

    function ConfirmSuspendSubscription(e) {
        $(e).attr('disabled', true);
        let SubscriptionId = $("#hiddenSubscriptionId").val();
        $.ajax({
            type: "post",
            url: "{{ route('billing.subscriptions.suspend') }}",
            data: { OrderId: SubscriptionId, SuspendReason : $("#suspendAccountReason").val() }
        }).done(function (data) {
            $(e).attr('disabled', false);
            if(!data) {
                $("#message-alert").show().html('Termination fee transaction failed');
            }
            $("#subscriptionsTable").DataTable().ajax.reload();
            $("#suspendModal").modal('toggle');
        });
    }

    function HoldSubscription(e) {
        let SubscriptionId = $(e).attr('id').split('_')[1];
        $("#hiddenHoldId").val(SubscriptionId);
        $("#holdModal").modal('toggle');
    }

    function ConfirmHoldSubscription(e) {
        $(e).attr('disabled', true);
        let SubscriptionId = $("#hiddenHoldId").val();
        $.ajax({
            type: "post",
            url: "{{ route('billing.subscriptions.hold') }}",
            data: { OrderId: SubscriptionId }
        }).done(function (data) {
            $(e).attr('disabled', false);
            if(!data) {
                $("#message-alert").show().html('Holding fee transaction failed');
            }
            $("#subscriptionsTable").DataTable().ajax.reload();
            $("#holdModal").modal('toggle');
        });
    }

    function CancelSubscription(e) {
        let SubscriptionId = $(e).attr('id').split('_')[1];
        $("#hiddenCancelSubscriptionId").val(SubscriptionId);
        $("#cancelModal").modal('toggle');
    }

    function ConfirmCancelSubscription(e) {
        $(e).attr('disabled', true);
        let SubscriptionId = $("#hiddenCancelSubscriptionId").val();
        $.ajax({
            type: "post",
            url: "{{ route('billing.subscriptions.cancel') }}",
            data: { OrderId: SubscriptionId, CancelReason : $("#cancelAccountReason").val() }
        }).done(function (data) {
            $(e).attr('disabled', false);
            if(!data) {
                $("#message-alert").show().html('Early Termination fee transaction failed');
            }
            $("#subscriptionsTable").DataTable().ajax.reload();
            $("#cancelModal").modal('toggle');
        });
    }

    function ActivateSubscription(e) {
        let SubscriptionId = $(e).attr('id').split('_')[1];
        $("#hiddenActivateId").val(SubscriptionId);
        $("#activateModal").modal('toggle');
    }

    function ConfirmActivateSubscription(e) {
        $(e).attr('disabled', true);
        let SubscriptionId = $("#hiddenActivateId").val();
        $.ajax({
            type: "post",
            url: "{{ route('billing.subscriptions.activate') }}",
            data: { OrderId: SubscriptionId }
        }).done(function (data) {
            $(e).attr('disabled', false);
            if(!data) {
                $("#message-alert").show().html('Reactivation fee transaction failed');
            }
            $("#subscriptionsTable").DataTable().ajax.reload();
            $("#activateModal").modal('toggle');
        });
    }

    function ViewSubscription(e) {
        let SubscriptionId = $(e).attr('id').split('_')[1];
        window.open('{{url('billing/memberships/view')}}' + '/' + window.btoa(SubscriptionId), '_self');
    }
</script>
