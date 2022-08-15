@extends('dashboard.layouts.app')
@section('content')
<style media="screen">
  .bootstrap-datetimepicker-widget{
    padding: 20px !important;
  }
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Announcements > New</h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2" style="float: right;"
                                onclick="window.location.href='{{route('announcements')}}';"><i
                                    class="fas fa-arrow-left"></i></button>
                    </div>
                    <div class="panel-body">
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

                        <form action="{{route('announcements.store')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" id="announcement_type" value="2"/>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="message">Message</label>
                                    <textarea name="message" id="message" class="form-control" rows="5" cols="80" required></textarea>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label for="dtpickerdemo" class="control-label">Expiration Date and Time:</label>
                                    <div class='input-group date' id='expiration_date_time'>
                                        <input type='text' class="form-control" name="expiration_date_time" autocomplete="off" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-2 text-center">
                                    <input type="submit" class="btn btn-primary " name="submitAddAnnouncementForm"
                                           id="submitAddAnnouncementForm" value="Save"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.announcements.scripts')
@endsection
