<script type="text/javascript">
    $(document).ready(function () {
        let Alert = $("#message-alert");
        if (Alert.length > 0) {
            setTimeout(function () {
                Alert.slideUp();
            }, 10000);
        }

        if ($("#dashboardPage").length > 0) {
            // Earning Line Graph
            EarningLineGraph();
            $(".earning-dropdown-item").click(function (e) {
                $("#earning-dropdown-value").html('').html(this.text + ' <span class="caret"></span>');
                LoadEarningLineGraphData(this.text);
            });
            // Earning,Expense Donut Graph
            EarningExpenseDonutGraph();
            // Expense Bar Graph
            FinanceBarGraph();
            // Player Category Donut Graph
            PlayerCategoryDonutGraph();
        }

        let default_pass_status = '<?= Auth::user()->default_pass_status; ?>';
        let RoleId = parseInt('<?= Auth::user()->role_id; ?>');

        $('#CompletePlayerProfile').on('hidden.bs.modal', function () {
            ResetUserDocumentStatus();
        });

        if(RoleId === 5 && '{{\App\Helpers\SiteHelper::CheckForUserDocumentStatus()}}' && '{{\App\Helpers\SiteHelper::CheckForPackagePurchase()}}') {
            /*Edit Profile Modal for Parent Only*/
            setTimeout(function () {
                $("#CompletePlayerProfile").modal('toggle');
            }, 5000);
        }

        if (parseInt(default_pass_status) === 0) {
            let user_id = '<?= Auth::id() ?>';
            if(RoleId !== 5) {
                /*Avoid Change Password for Parent*/
                setTimeout(function () {
                    $("#changePasswordUserId").val(user_id);
                    $("#changePasswordModal").modal('toggle');
                }, 5000);
            }

            // User Change Password
            $("form#changePasswordForm").submit(function (e) {
                // Check for Password Match
                let NewPassword = $("#newPassword").val();
                let ConfirmPassword = $("#confirmPassword").val();
                if (NewPassword === ConfirmPassword) {
                    $("#changePasswordError").hide();
                } else {
                    $("#changePasswordError").show();
                    e.preventDefault(e);
                }
            });
        }

        // Parent Dashboard Table
        if ($("#ParentDashoboardPage").length > 0) {
            MakeParentExpenseTable();
            MakeParentEvaluationReportTable();
        }

        // Coach Dashboard Table
        if ($("#CoachDashoboardPage").length > 0) {
            PlayerEvaluationReportDonutGraph();
            MakeCoachAllPlayerTable();
        }

        // Player Dashboard Page
        if($("#PlayerDashboardPage").length > 0) {
            MakePlayerEvaluationReportTable();
            PlayerAttendanceDonutGraph();
        }
    });

    /* Read Announcement - Start */
    function ReadAnnouncement() {
        let AnnouncementId = $("#announcement_id").val();
        $.ajax({
            type: "post",
            url: "{{route('announcements.read')}}",
            data: {AnnouncementId: AnnouncementId}
        }).done(function (data) {
            //
        });
    }

    /* Read Announcement - End */

    /* Earnings Graph - Start */
    function LoadEarningLineGraphData(Type) {
        $.ajax({
            type: "post",
            url: "{{ route('dashboard.graph.earnings') }}",
            data: {Type: Type}
        }).done(function (data) {
            data = JSON.parse(data);
            $("#MembershipAmounts").val(JSON.stringify(data[0]));
            $("#InvoicesAmounts").val(JSON.stringify(data[1]));
            $("#EarningAmount").val(JSON.stringify(data[2]));
            EarningLineGraph();
        });
    }

    function EarningLineGraph() {
        let MembershipAmounts = JSON.parse($("#MembershipAmounts").val());
        let InvoicesAmounts = JSON.parse($("#InvoicesAmounts").val());
        let EarningAmount = $("#EarningAmount").val();
        let options = {
            chart: {
                height: 220,
                type: "line",
                stacked: false,
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ["#FF1654", "#247BA0"],
            series: [
                {
                    name: "Membership",
                    data: MembershipAmounts
                },
                {
                    name: "Invoices",
                    data: InvoicesAmounts
                }
            ],
            stroke: {
                width: [4, 4]
            },
            plotOptions: {
                bar: {
                    columnWidth: "20%"
                }
            },
            xaxis: {
                title: {
                    text: "Month Date",
                    style: {
                        color: "#455560"
                    }
                },
                categories: ["1 - 5", "6 - 10", "11 - 15", "16 - 20", "21 - 25", "26 - 31"]
            },
            yaxis: [],
            tooltip: {
                /*shared: false,
                intersect: true,*/
                x: {
                    show: false
                },
                y: {
                    formatter: (value) => {
                        return value
                    },
                },
            },
            legend: {
                horizontalAlign: "left",
                offsetX: 40
            }
        };

        $("#totalMonthlyEarning").text('').text('($' + EarningAmount + ')');
        $("#earnings-chart").html('');
        let chart = new ApexCharts(document.querySelector("#earnings-chart"), options);
        chart.render();
    }

    /* Earnings Graph - End */

    /* Expense Graph - Start */
    function FinanceBarGraph() {
        let expense_title = $("#expense_title").val();
        let ExpenseAmounts = JSON.parse($("#ExpenseAmounts").val());
        var options = {
            series: [{
                name: 'Finance',
                data: ExpenseAmounts
            }],
            chart: {
                height: 338,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'center', // top, center, bottom
                    },
                }
            },
            dataLabels: {
                enabled: false,
                formatter: function (val) {
                    return "$" + val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },

            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                position: 'bottom',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: true,
                    formatter: function (val) {
                        return "$" + val;
                    }
                }

            },
            title: {
                text: expense_title,
                floating: true,
                offsetY: 0,
                align: 'center',
                style: {
                    color: '#444'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#finance-chart"), options);
        chart.render();
    }

    /* Expense Graph - End */

    /* Player Category Graph - Start */
    function PlayerCategoryDonutGraph() {
        let CategoryAmounts = JSON.parse($("#CategoryAmounts").val());
        let CategoryPlayerAmounts = JSON.parse($("#CategoryPlayerAmounts").val());
        var options = {
            series: CategoryPlayerAmounts,
            chart: {
                type: 'donut',
                height: 350
            },
            labels: CategoryAmounts,
            legend: {show: true},
            responsive: [{
                breakpoint: 480,
            }],
            dataLabels: {
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex]
                },
            },
            title: {
                text: "Player Category",
                floating: true,
                offsetY: 0,
                align: 'left',
                style: {
                    color: '#444'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#playercategory-chart"), options);
        chart.render();
    }

    /* Player Category Graph - End */

    /* Earning,Expense Graph - Start */
    function EarningExpenseDonutGraph() {
        let FinanceTypes = JSON.parse($("#FinanceTypes").val());
        let FinanceAmounts = JSON.parse($("#FinanceAmounts").val());
        var options = {
            series: FinanceAmounts,
            colors: ['#66b83a', '#d43735'],
            chart: {
                type: 'donut',
                height: 267
            },
            labels: FinanceTypes,
            legend: {show: true},
            responsive: [{
                breakpoint: 480,
            }],
            dataLabels: {
                formatter: function (val, opts) {
                    return "$" + opts.w.config.series[opts.seriesIndex]
                },
            },
            tooltip: {
                formatter: function (val, opts) {
                    return "$" + opts.w.config.series[opts.seriesIndex]
                },
            },
            title: {
                text: $("#earning_spending_title").val(),
                margin: 20,
                floating: true,
                offsetY: 0,
                align: 'left',
                style: {
                    color: '#444'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#earning-expense-chart"), options);
        chart.render();
    }

    /* Earning,Expense Graph - End */

    /* Event Calender - Start */
    document.addEventListener('DOMContentLoaded', function () {
        var initialTimeZone = 'local';
        var timeZoneSelectorEl = document.getElementById('time-zone-selector');
        var loadingEl = document.getElementById('loading');
        var calendarEl = document.getElementById('calendar');

        if($("#calendar").length > 0) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: initialTimeZone,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                initialDate: '2020-09-12',
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                selectable: true,
                dayMaxEvents: true, // allow "more" link when too many events
                loading: function (bool) {
                    if (bool) {
                        loadingEl.style.display = 'inline'; // show
                    } else {
                        loadingEl.style.display = 'none'; // hide
                    }
                },

                eventTimeFormat: {hour: 'numeric', minute: '2-digit', timeZoneName: 'short'},

                dateClick: function (arg) {
                    console.log('dateClick', calendar.formatIso(arg.date));
                },
                select: function (arg) {
                    console.log('select', calendar.formatIso(arg.start), calendar.formatIso(arg.end));
                }
            });

            calendar.render();
        }

        // load the list of available timezones, build the <select> options
        // it's HIGHLY recommended to use a different library for network requests, not this internal util func
        // FullCalendar.requestJson('GET', 'php/get-time-zones.php', {}, function(timeZones) {
        //
        //   timeZones.forEach(function(timeZone) {
        //     var optionEl;
        //
        //     if (timeZone !== 'UTC') { // UTC is already in the list
        //       optionEl = document.createElement('option');
        //       optionEl.value = timeZone;
        //       optionEl.innerText = timeZone;
        //       timeZoneSelectorEl.appendChild(optionEl);
        //     }
        //   });
        // }, function() {
        //   // TODO: handle error
        // });

        // when the timezone selector changes, dynamically change the calendar option
        // timeZoneSelectorEl.addEventListener('change', function() {
        //   calendar.setOption('timeZone', this.value);
        // });
    });
    /* Event Calender - End */

    // PARENT DASHBOARD TABLE
    function MakeParentExpenseTable() {
        let Table = $("#parentsAllExpensesTable");
        if (Table.length > 0) {
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 5,
                "lengthMenu": [
                    [5, 10, 20, 40],
                    ['5', '10', '20', '40']
                ],
                "ajax": {
                    "url": "{{route('dashboard.parent.expenses')}}",
                    "type": "POST",
                    "data": {
                        "StartDate": '',
                        "EndDate": '',
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

    function MakeParentEvaluationReportTable() {
        let Table = $("#parentsEvaluationReportTable");
        if (Table.length > 0) {
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 5,
                "lengthMenu": [
                    [5, 10, 20, 40],
                    ['5', '10', '20', '40']
                ],
                "ajax": {
                    "url": "{{route('dashboard.parent.evaluationreport')}}",
                    "type": "POST",
                },
                'columns': [
                    {data: 'sr_no', orderable: false},
                    {data: 'id', orderable: false},
                    {data: 'player', orderable: false},
                    {data: 'grade', orderable: false},
                    {data: 'date', orderable: false},
                ],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    function MakePlayerEvaluationReportTable() {
        let Table = $("#playerEvaluationReportTable");
        if (Table.length > 0) {
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 5,
                "lengthMenu": [
                    [5, 10, 20, 40],
                    ['5', '10', '20', '40']
                ],
                "ajax": {
                    "url": "{{route('dashboard.player.evaluationreport')}}",
                    "type": "POST",
                },
                'columns': [
                    {data: 'sr_no', orderable: false},
                    {data: 'id', orderable: false},
                    {data: 'player', orderable: false},
                    {data: 'grade', orderable: false},
                    {data: 'date', orderable: false},
                ],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    /* Player Evaluation Report Pie Chart - Start */
    function PlayerEvaluationReportDonutGraph() {
        let TotalCategories = JSON.parse($("#TotalCategories").val());
        let TotalCategoryPlayers = JSON.parse($("#TotalCategoryPlayers").val());
        var options = {
            series: TotalCategoryPlayers,
            chart: {
                type: 'donut',
                height: 418
            },
            labels: TotalCategories,
            legend: {show: true},
            responsive: [{
                breakpoint: 480,
            }],
            legend: {
                position: 'bottom',
            },
            dataLabels: {
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex]
                },
            },
            title: {
                text: "Category Players",
                floating: true,
                offsetY: 0,
                align: 'left',
                style: {
                    color: '#444'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#playerreport-chart"), options);
        chart.render();
    }

    function PlayerAttendanceDonutGraph() {
        let Labels = ['Present', 'Late', 'Absent'];
        let Values = [parseInt($("#totalPresentDays").val()), parseInt($("#totalLate").val()), parseInt($("#TotalAbsent").val())];
        let options = {
            series: Values,
            colors: ['#66b83a', '#fdd037', '#d43735'],
            chart: {
                type: 'donut',
                height: 267
            },
            labels: Labels,
            legend: {show: true},
            responsive: [{
                breakpoint: 480,
            }],
            dataLabels: {
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex]
                },
            },
            tooltip: {
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex]
                },
            },
            title: {
                text: 'Attendance Report',
                margin: 20,
                floating: true,
                offsetY: 0,
                align: 'left',
                style: {
                    color: '#444'
                }
            }
        };

        if($("#attendance-chart").length > 0) {
            let chart = new ApexCharts(document.querySelector("#attendance-chart"), options);
            chart.render();
        }
    }

    /* Player Evaluation Report Pie Chart - Start */

    /* Coach All Players Table - Start */
    function MakeCoachAllPlayerTable() {
        let Table = $("#coachAllPlayersTable");
        if (Table.length > 0) {
            Table.dataTable({
                "processing": true,
                "serverSide": true,
                "paging": true,
                "bPaginate": true,
                "ordering": true,
                "pageLength": 5,
                "lengthMenu": [
                    [5, 10, 20, 40],
                    ['5', '10', '20', '40']
                ],
                "ajax": {
                    "url": "{{route('dashboard.coach.allplayer')}}",
                    "type": "POST",
                },
                'columns': [
                    {data: 'sr_no', orderable: false},
                    {data: 'id', orderable: false},
                    {data: 'photo', orderable: false},
                    {data: 'player', orderable: false},
                    {data: 'gender', orderable: false},
                    {data: 'position', orderable: false},
                    {data: 'training_days', orderable: false},
                    {data: 'date', orderable: false},
                ],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }

    /* Coach All Players Table - End */

    function OpenPlayerProfile() {
        let UserPlayer = '';
        @if(\Illuminate\Support\Facades\Session::has('user_player'))
            UserPlayer = '{{\Illuminate\Support\Facades\Session::get('user_player')}}';
        @endif
        let Link = '{{url('users/edit')}}' + '/' + window.btoa(UserPlayer);
        ResetUserDocumentStatus(Link);
    }

    function ResetUserDocumentStatus(Link = '') {
        $.ajax({
            type: "post",
            url: "{{ route('users.documents.status.reset') }}",
            data: {  }
        }).done(function (data) {
            if(Link !== '') {
                window.location.href = Link;
            }
        });
    }

</script>
