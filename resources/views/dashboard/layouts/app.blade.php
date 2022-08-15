<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @include('dashboard.layouts.partials.head')
    <style type="text/css">
        .table > tbody > tr > td {
            vertical-align: middle;
        }
    </style>
</head>

<body class="page-body">
@include('dashboard.layouts.partials.upper-menu')
<div class="page-container">
    @include('dashboard.layouts.partials.sidebar')
    <div class="main-content">
        @include('dashboard.layouts.partials.navbar')
        @yield('content')
        @include('dashboard.layouts.partials.footer')
    </div>
</div>
@include('dashboard.layouts.partials.footer-scripts')
@include('dashboard.includes.recieverBroadcastModal')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    $(document).ready(function () {
        window.setInterval(function () {
            // Broadcast Notification
            let check = $("#recieverBroadcastUserId").val();
            if (check === '') {
                $.ajax({
                    type: "post",
                    url: "{{url('/broadcast/all')}}",
                    data: {}
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.Total === 1) {
                        $("#recieverBroadcastId").val(data.BroadcastId);
                        $("#recieverReadBroadcastId").val(data.ReadBroadcastId);
                        $("#recieverBroadcastUserId").val(data.RecieverId);
                        $("#recieverBroadcast_message").html('').html(data.Message);
                        $("#recieverBroadcastModal").modal('toggle');
                    }
                });
            }
        }, 2500);
    });

    function UpdateBroadcastReadStatus() {
        let broadcast_id = $("#recieverBroadcastId").val();
        let read_broadcast_id = $("#recieverReadBroadcastId").val();
        let broadcast_reciever_id = $("#recieverBroadcastUserId").val();
        $.ajax({
            type: "post",
            url: "{{route('broadcasts.status.update')}}",
            data: {
                BroadcastId: broadcast_id,
                ReadBroadcastId: read_broadcast_id,
                BroadcastRecieverId: broadcast_reciever_id
            }
        }).done(function (data) {
            if (jQuery.trim(data) === 'Success') {
                $("#recieverBroadcastId").val('');
                $("#recieverReadBroadcastId").val('');
                $("#recieverBroadcastUserId").val('');
                $("#recieverBroadcastModal").modal('toggle');
            } else {
                $("#recieverBroadcastId").val('');
                $("#recieverReadBroadcastId").val('');
                $("#recieverBroadcastUserId").val('');
                $("#recieverBroadcastModal").modal('toggle');
            }
        });
    }
    if ($("#faqAnswer").length) {
        ClassicEditor.create(document.querySelector('#faqAnswer'), {
            ckfinder: {
                uploadUrl: 'http://localhost/eliteempire/public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
            },
            link: {
                addTargetToExternalLinks: true
            },
        }).then(editor => {

        })
            .catch(error => {

            });
    }
    if ($("#faqAnswer1").length) {
        ClassicEditor.create(document.querySelector('#faqAnswer1'), {
            ckfinder: {
                uploadUrl: 'http://localhost/eliteempire/public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
            },
            link: {
                addTargetToExternalLinks: true
            },
        }).then(editor => {
            EditFaqAnswerEditor = editor;
        })
            .catch(error => {

            });
    }
    if ($("#add_article_details").length) {
        ClassicEditor.create(document.querySelector('#add_article_details'), {
            ckfinder: {
                uploadUrl: 'http://localhost/eliteempire/public/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
            },
            link: {
                addTargetToExternalLinks: true
            },
        }).then(editor => {

        })
            .catch(error => {

            });
    }


</script>
@stack('extended-scripts')
</body>
</html>