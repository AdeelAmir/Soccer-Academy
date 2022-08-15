@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        .d-none {
            display: none;
        }

        .w-0 {
            width: 0;
        }

        .error {
            font-size: 12px;
            color: red;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Profile</h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2" style="float: right;"
                                onclick="window.location.href='{{route('HomeRoute')}}';"><i
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
                        <form role="form" action="{{route('profile.update')}}" method="post"
                              enctype="multipart/form-data"
                              class="form-wizard validate userInfoForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group" style="align-items: center">
                                        <label class="control-label" for="firstName">First Name</label>
                                        <input class="form-control" name="firstName"
                                               id="firstName" data-validate="required"
                                               value="{{$UserDetails[0]->firstName}}"
                                               required
                                               placeholder="Your First Name"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="middleName">Middle Name</label>
                                        <input class="form-control" name="middleName"
                                               id="middleName"
                                               value="{{$UserDetails[0]->middleName}}"
                                               placeholder="Your Middle Name"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="lastName">Last Name</label>
                                        <input class="form-control" name="lastName"
                                               id="lastName" data-validate="required"
                                               value="{{$UserDetails[0]->lastName}}"
                                               required
                                               placeholder="Your Last Name"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="dob">Date of Birth</label>
                                        <input class="form-control datepicker" name="dob"
                                               id="dob" data-validate="required"
                                               required
                                               value="{{\Carbon\Carbon::parse($UserDetails[0]->dob)->format('m/d/Y')}}"
                                               autocomplete="off"
                                               onchange="SetUserCategory();"
                                               placeholder="MM/DD/YYYY"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="gender">Gender</label>
                                        <div class="mt-2">
                                            <label class="radio-inline">
                                                <input type="radio" name="gender" id="male_gender" value="Male" <?php if ($UserDetails[0]->gender == "Male") {
                                                    echo "checked";
                                                } ?>>Male
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="gender" id="female_gender" value="Female" <?php if ($UserDetails[0]->gender == "Female") {
                                                    echo "checked";
                                                } ?>>Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="profile_pic">Profile Picture</label>
                                        <input type="hidden"
                                               name="old_profile_pic"
                                               id="old_profile_pic"
                                               value="{{$UserDetails[0]->profile_pic}}"
                                               accept=".jpeg,.png,.jpg,.JPEG,.PNG,.JPG"/>
                                        <input type="file"
                                               name="profile_pic"
                                               id="profile_pic"
                                               value="profile_pic"
                                               accept=".jpeg,.png,.jpg,.JPEG,.PNG,.JPG"/>
                                    </div>
                                </div>

                                <div class="col-md-12 text-right">
                                    <button class="btn btn-primary mt-4" type="submit" name="submit">Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
