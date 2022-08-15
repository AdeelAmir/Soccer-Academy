@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        .collapsible {
            background-color: #104090;
            color: white;
            cursor: pointer;
            padding: 10px;
            width: 96%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            margin-left: 1rem;
        }

        .active1, .collapsible:hover {
            background-color: #104090;
        }

        .collapsible:after {
            content: '\002B';
            color: white;
            font-weight: bold;
            float: right;
            margin-left: 5px;
        }

        .active1:after {
            content: "\2212";
        }

        .content {
            padding: 0 18px;
            max-height: 1000px !important;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }

        #upload_image_box {
            width: 70%;
            margin-right: 15%;
            height: 146px;
            background-clip: padding-box;
            border-radius: 5px;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .error-msg {
            font-size: 12px;
            color: red;
            display: none;
        }
        @media (min-width: 992px){
            .responsive {

            }
        }


        @media (max-width: 767px) {
            .responsive {
                overflow-x:auto;
            }
        }
    </style>
    <div class="container-fluid" id="EditUserPage">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">
                            @if($UserRole == 5)
                                Family > <span class="text-primary">View</span>
                            @else
                                Users > <span class="text-primary">View</span>
                            @endif
                        </h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2" style="float: right;"
                                onclick="window.location.href='{{route('users')}}';"><i
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

                        <form role="form" action="{{route('users.update')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="hiddenUserId" value="{{$User[0]->id}}"/>
                            <input type="hidden" name="documentsDeleted" id="documentsDeleted" value=""/>
                            <input type="hidden" name="old_profile_pic" value="{{$UserDetails[0]->profile_pic}}"/>
                            <div class="col-md-12">

                                <!-- User Information - Start -->
                                <button type="button" class="collapsible">User Information</button>
                                <div class="content">
                                    <div class="custom-row mt-3 mb-3">
                                        @if($UserRole == 5)
                                            <input type="hidden" name="role" id="role" value="{{$User[0]->role_id}}"/>
                                        @else
                                            <div class="custom-col-4">
                                                <div class="form-group">
                                                    <label class="control-label" for="role">Role</label>
                                                    <select class="form-control" name="role"
                                                            required
                                                            onchange="CheckEditUserRole(this);"
                                                            id="role"
                                                            disabled>
                                                        <option value="">Select Role</option>
                                                        @foreach($Roles as $index => $item)
                                                            <option value="{{$item->id}}" <?php if ($User[0]->role_id == $item->id) {
                                                                echo 'selected';
                                                            } ?>>{{$item->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="firstName">First Name</label>
                                                <input class="form-control" name="firstName"
                                                       id="firstName"
                                                       required
                                                       disabled
                                                       value="{{$UserDetails[0]->firstName}}"
                                                       placeholder="Your First Name"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="middleName">Middle Name</label>
                                                <input class="form-control" name="middleName"
                                                       id="middleName"
                                                       value="{{$UserDetails[0]->middleName}}"
                                                       disabled
                                                       placeholder="Your Middle Name"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="lastName">Last Name</label>
                                                <input class="form-control" name="lastName"
                                                       id="lastName"
                                                       required
                                                       value="{{$UserDetails[0]->lastName}}"
                                                       disabled
                                                       placeholder="Your Last Name"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="dob">Date of Birth</label>
                                                <input class="form-control datepicker" name="dob"
                                                       id="dob"
                                                       required
                                                       onblur="SetUserCategory();"
                                                       value="{{\Carbon\Carbon::parse($UserDetails[0]->dob)->format('m/d/Y')}}"
                                                       disabled
                                                       placeholder="MM/DD/YYYY"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="dob">Gender</label>
                                                <div class="mt-2">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="gender" id="male_gender"
                                                               value="Male" <?php if ($UserDetails[0]->gender == "Male") {
                                                            echo 'checked';
                                                        } ?>>Male
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="gender" id="female_gender"
                                                               value="Female" <?php if ($UserDetails[0]->gender == "Female") {
                                                            echo 'checked';
                                                        } ?>>Female
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 managerRequiredFields" <?php if ($User[0]->role_id != 3) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="managerLocations">Manager
                                                    Locations</label>
                                                <select class="form-control select2" name="managerLocations[]"
                                                        id="managerLocations"
                                                        disabled
                                                        <?php if ($User[0]->role_id == 3) {
                                                            echo 'required';
                                                        } ?>
                                                        multiple>
                                                    <?php
                                                    $_Locations = array();
                                                    if ($UserDetails[0]->managerLocations != null) {
                                                        $_Locations = explode(',', $UserDetails[0]->managerLocations);
                                                    }
                                                    ?>
                                                    @foreach($Locations as $index => $item)
                                                        <option value="{{$item->id}}" <?php if (in_array($item->id, $_Locations)) {
                                                            echo 'selected';
                                                        } ?>>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 coachRequiredFields" <?php if ($User[0]->role_id != 4) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="coachLevels">Levels</label>
                                                <select class="form-control select2" name="coachLevels[]"
                                                        id="coachLevels"
                                                        <?php if ($User[0]->role_id == 4) {
                                                            echo 'required';
                                                        } ?>
                                                        disabled
                                                        multiple>
                                                    <?php
                                                    $_Levels = array();
                                                    if ($UserDetails[0]->coachLevels != null) {
                                                        $_Levels = explode(',', $UserDetails[0]->coachLevels);
                                                    }
                                                    ?>
                                                    @foreach($Levels as $index => $item)
                                                        <option value="{{$item->id}}" <?php if (in_array($item->id, $_Levels)) {
                                                            echo 'selected';
                                                        } ?>>{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 coachRequiredFields" <?php if ($User[0]->role_id != 4) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="coachCategories">Categories</label>
                                                <select class="form-control select2" name="coachCategories[]"
                                                        id="coachCategories"
                                                        <?php if ($User[0]->role_id == 4) {
                                                            echo 'required';
                                                        } ?>
                                                        disabled
                                                        multiple>
                                                    <?php
                                                    $_Categories = array();
                                                    if ($UserDetails[0]->coachCategories != null) {
                                                        $_Categories = explode(',', $UserDetails[0]->coachCategories);
                                                    }
                                                    ?>
                                                    @foreach($Categories as $index => $item)
                                                        <option value="{{$item->id}}" <?php if (in_array($item->id, $_Categories)) {
                                                            echo 'selected';
                                                        } ?>>{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 coachRequiredFields" <?php if ($User[0]->role_id != 4) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="coachLocations">Locations</label>
                                                <select class="form-control select2" name="coachLocations[]"
                                                        <?php if ($User[0]->role_id == 4) {
                                                            echo 'required';
                                                        } ?>
                                                        id="coachLocations"
                                                        disabled
                                                        multiple>
                                                    <?php
                                                    $_Locations = array();
                                                    if ($UserDetails[0]->coachLocations != null) {
                                                        $_Locations = explode(',', $UserDetails[0]->coachLocations);
                                                    }
                                                    ?>
                                                    @foreach($Locations as $index => $item)
                                                        <option value="{{$item->id}}" <?php if (in_array($item->id, $_Locations)) {
                                                            echo 'selected';
                                                        } ?>>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 parentRequiredFields" <?php if ($User[0]->role_id != 5) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="parentProfession">Occupation</label>
                                                <input type="text" class="form-control" name="parentProfession"
                                                       id="parentProfession" placeholder="Occupation" maxlength="100"
                                                       disabled
                                                       value="{{$UserDetails[0]->parent_profession}}">
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label" for="athletesHeightFt">Height
                                                            (ft)</label>
                                                        <input type="number" step="any" class="form-control"
                                                               name="athletesHeightFt"
                                                               value="{{$UserDetails[0]->athletesHeightFt}}"
                                                               disabled
                                                               id="athletesHeightFt" placeholder="feet" min="0"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label" for="athletesHeightInches">Height
                                                            (in)</label>
                                                        <input type="number" step="any" class="form-control"
                                                               name="athletesHeightInches"
                                                               value="{{$UserDetails[0]->athletesHeightInches}}"
                                                               disabled
                                                               id="athletesHeightInches" placeholder="inches" min="0"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesWeight">Weight</label>
                                                <input type="number" step="any" class="form-control"
                                                       name="athletesWeight"
                                                       value="{{$UserDetails[0]->athletesWeight}}"
                                                       disabled
                                                       id="athletesWeight" placeholder="Weight" min="0"/>
                                            </div>
                                        </div>

                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?> <?php if ($UserRole == 5) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesLevel">Level</label>
                                                <select class="form-control select2" name="athletesLevel"
                                                        disabled
                                                        <?php if ($User[0]->role_id == 6) {
                                                            echo 'required';
                                                        } ?>
                                                        id="athletesLevel">
                                                    <option value="">Select Level</option>
                                                    @foreach($Levels as $index => $item)
                                                        <option value="{{$item->id}}" <?php if ($UserDetails[0]->athletesLevel == $item->id) {
                                                            echo 'selected';
                                                        } ?>>{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?> <?php if ($UserRole == 5) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesCategory">Category</label>
                                                <select class="form-control select2" name="athletesCategory"
                                                        <?php if ($User[0]->role_id == 6) {
                                                            echo 'required';
                                                        } ?>
                                                        disabled
                                                        id="athletesCategory">
                                                    <option value="">Select Category</option>
                                                    @foreach($Categories as $index => $item)
                                                        <option value="{{$item->id}}" <?php if ($UserDetails[0]->athletesCategory == $item->id) {
                                                            echo 'selected';
                                                        } ?>>{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?> <?php if ($UserRole == 5) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesPosition">Position</label>
                                                <select class="form-control select2" name="athletesPosition"
                                                        <?php /*if ($User[0]->role_id == 6) {
                                                            echo 'required';
                                                        } */?>
                                                        disabled
                                                        id="athletesPosition">
                                                    <option value="">Select</option>
                                                    @foreach($PlayerPositions as $index => $item)
                                                        <option value="{{$item->id}}" <?php if ($UserDetails[0]->athletesPosition == $item->id) {
                                                            echo 'selected';
                                                        } ?>>{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                        $TrainingDays = array();
                                        if ($UserDetails[0]->athletesTrainingDays != "") {
                                            $TrainingDays = explode(",", $UserDetails[0]->athletesTrainingDays);
                                        }
                                        ?>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesTrainingDays">Training Days
                                                    #</label>
                                                <select class="form-control select2" name="athletesTrainingDays[]"
                                                        id="athletesTrainingDays" disabled multiple>
                                                    <option value="" disabled>Select</option>
                                                    <option value="M" <?php if (in_array("M", $TrainingDays)) {
                                                        echo "selected";
                                                    } ?>>Monday
                                                    </option>
                                                    <option value="T" <?php if (in_array("T", $TrainingDays)) {
                                                        echo "selected";
                                                    } ?>>Tuesday
                                                    </option>
                                                    <option value="W" <?php if (in_array("W", $TrainingDays)) {
                                                        echo "selected";
                                                    } ?>>Wednesday
                                                    </option>
                                                    <option value="Th" <?php if (in_array("Th", $TrainingDays)) {
                                                        echo "selected";
                                                    } ?>>Thrusday
                                                    </option>
                                                    <option value="F" <?php if (in_array("F", $TrainingDays)) {
                                                        echo "selected";
                                                    } ?>>Friday
                                                    </option>
                                                    <option value="S" <?php if (in_array("S", $TrainingDays)) {
                                                        echo "selected";
                                                    } ?>>Saturday
                                                    </option>
                                                    <option value="Su" <?php if (in_array("Su", $TrainingDays)) {
                                                        echo "selected";
                                                    } ?>>Sunday
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                @if($UserDetails[0]->profile_pic != "")
                                                    <label class="control-label w-100" for="profile_pic">Profile Picture
                                                        <a href="{{asset('public/storage/user-profiles/'.$UserDetails[0]->profile_pic)}}"
                                                           download><i class="fa fa-download float-right"
                                                                       aria-hidden="true"></i></a></label>
                                                @else
                                                    <label class="control-label w-100" for="profile_pic">Profile
                                                        Picture</label>
                                                @endif
                                                <input type="file"
                                                       name="profile_pic"
                                                       id="profile_pic"
                                                       accept=".jpeg,.png,.jpg,.JPEG,.PNG,.JPG"/>
                                            </div>
                                        </div>

                                        @if($UserRole == 5)
                                            <input type="hidden" name="athletesParent" id="athletesParent"
                                                   value="{{$UserDetails[0]->athletesParent}}"/>
                                        @else
                                            <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                                echo 'style="display: none;"';
                                            } ?>>
                                                <div class="form-group">
                                                    <label class="control-label" for="athletesParent">Parent
                                                        Name</label>
                                                    <select class="form-control select2" name="athletesParent"
                                                            <?php if ($User[0]->role_id == 6) {
                                                                echo 'required';
                                                            } ?>
                                                            id="athletesParent" onchange="getParentAddressInfo();"
                                                            disabled>
                                                        <option value="">Select</option>
                                                        <?php
                                                        $ParentName = "";
                                                        ?>
                                                        @foreach($Parents as $index => $item)
                                                            <?php
                                                            if ($item->middleName != "") {
                                                                $ParentName = $item->firstName . " " . $item->middleName . " " . $item->lastName;
                                                            } else {
                                                                $ParentName = $item->firstName . " " . $item->lastName;
                                                            }
                                                            ?>
                                                            <option value="{{$item->id}}" <?php if ($UserDetails[0]->athletesParent == $item->id) {
                                                                echo 'selected';
                                                            } ?>>{{$ParentName}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label"
                                                       for="athletesRelationship">Relation to Player</label>
                                                <select class="form-control" name="athletesRelationship"
                                                        disabled
                                                        id="athletesRelationship">
                                                    <option value="" selected>Select</option>
                                                    <option value="Mother" <?php if ($UserDetails[0]->athletesRelationship == "Mother") {
                                                        echo 'selected';
                                                    } ?>>Mother
                                                    </option>
                                                    <option value="Father" <?php if ($UserDetails[0]->athletesRelationship == "Father") {
                                                        echo 'selected';
                                                    } ?>>Father
                                                    </option>
                                                    <option value="Legal Guardian" <?php if ($UserDetails[0]->athletesRelationship == "Legal Guardian") {
                                                        echo 'selected';
                                                    } ?>>Legal Guardian
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesInsuranceName">Insurance
                                                    Name</label>
                                                <input class="form-control" name="athletesInsuranceName"
                                                       value="{{$UserDetails[0]->athletesInsuranceName}}"
                                                       disabled
                                                       id="athletesInsuranceName" placeholder="Insurance Name"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesDoctorName">Doctor
                                                    Name</label>
                                                <input class="form-control" name="athletesDoctorName"
                                                       value="{{$UserDetails[0]->athletesDoctorName}}"
                                                       disabled
                                                       id="athletesDoctorName" placeholder="Doctor Name"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesDoctorPhoneNumber">Doctor
                                                    #</label>
                                                <input type="number" step="any" class="form-control"
                                                       name="athletesDoctorPhoneNumber"
                                                       value="{{$UserDetails[0]->athletesDoctorPhoneNumber}}"
                                                       disabled
                                                       id="athletesDoctorPhoneNumber" placeholder="Phone Number"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesPolicyNumber">Policy #</label>
                                                <input class="form-control" name="athletesPolicyNumber"
                                                       value="{{$UserDetails[0]->athletesPolicyNumber}}"
                                                       disabled
                                                       id="athletesPolicyNumber" placeholder="Policy Number"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if ($User[0]->role_id != 6) {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesAllergies">Allergies</label>
                                                <input class="form-control" name="athletesAllergies"
                                                       data-role="tagsinput"
                                                       value="{{$UserDetails[0]->athletesAllergies}}"
                                                       disabled
                                                       id="athletesAllergies" placeholder="Allergies"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- User Information - End -->

                                <!-- Contact Information - Start -->
                                <button type="button" class="collapsible">Contact Information</button>
                                <div class="content">
                                    <div class="custom-row mt-3 mb-3">
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="email">Email Address</label>
                                                <input class="form-control" name="email" id="email"
                                                       data-validate="email" required
                                                       value="{{$User[0]->email}}"
                                                       disabled
                                                       placeholder="Email Address"/>
                                            </div>
                                        </div>

                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label w-100" for="phone1">
                                                    Phone Number 1
                                                    <i class="fa fa-plus-circle float-right" style="cursor: pointer;"
                                                       onclick="ShowPhone2();"></i>
                                                </label>
                                                <input type="number" step="any" maxlength="20" class="form-control"
                                                       name="phone1" id="phone1" disabled
                                                       <?php if ($User[0]->role_id != 6) {
                                                           echo "required";
                                                       } ?>
                                                       value="{{$UserDetails[0]->phone1}}"
                                                       placeholder="Phone Number 1"/>
                                            </div>
                                        </div>

                                        <div class="custom-col-4"
                                             id="phone2Field" <?php if ($UserDetails[0]->phone2 == '') {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label w-100" for="phone2">
                                                    Phone Number 2
                                                    <i class="fa fa-times-circle float-right" style="cursor: pointer;"
                                                       onclick="HidePhone2();"></i>
                                                </label>
                                                <input type="number" step="any" maxlength="20" class="form-control"
                                                       name="phone2" id="phone2"
                                                       value="{{$UserDetails[0]->phone2}}"
                                                       disabled
                                                       placeholder="Phone Number 2"/>
                                            </div>
                                        </div>

                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label w-100" for="socialMedia">
                                                    Social Media 1
                                                    <i class="fa fa-plus-circle float-right" style="cursor: pointer;"
                                                       onclick="ShowSocialMedia2();"></i>
                                                </label>
                                                <input class="form-control" name="socialMedia" id="socialMedia"
                                                       value="{{$UserDetails[0]->socialMedia}}"
                                                       disabled
                                                       placeholder="Link"/>
                                            </div>
                                        </div>

                                        <div class="custom-col-4"
                                             id="socialMedia2Field" <?php if ($UserDetails[0]->socialMedia2 == "") {
                                            echo 'style="display:none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label w-100" for="socialMedia2">
                                                    Social Media 2
                                                    <i class="fa fa-plus-circle float-right" style="cursor: pointer;"
                                                       onclick="ShowSocialMedia3();"></i>
                                                    <i class="fa fa-times-circle float-right"
                                                       style="cursor: pointer;padding-right: 2px;"
                                                       onclick="HideSocialMedia2();"></i>
                                                </label>
                                                <input class="form-control" name="socialMedia2" id="socialMedia2"
                                                       value="{{$UserDetails[0]->socialMedia2}}" disabled
                                                       placeholder="Link"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4"
                                             id="socialMedia3Field" <?php if ($UserDetails[0]->socialMedia3 == "") {
                                            echo 'style="display:none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label w-100" for="socialMedia3">
                                                    Social Media 3
                                                    <i class="fa fa-times-circle float-right" style="cursor: pointer;"
                                                       onclick="HideSocialMedia3();"></i>
                                                </label>
                                                <input class="form-control" name="socialMedia3" id="socialMedia3"
                                                       value="{{$UserDetails[0]->socialMedia3}}" disabled
                                                       placeholder="Link"/>
                                            </div>
                                        </div>

                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="state">State</label>
                                                <select name="state" id="state" class="form-control select2"
                                                        onchange="LoadStateCountyCity();" disabled>
                                                    <option value="" selected>Select State</option>
                                                    @foreach($States as $state)
                                                        @if($UserDetails[0]->state == $state->name)
                                                            <option value="{{$state->name}}"
                                                                    selected>{{$state->name}}</option>
                                                        @else
                                                            <option value="{{$state->name}}">{{$state->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="custom-col-4"
                                             id="citySection" <?php if ($UserDetails[0]->city == '') {
                                            echo 'style="display: none;"';
                                        } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="city">City</label>
                                                <select name="city" id="city" class="form-control select2" disabled>
                                                    <option value="" selected>Select City</option>
                                                    @foreach($cities as $index => $item)
                                                        @if($UserDetails[0]->city == $item->city)
                                                            <option value="{{$item->city}}"
                                                                    selected>{{$item->city}}</option>
                                                        @else
                                                            <option value="{{$item->city}}">{{$item->city}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="street">Street</label>
                                                <input class="form-control" name="street" id="street"
                                                       value="{{$UserDetails[0]->street}}"
                                                       disabled
                                                       placeholder="Street"/>
                                            </div>
                                        </div>

                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="zipcode">Zip code/Postal Code</label>
                                                <input type="number" name="zipcode" id="zipcode"
                                                       class="form-control"
                                                       value="{{$UserDetails[0]->zipcode}}"
                                                       data-validate="minlength[5]" required
                                                       onkeypress="limitKeypress(event,this.value,5)"
                                                       onblur="limitZipCodeCheck();"
                                                       disabled
                                                       placeholder="Zip Code"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Contact Information - End -->

                                <!-- User Identification - Start -->
                                <button type="button" class="collapsible">User Documents</button>
                                <div class="content">
                                    <div class="custom-row mt-3 mb-3">
                                        {{--Old Documents--}}
                                        <div class="responsive" >
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped w-100 mb-0 tbl-responsive">
                                                <thead>
                                                <tr>
                                                    <th>Document Type</th>
                                                    <th>Document Name</th>
                                                    <th>Expiration Date</th>
                                                    <th>Document Number</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($UserDocuments as $index => $document)
                                                    <tr id="{{'documentRow_' . $index}}">
                                                        <td>
                                                            {{$document->document_type}}
                                                        </td>
                                                        <td>
                                                            {{$document->document_name}}
                                                        </td>
                                                        <td>
                                                            @if($document->expiration_date != "")
                                                                {{Carbon\Carbon::parse($document->expiration_date)->format('m/d/Y')}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{$document->document_number}}
                                                        </td>
                                                        <td>
                                                        <span>
                                                            <a class="mr-2 pointerEvents"
                                                               href="{{asset('public/storage/user-documents/') . '/' . $document->document}}"
                                                               download="{{$document->document}}"><i
                                                                        class="fas fa-download"></i></a>
                                                            @if($UserRole == 1 || $UserRole == 2 || $UserRole == 3)
                                                                <i class="fas fa-trash cursor-pointer pointerEvents"
                                                                   id="{{'document||' . $index . '||' . $document->document}}"
                                                                   onclick="RemoveDocument(this.id);"></i>
                                                            @endif
                                                            @if($document->status != 0)
                                                                @if($document->status == 1)
                                                                    <i class="fas fa-check-circle pl-2"
                                                                       id="documentApproved_{{$document->id}}"
                                                                       style="color: green;"> Approved</i>
                                                                @elseif($document->status == 2)
                                                                    <i class="fas fa-times-circle pl-2"
                                                                       id="documentRejected_{{$document->id}}"
                                                                       style="color: red;"> Rejected</i>
                                                                @endif
                                                            @else
                                                                <i class="fas fa-check-circle pl-2"
                                                                   id="documentApproved_{{$document->id}}"
                                                                   style="color: green;display:none;"> Approved</i>
                                                                <i class="fas fa-times-circle pl-2"
                                                                   id="documentRejected_{{$document->id}}"
                                                                   style="color: red;display:none;"> Rejected</i>
                                                            @endif
                                                        </span>
                                                            @if($document->status == 0)
                                                                @if($UserRole == 1 || $UserRole == 2 || $UserRole == 3)
                                                                    <div class="form-check mt-2"
                                                                         id="documentVerificationBlock_{{$index}}_{{$document->id}}">
                                                                        <input type="checkbox"
                                                                               class="form-check-input hide-data-repeater-btn"
                                                                               onchange="documentVerification(this.id);"
                                                                               id="documentStatus_{{$index}}_{{$document->id}}"/>
                                                                        <label class="form-check-label"
                                                                               for="documentStatus_{{$index}}_{{$document->id}}">Approve/Reject</label>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        </div>

                                        <div class="col-md-12">
                                            <hr>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="repeater-custom-show-hide">
                                                <div data-repeater-list="documents">
                                                    <div data-repeater-item="" style="" class="mb-3">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="w-100">Document Type</label>
                                                                    <select name="documentName" id="documentName"
                                                                            class="form-control"
                                                                            onchange="CheckForDocumentName(this, this.value);">
                                                                        <option value="">Select</option>
                                                                        <option value="Background Check">Background
                                                                            Check
                                                                        </option>
                                                                        <option value="State ID">State ID</option>
                                                                        <option value="Passport">Passport</option>
                                                                        <option value="Birth Certificate">Birth
                                                                            Certificate
                                                                        </option>
                                                                        <option value="Player ID">Player ID</option>
                                                                        <option value="Others">Others</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 documentNameOthersSection"
                                                                 style="display: none;">
                                                                <div class="form-group">
                                                                    <label class="w-100">Document Name</label>
                                                                    <input type="text" name="documentNameOthers"
                                                                           id="documentNameOthers" class="form-control"
                                                                           placeholder="Document Name"
                                                                           autocomplete="off" maxlength="100"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="w-100">Document Number</label>
                                                                    <input type="number" name="documentNumbers"
                                                                           id="documentNumbers" class="form-control"
                                                                           placeholder="Enter Document Numbers"
                                                                           autocomplete="off" maxlength="100"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="add_document_label">Document 1</label>
                                                                    <input type="file" name="documentFile"
                                                                           id="documentFile" class="form-control"
                                                                           accept="image/jpeg, image/png, image/jpg, application/pdf, .doc, .docx, application/msword"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                            <span data-repeater-delete=""
                                                                  class="btn btn-danger btn-sm float-right hide-data-repeater-btn"
                                                                  id="DeleteDocumentBtn">
                                                                <span class="fas fa-trash mr-1"></span>&nbsp;
                                                                Delete
                                                            </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                    <span data-repeater-create=""
                                                          class="btn btn-primary btn-sm float-right hide-data-repeater-btn"
                                                          id="AddNewDocumentBtn">
                                                        <span class="fa fa-plus"></span> Add New Document
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- User Identification - End -->
                            </div>

                            <div class="col-md-12 text-center mt-5">
                                <button type="button" name="editUserBtn" id="editUserBtn" class="btn btn-primary"
                                        onclick="checkConfirmation();">Edit
                                </button>
                                <input type="submit" class="btn btn-primary" name="submitUserFormBtn"
                                       id="submitUserFormBtn" style="display:none;" value="Save Changes"/>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.users.scripts')
    @include('dashboard.users.editConfirmationModal')
    @include('dashboard.users.documentVerificationModal')
@endsection
