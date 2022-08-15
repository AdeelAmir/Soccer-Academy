@extends('dashboard.layouts.app')
@section('content')

    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            @if(\Illuminate\Support\Facades\Session::has('success'))
                <div class="alert alert-success" id="message-alert">
                    <button type="button" class="close" data-dismiss="alert"><span
                                aria-hidden="true">×</span> <span
                                class="sr-only">Close</span></button>
                    {{\Illuminate\Support\Facades\Session::get('success')}}
                </div>
            @elseif(\Illuminate\Support\Facades\Session::has('error'))
                <div class="alert alert-danger" id="message-alert">
                    {{\Illuminate\Support\Facades\Session::get('error')}}
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center mt-0 mb-5">UPDATE FREE CLASS</h3>
        </div>
    </div>

    {{-- Get Registered --}}
    <form action="{{route('dashboard.registration.update.lead')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="leadId" id="leadId" value="{{$LeadConversion[0]->lead_id}}" />

        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <div class="panel panel-default" id="leadRegistered">
                    <div class="panel-body">
                        <div class="custom-row mb-3">
                            <div class="custom-col-3 mt-2 freeClassField">
                                <div class="form-group">
                                    <label class="control-label" for="free_class">Free Class</label>
                                    <select class="form-control select2" name="free_class"
                                            id="free_class" onchange="getFreeClassDays(this.value);" required>
                                        <option value="">Select</option>
                                        @foreach($FreeClasses as $index => $class)
                                            <option value="{{$class->id}}">{{$class->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="custom-col-3 mt-2 freeClassField" style="display:none;">
                                <div class="form-group">
                                    <label class="control-label" for="free_class_date">Free Class
                                        Date</label>
                                    <input class="form-control free_class_date" name="free_class_date"
                                           id="free_class_date" autocomplete="off"
                                           placeholder="MM/DD/YYYY" disabled="disabled" required />
                                </div>
                            </div>

                            <div class="custom-col-3 mt-2 freeClassField" style="display:none;">
                                <div class="form-group">
                                    <label class="control-label" for="free_class_time">Free Class
                                        Time</label>
                                    <select class="form-control select2" name="free_class_time"
                                            id="free_class_time" disabled="disabled" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <input type="submit" class="btn btn-primary" value="Submit"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- Get Registered --}}

    <script>
        $(document).ready(function () {
            let Alert = $("#message-alert");
            if (Alert.length > 0) {
                setTimeout(function () {
                    Alert.slideUp();
                }, 10000);
            }
        });

        function getFreeClassDays(class_id) {
            $.ajax({
                type: "post",
                url: "{{route('leads.freeclass.days')}}",
                data: {
                    class_id: class_id,
                }
            }).done(function (data) {
                data = JSON.parse(data);
                $(".freeClassField").each(function f(i, obj) {
                    $(obj).show();
                });

                $('#free_class_time').html('').html('<option value="">Select</option>').select2();
                $('#free_class_time').val('').prop('disabled', true);
                if ($('#free_class_date').val() != ''){
                    $('#free_class_date').data('datepicker').remove();
                }
                $('#free_class_date').prop('disabled', false);
                $('.free_class_date').datepicker({
                    format: 'mm/dd/yyyy',
                    todayHighlight: 'FALSE',
                    autoclose: true,
                    startDate: new Date(),
                    daysOfWeekDisabled: data,
                }).on('changeDate', function(e) {
                    let class_id = $("#free_class option:selected").val();
                    let free_class_date = $("#free_class_date").val();
                    $.ajax({
                        type: "post",
                        url: "{{route('leads.freeclass.timing')}}",
                        data: {
                            class_id: class_id,
                            class_date: free_class_date,
                        }
                    }).done(function (data) {
                        let s = data;
                        s = s.replace(/\\n/g, "\\n")
                            .replace(/\\'/g, "\\'")
                            .replace(/\\"/g, '\\"')
                            .replace(/\\&/g, "\\&")
                            .replace(/\\r/g, "\\r")
                            .replace(/\\t/g, "\\t")
                            .replace(/\\b/g, "\\b")
                            .replace(/\\f/g, "\\f");
                        // remove non-printable and other non-valid JSON chars
                        s = s.replace(/[\u0000-\u0019]+/g, "");
                        let timing = JSON.parse(s);
                        $("#free_class_time").prop('disabled',false);
                        $("#free_class_time").html('').html(timing).select2();
                    });
                });
            });
        }
    </script>
@endsection