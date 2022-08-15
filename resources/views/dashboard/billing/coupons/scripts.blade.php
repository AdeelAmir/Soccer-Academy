<script type="text/javascript">
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if (Alert.length > 0) {
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }
        MakeCouponsTable();
    });

    function MakeCouponsTable() {
        let Table = $("#couponsTable");
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
                    "url": "{{route('billing.coupons.load')}}",
                    "type": "POST"
                },
                'columns': [
                    {data: 'id'},
                    {data: 'coupon_name'},
                    {data: 'coupon_code'},
                    {data: 'coupon_type'},
                    {data: 'coupon_limit'},
                    {data: 'coupon_apply'},
                    {data: 'coupon_rate'},
                    {data: 'action', orderable: false}
                ],
                'order': [0, 'desc'],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function Edit(e) {
        let Id = $(e).attr('id').split('_')[1];
        window.open('{{url('billing/coupons/edit')}}' + '/' + window.btoa(Id), '_self');
    }

    function Delete(e) {
        let Id = $(e).attr('id').split('_')[1];
        $("#deleteCouponId").val(Id);
        $("#deleteModal").modal('toggle');
    }
</script>