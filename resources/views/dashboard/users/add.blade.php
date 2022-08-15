@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid" id="AddUserPage">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">
                            @if($UserRole == 5)
                                Family > New
                            @else
                                Users > New
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

                        <form role="form" action="{{route('users.store')}}" method="post" enctype="multipart/form-data"
                              class="form-wizard validate userInfoForm" novalidate>
                            @csrf
                            <input type="hidden" name="role" value="{{$RoleId}}">
                            <ul class="tabs">
                                <li class="active">
                                    <a href="#userInfo" data-toggle="tab">
                                        User Information
                                        <span>1</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#contactInfo" data-toggle="tab">
                                        Contact Information
                                        <span>2</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#identificationInfo" data-toggle="tab">
                                        Documents
                                        <span>3</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="progress-indicator"><span></span></div>

                            <div class="tab-content no-margin">
                                <div class="tab-pane with-bg active" id="userInfo">
                                    <div class="custom-row">
                                        @if($UserRole != 5)
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="role_title">Role</label>
                                                <input type="text" class="form-control" name="role_title"
                                                       id="role_title" value="{{$RoleDetials[0]->title}}">
                                            </div>
                                        </div>
                                        @endif
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="firstName">First Name</label>
                                                <input class="form-control" name="firstName"
                                                       id="firstName" data-validate="required"
                                                       required
                                                       placeholder="Your First Name" />
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="middleName">Middle Name</label>
                                                <input class="form-control" name="middleName"
                                                       id="middleName"
                                                       placeholder="Your Middle Name" />
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="lastName">Last Name</label>
                                                <input class="form-control" name="lastName"
                                                       id="lastName" data-validate="required"
                                                       required
                                                       placeholder="Your Last Name" />
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="dob">Date of Birth</label>
                                                <input class="form-control datepicker" name="dob"
                                                       id="dob" data-validate="required"
                                                       required
                                                       autocomplete="off"
                                                       onchange="SetUserCategory();"
                                                       placeholder="MM/DD/YYYY" />
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="gender">Gender</label>
                                                <div class="mt-2">
                                                  <label class="radio-inline">
                                                    <input type="radio" name="gender" id="male_gender" value="Male" checked>Male
                                                  </label>
                                                  <label class="radio-inline">
                                                    <input type="radio" name="gender" id="female_gender" value="Female">Female
                                                  </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 managerRequiredFields" <?php if($RoleId != 3) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="managerLocations">Manager Locations</label>
                                                <select class="form-control select2" name="managerLocations[]"
                                                       id="managerLocations"
                                                       <?php if($RoleId == 3) { echo 'data-validate="required"'; } ?>
                                                       multiple>
                                                    <option value="" disabled>Select</option>
                                                    @foreach($Locations as $index => $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 coachRequiredFields" <?php if($RoleId != 4) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="coachLevels">Levels</label>
                                                <select class="form-control select2" name="coachLevels[]"
                                                        id="coachLevels"
                                                        <?php if($RoleId == 4) { echo 'data-validate="required"'; } ?>
                                                        multiple>
                                                    <option value="" disabled>Select</option>
                                                    @foreach($Levels as $index => $item)
                                                        <option value="{{$item->id}}">{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 coachRequiredFields" <?php if($RoleId != 4) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="coachCategories">Categories</label>
                                                <select class="form-control select2" name="coachCategories[]"
                                                        id="coachCategories"
                                                        <?php if($RoleId == 4) { echo 'data-validate="required"'; } ?>
                                                        multiple>
                                                    <option value="" disabled>Select</option>
                                                    @foreach($Categories as $index => $item)
                                                        <option value="{{$item->id}}">{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 coachRequiredFields" <?php if($RoleId != 4) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="coachLocations">Coach Locations</label>
                                                <select class="form-control select2" name="coachLocations[]"
                                                       id="coachLocations"
                                                       <?php if($RoleId == 4) { echo 'data-validate="required"'; } ?>
                                                       multiple>
                                                    <option value="" disabled>Select</option>
                                                    @foreach($Locations as $index => $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 parentRequiredFields" <?php if($RoleId != 5) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="parentProfession">Occupation</label>
                                                <input type="text" class="form-control" name="parentProfession" id="parentProfession" placeholder="Occupation" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                          <div class="row">
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                  <label class="control-label" for="athletesHeightFt">Height (ft)</label>
                                                  <input type="number" step="any" class="form-control" name="athletesHeightFt"
                                                         id="athletesHeightFt" placeholder="feet" min="0" />
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                  <label class="control-label" for="athletesHeightInches">Height (in)</label>
                                                  <input type="number" step="any" class="form-control" name="athletesHeightInches"
                                                         id="athletesHeightInches" placeholder="inches" min="0" />
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?> sty>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesWeight">Weight</label>
                                                <input type="number" step="any" class="form-control" name="athletesWeight"
                                                       id="athletesWeight" placeholder="Weight" min="0" />
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesLevel">Level</label>
                                                <select class="form-control select2" name="athletesLevel"
                                                        <?php if($RoleId == 6) { echo 'data-validate="required"'; } ?>
                                                        id="athletesLevel">
                                                    <option value="">Select</option>
                                                    @foreach($Levels as $index => $item)
                                                        <option value="{{$item->id}}">{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesCategory">Category</label>
                                                <select class="form-control select2" name="athletesCategory"
                                                        <?php if($RoleId == 6) { echo 'data-validate="required"'; } ?>
                                                        id="athletesCategory">
                                                    <option value="">Select</option>
                                                    @foreach($Categories as $index => $item)
                                                        <option value="{{$item->id}}">{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesPosition">Position</label>
                                                <select class="form-control select2" name="athletesPosition"
                                                        <?php /*if($RoleId == 6) { echo 'data-validate="required"'; } */?>
                                                        id="athletesPosition">
                                                    <option value="">Select</option>
                                                    @foreach($PlayerPositions as $index => $item)
                                                        <option value="{{$item->id}}">{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesTrainingDays">Training Days #</label>
                                                <!-- <input type="number" step="any" class="form-control trainingdays" name="athletesTrainingDays"
                                                       id="athletesTrainingDays" placeholder="Days" min="1" max="7" /> -->
                                                 <select class="form-control select2" name="athletesTrainingDays[]" id="athletesTrainingDays" multiple>
                                                    <option value="" disabled>Select</option>
                                                    <option value="M">Monday</option>
                                                    <option value="T">Tuesday</option>
                                                    <option value="W">Wednesday</option>
                                                    <option value="Th">Thrusday</option>
                                                    <option value="F">Friday</option>
                                                    <option value="S">Saturday</option>
                                                    <option value="Su">Sunday</option>
                                                 </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                              <label class="control-label" for="profile_pic">Profile Picture</label>
                                              <input type="file"
                                                     name="profile_pic"
                                                     id="profile_pic"
                                                     accept=".jpeg,.png,.jpg,.JPEG,.PNG,.JPG"/>
                                            </div>
                                        </div>

                                        @if($UserRole == 5)
                                        <input type="hidden" name="athletesParent" id="athletesParent" value="{{\Illuminate\Support\Facades\Auth::id()}}">
                                        @else
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesParent">Parent Name</label>
                                                <select class="form-control select2" name="athletesParent"
                                                        <?php if($RoleId == 6) { echo 'data-validate="required"'; } ?>
                                                        id="athletesParent" onchange="getParentAddressInfo();">
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
                                                    <option value="{{$item->id}}">{{$ParentName}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesRelationship">Relation to Player</label>
                                                <select class="form-control" name="athletesRelationship"
                                                        id="athletesRelationship">
                                                    <option value="" selected>Select</option>
                                                    <option value="Mother">Mother</option>
                                                    <option value="Father">Father</option>
                                                    <option value="Legal Guardian">Legal Guardian</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesInsuranceName">Insurance Name</label>
                                                <input class="form-control" name="athletesInsuranceName"
                                                       id="athletesInsuranceName" placeholder="Insurance Name" />
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesDoctorName">Doctor Name</label>
                                                <input class="form-control" name="athletesDoctorName"
                                                       id="athletesDoctorName" placeholder="Doctor Name" />
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesDoctorPhoneNumber">Doctor #</label>
                                                <input type="number" step="any" class="form-control" name="athletesDoctorPhoneNumber"
                                                       id="athletesDoctorPhoneNumber" placeholder="Phone Number" />
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesPolicyNumber">Policy #</label>
                                                <input class="form-control" name="athletesPolicyNumber"
                                                       id="athletesPolicyNumber" placeholder="Policy Number" />
                                            </div>
                                        </div>
                                        <div class="custom-col-4 athletesRequiredFields" <?php if($RoleId != 6) { echo 'style="display: none;"'; } ?>>
                                            <div class="form-group">
                                                <label class="control-label" for="athletesAllergies">Allergies</label>
                                                <input class="form-control" name="athletesAllergies"
                                                       data-role="tagsinput"
                                                       id="athletesAllergies" placeholder="Allergies" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane with-bg" id="contactInfo">
                                    <div class="custom-row">
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="email">Email Address</label>
                                                <input class="form-control" name="email" id="email"
                                                       data-validate="email" required
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
                                                       <?php if($RoleId != 6) { echo 'data-validate="required"'; } ?>
                                                       name="phone1" id="phone1"
                                                       placeholder="Phone Number 1"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4" id="phone2Field" style="display: none;">
                                            <div class="form-group">
                                                <label class="control-label w-100" for="phone2">
                                                    Phone Number 2
                                                    <i class="fa fa-times-circle float-right" style="cursor: pointer;"
                                                       onclick="HidePhone2();"></i>
                                                </label>
                                                <input type="number" step="any" maxlength="20" class="form-control" name="phone2" id="phone2"
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
                                                       placeholder="Link"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4" id="socialMedia2Field" style="display:none;">
                                            <div class="form-group">
                                                <label class="control-label w-100" for="socialMedia2">
                                                    Social Media 2
                                                    <i class="fa fa-plus-circle float-right" style="cursor: pointer;"
                                                       onclick="ShowSocialMedia3();"></i>
                                                    <i class="fa fa-times-circle float-right" style="cursor: pointer;padding-right: 2px;"
                                                       onclick="HideSocialMedia2();"></i>
                                                </label>
                                                <input class="form-control" name="socialMedia2" id="socialMedia2"
                                                       placeholder="Link"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4" id="socialMedia3Field" style="display:none;">
                                            <div class="form-group">
                                                <label class="control-label w-100" for="socialMedia3">
                                                    Social Media 3
                                                    <i class="fa fa-times-circle float-right" style="cursor: pointer;"
                                                       onclick="HideSocialMedia3();"></i>
                                                </label>
                                                <input class="form-control" name="socialMedia3" id="socialMedia3"
                                                       placeholder="Link"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="state">State</label>
                                                <select name="state" id="state" class="form-control select2" onchange="LoadStateCountyCity();">
                                                    <option value="">Select State</option>
                                                    @foreach($States as $state)
                                                        <option value="{{$state->name}}">{{$state->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4" id="citySection" style="display: none;">
                                            <div class="form-group">
                                                <label class="control-label" for="city">City</label>
                                                <select name="city" id="city" class="form-control">
                                                    <option value="" selected>Select City</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="street">Street</label>
                                                <input class="form-control" name="street" id="street"
                                                       placeholder="Street"/>
                                            </div>
                                        </div>
                                        <div class="custom-col-4">
                                            <div class="form-group">
                                                <label class="control-label" for="zipcode">Zip code/Postal Code</label>
                                                <input type="number" name="zipcode" id="zipcode"
                                                       class="form-control"
                                                       data-validate="minlength[5]" required
                                                       onkeypress="limitKeypress(event,this.value,5)"
                                                       onblur="limitZipCodeCheck();"
                                                       placeholder="Zip Code"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane with-bg" id="identificationInfo">
                                    <div class="custom-row">
                                        <div class="col-md-12">
                                            <div class="repeater-custom-show-hide">
                                                <div data-repeater-list="documents">
                                                    <div data-repeater-item="" style="" class="mb-3">
                                                        <div class="custom-row">
                                                            <div class="custom-col-4">
                                                                <div class="form-group">
                                                                    <label class="w-100">Document Type</label>
                                                                    <select name="documentName" class="form-control" onchange="CheckForDocumentName(this, this.value);">
                                                                        <option value="">Select</option>
                                                                        <option value="Background Check">Background Check</option>
                                                                        <option value="State ID">State ID</option>
                                                                        <option value="Passport">Passport</option>
                                                                        <option value="Birth Certificate">Birth Certificate</option>
                                                                        <option value="Player ID">Player ID</option>
                                                                        <option value="Others">Others</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="custom-col-4 documentNameOthersSection" style="display: none;">
                                                                <div class="form-group">
                                                                    <label class="w-100">Document Name</label>
                                                                    <input type="text" name="documentNameOthers" class="form-control"
                                                                           placeholder="Document Name" autocomplete="off" maxlength="100" />
                                                                </div>
                                                            </div>
                                                            <div class="custom-col-4 documentExpirationDateSection" style="display: none;">
                                                                <div class="form-group">
                                                                    <label class="w-100">Expiration Date</label>
                                                                    <input class="form-control datepicker" name="documentExpirationDate"
                                                                           id="documentExpirationDate"
                                                                           autocomplete="off"
                                                                           placeholder="MM/DD/YYYY" />
                                                                </div>
                                                            </div>
                                                            <div class="custom-col-4">
                                                                <div class="form-group">
                                                                    <label class="w-100">Document Number</label>
                                                                    <input type="number" name="documentNumbers" class="form-control"
                                                                           placeholder="Enter Document Numbers" autocomplete="off" maxlength="100" />
                                                                </div>
                                                            </div>
                                                            <div class="custom-col-4">
                                                                <div class="form-group">
                                                                    <label class="add_document_label">Document 1</label>
                                                                    <input type="file" name="documentFile" class="form-control"
                                                                           accept="image/jpeg, image/png, image/jpg, application/pdf, .doc, .docx, application/msword" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <span data-repeater-delete=""
                                                                      class="btn btn-danger btn-sm float-right">
                                                                    <span class="fas fa-trash mr-1"></span>&nbsp;
                                                                    Delete
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <span data-repeater-create="" class="btn btn-primary btn-sm float-right">
                                                            <span class="fa fa-plus"></span> Add New Document
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <input type="submit" class="btn btn-primary" name="submitBtn" value="Save" />
                                        </div>
                                    </div>
                                </div>
                                <ul class="pager wizard">
                                    <li class="previous"><a href="#"><i class="entypo-left-open"></i> Previous</a>
                                    </li>
                                    <li class="next"><a href="#">Next <i class="entypo-right-open"></i></a></li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.users.scripts')
@endsection
