@extends('dashboard.layouts.app')
@section('content')
<style media="screen">
  /* Step Form Progress bar start */
  .bs-wizard {
      margin-top: 20px;
  }

  .bs-wizard {
      border-bottom: solid 1px #e0e0e0;
      padding: 0 0 10px 0;
  }

  .bs-wizard > .bs-wizard-step {
      padding: 0;
      position: relative;
      width: 15%;
  }

  .bs-wizard > .bs-wizard-step > .bs-wizard-dot {
      background: #104090;
  }

  .bs-wizard > .bs-wizard-step .bs-wizard-stepnum {
      color: #595959;
      font-size: 16px;
      margin-bottom: 5px;
  }

  .bs-wizard > .bs-wizard-step .bs-wizard-info {
      color: #999;
      font-size: 14px;
  }

  .bs-wizard > .bs-wizard-step > .bs-wizard-dot {
      position: absolute;
      width: 30px;
      height: 30px;
      display: block;
      top: 45px;
      left: 50%;
      margin-top: -15px;
      margin-left: -15px;
      border-radius: 50%;
  }

  .bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {
      content: ' ';
      width: 14px;
      height: 14px;
      background: #ffffff;
      border-radius: 50px;
      position: absolute;
      top: 8px;
      left: 8px;
  }

  .bs-wizard > .bs-wizard-step > .progress {
      position: relative;
      border-radius: 0px;
      height: 8px;
      box-shadow: none;
      margin: 20px 0;
  }

  .bs-wizard > .bs-wizard-step > .progress > .progress-bar {
      width: 0px;
      box-shadow: none;
  }

  .bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {
      width: 100%;
  }

  .bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {
      width: 50%;
  }

  .bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {
      width: 0%;
  }

  .bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {
      width: 100%;
  }

  .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {
      background-color: #f5f5f5;
  }

  .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {
      opacity: 0;
  }

  .bs-wizard > .bs-wizard-step:first-child > .progress {
      left: 50%;
      width: 50%;
  }

  .bs-wizard > .bs-wizard-step:last-child > .progress {
      width: 50%;
  }

  .bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot {
      pointer-events: none;
  }

  .StepBar {
      margin-top: -50px;
  }

  .grid-margin {
      margin-bottom: 0;
  }

  .progress-bar {
      float: left;
      width: 0;
      height: 100%;
      font-size: 12px;
      line-height: 18px;
      color: #fff;
      text-align: center;
      background-color: #104090;
      -webkit-box-shadow: inset 0 -1px 0 rgb(0 0 0 / 15%);
      box-shadow: inset 0 -1px 0 rgb(0 0 0 / 15%);
      -webkit-transition: width .6s ease;
      -o-transition: width .6s ease;
      transition: width .6s ease;
  }
  /* End of step form progress bar */
  @media (min-width: 992px){
      .responsive {

      }
      .head {

      }
      .head_mob{
          display:none;
      }
  }


  @media (max-width: 767px) {
      .responsive {
          overflow-x:auto;
      }
      .head {
          display:none;
      }
      .head_mob{

      }
  }
</style>
    <form action="{{route('leads.update')}}" method="post"
      enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="lead_id" id="lead_id" value="" />
      <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
                <h3 class="head">Leads > <span class="text-primary">Add</span></h3>
            </div>
            <div class="col-md-6">
              <div class="float-right">
                <button type="button" class="btn btn-primary mb-3 mr-2" style="float: right;"
                        onclick="window.location.href='{{route('leads')}}';"><i
                            class="fas fa-arrow-left"></i></button>
              </div>
            </div>
          </div>

          <div class="row">
              <div class="col-md-12">
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

          {{--Step Bar--}}
          <section class="contact-area mb-3 head">
              <div class="container">
                  <div class="row" style="margin:auto;">
                      <div class="col-lg-12">
                          <div class="row bs-wizard" style="border-bottom:0;">
                              <div class="col-md-2 col-sm-4"></div>
                              <div class="col-xs-12 bs-wizard-step step1 complete"><!-- complete -->
                                  <div class="text-center bs-wizard-stepnum">Step 1</div>
                                  <div class="progress">
                                      <div class="progress-bar"></div>
                                  </div>
                                  <a href="#" class="bs-wizard-dot"></a>
                                  <div class="bs-wizard-info text-center">Parent's Information</div>
                              </div>

                              <div class="col-xs-12 bs-wizard-step step2 disabled"><!-- disabled -->
                                  <div class="text-center bs-wizard-stepnum">Step 2</div>
                                  <div class="progress">
                                      <div class="progress-bar"></div>
                                  </div>
                                  <a href="#" class="bs-wizard-dot"></a>
                                  <div class="bs-wizard-info text-center">Player's Information</div>
                              </div>

                              <div class="col-xs-12 bs-wizard-step step3 disabled"><!-- disabled -->
                                  <div class="text-center bs-wizard-stepnum">Step 3</div>
                                  <div class="progress">
                                      <div class="progress-bar"></div>
                                  </div>
                                  <a href="#" class="bs-wizard-dot"></a>
                                  <div class="bs-wizard-info text-center">Schedule Free Class</div>
                              </div>

                              <div class="col-xs-12 bs-wizard-step step4 disabled"><!-- disabled -->
                                  <div class="text-center bs-wizard-stepnum">Step 4</div>
                                  <div class="progress">
                                      <div class="progress-bar"></div>
                                  </div>
                                  <a href="#" class="bs-wizard-dot"></a>
                                  <div class="bs-wizard-info text-center">Get Registered</div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
          {{--Step Bar--}}

          {{-- Parents Information --}}
          <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-10">
                  <div class="panel panel-default" id="leadParentsInformation">
                     <div class="panel-body">
                        <h2 class="panel-title mb-4">
                            PARENTS INFORMATION
                        </h2>
                        <div class="custom-row">
                          <div class="custom-col-3 mt-2">
                              <label for="parentFirstName"><strong>First Name</strong></label>
                              <input type="text" name="parentFirstName" id="parentFirstName" class="form-control"
                              placeholder="First Name" autocomplete="off" onblur="AutoSaveLead();" />
                              <div style="margin-top: 7px;" id="parent_f_name"></div>
                          </div>
                          <div class="custom-col-3 mt-2">
                              <label for="parentLastName"><strong>Last Name</strong></label>
                              <input type="text" name="parentLastName" id="parentLastName" class="form-control"
                              placeholder="Last Name" autocomplete="off" onblur="AutoSaveLead();" />
                              <div style="margin-top: 7px;" id="parent_l_name"></div>
                          </div>
                          <div class="custom-col-3 mt-2">
                              <label for="parentPhone" class="w-100"><strong>Phone Number 1</strong><i
                              class="fa fa-plus-circle float-right" style="cursor: pointer;"
                              onclick="ShowParentPhone2Field();"></i></label>
                              <input type="number" name="parentPhone" id="parentPhone" class="form-control"
                              placeholder="Enter Your Phone Number" maxlength="20" autocomplete="off" onblur="AutoSaveLead();"/>
                              <div style="margin-top: 7px;" id="parent_phone1"></div>
                          </div>
                          <div class="custom-col-3 mt-2" id="ParentPhoneNumber2" style="display: none;">
                              <label for="parentPhone2" class="w-100">Phone Number 2<i
                              class="fa fa-trash float-right" style="cursor: pointer;"
                              onclick="HideParentPhone2Field();"></i></label>
                              <input type="number" name="parentPhone2" id="parentPhone2"
                                     class="form-control" placeholder="Enter Your Phone Number"
                                     maxlength="20" autocomplete="off" onblur="AutoSaveLead();"/>
                          </div>
                          <div class="custom-col-3 mt-2">
                              <label for="parentEmail"><strong>Email</strong></label>
                              <input type="email" name="parentEmail" id="parentEmail" class="form-control"
                              placeholder="Email" onblur="AutoSaveLead();"/>
                              {{--<div style="margin-top: 7px;" id="parent_email"></div>--}}
                          </div>
                          <div class="custom-col-3 mt-2">
                              <div class="form-group">
                                  <label class="control-label" for="state">State</label>
                                  <select name="state" id="state" class="form-control select2"
                                          onchange="LoadStateCountyCity();">
                                      <option value="">Select State</option>
                                      @foreach($States as $state)
                                          <option value="{{$state->name}}">{{$state->name}}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="custom-col-3 mt-2" id="citySection" style="display: none;">
                              <div class="form-group">
                                  <label class="control-label" for="city">City</label>
                                  <select name="city" id="city" class="form-control">
                                      <option value="" selected>Select City</option>
                                  </select>
                              </div>
                          </div>
                          <div class="custom-col-3 mt-2">
                              <div class="form-group">
                                  <label class="control-label" for="street">Street</label>
                                  <input class="form-control" name="street" id="street"
                                         placeholder="Street" autocomplete="off" onblur="AutoSaveLead();" />
                              </div>
                          </div>
                          <div class="custom-col-3 mt-2">
                              <div class="form-group">
                                  <label class="control-label" for="zipcode">Zip code</label>
                                  <input type="number" name="zipcode" id="zipcode"
                                         class="form-control"
                                         data-validate="minlength[5]"
                                         onkeypress="limitKeypress(event,this.value,5)"
                                         onblur="limitZipCodeCheck();"
                                         autocomplete="off"
                                         placeholder="Zip Code" onblur="AutoSaveLead();" />
                              </div>
                          </div>
                       </div>
                       <div class="row mt-4">
                         <div class="col-md-12">
                           <input type="button" class="btn btn-primary  float-right" value="Next"
                                  onclick="AutoSaveLead();ShowPlayerInformation();"/>
                         </div>
                       </div>
                    </div>
                </div>
             </div>
          </div>
          {{-- Parents Information --}}

          {{-- Players Information --}}
          <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-10">
                  <div class="panel panel-default" id="leadPlayersInformation" style="display: none;">
                     <div class="panel-body">
                        <h2 class="panel-title mb-4">
                            PLAYER INFORMATION
                        </h2>

                        <div class="repeater-custom-show-hide">
                          <div data-repeater-list="playerInformation">
                              <div data-repeater-item="">
                                  <div class="custom-row">
                                    <div class="custom-col-3 mt-2">
                                        <label for="playerFirstName"><strong>First Name</strong></label>
                                        <input type="text" name="playerFirstName" id="playerFirstName"
                                               class="form-control"
                                               placeholder="First Name" autocomplete="off" />
                                        <div style="margin-top: 7px;" id="player_f_name"></div>
                                    </div>
                                    <div class="custom-col-3 mt-2">
                                        <label for="playerLastName"><strong>Last Name</strong></label>
                                        <input type="text" name="playerLastName" id="playerLastName"
                                               class="form-control"
                                               placeholder="Last Name" autocomplete="off" />
                                        <div style="margin-top: 7px;" id="player_l_name"></div>
                                    </div>
                                    <div class="custom-col-3 mt-2">
                                        <label for="playerDOB">Date of Birth</label>
                                        <input class="form-control datepicker" name="playerDOB"
                                               id="playerDOB"
                                               autocomplete="off"
                                               placeholder="MM/DD/YYYY" autocomplete="off" />
                                        <div style="margin-top: 7px;" id="player_dob"></div>
                                    </div>
                                    <div class="custom-col-3 mt-2">
                                        <div class="form-group">
                                            <label class="control-label" for="playerGender">Gender</label>
                                            <div class="mt-2">
                                              <label class="radio-inline">
                                                <input type="radio" name="playerGender" id="male_gender" value="Male" checked>Male
                                              </label>
                                              <label class="radio-inline">
                                                <input type="radio" name="playerGender" id="female_gender" value="Female">Female
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-col-3 mt-2">
                                        <div class="form-group">
                                            <label class="control-label" for="playerRelationship">Relationship</label>
                                            <select class="form-control" name="playerRelationship"
                                                    id="playerRelationship">
                                                <option value="" selected>Select</option>
                                                <option value="Mother">Mother</option>
                                                <option value="Father">Father</option>
                                                <option value="Legal Guardian">Legal Guardian</option>
                                            </select>
                                            <div style="margin-top: 5px;" id="player_relationship"></div>
                                        </div>
                                    </div>
                                    <div class="custom-col-3 mt-2">
                                        <label> &nbsp;</label>
                                        <div>
                                            <span data-repeater-delete="" class="btn btn-danger btn-sm">
                                                <span class="far fa-trash-alt mr-1"></span> Delete
                                            </span>
                                        </div>
                                    </div>
                                 </div>
                               </div>
                           </div>
                           <div class="custom-row">
                               <div class="custom-col-12 mb-2">
                                   <span data-repeater-create="" class="btn btn-primary btn-sm">
                                       <span class="fa fa-plus"></span> Add
                                   </span>
                               </div>
                           </div>
                       </div>

                       <div class="custom-row">
                         <div class="custom-col-3 mt-2">
                             <div class="form-group">
                                 <label class="control-label" for="location">Locations</label>
                                 <select class="form-control select2" name="location"
                                        id="location" onchange="checkLeadLocation(this.value);">
                                     <option value="">Select</option>
                                     <option value="-1">I don't know</option>
                                     @foreach($Locations as $index => $item)
                                         <option value="{{$item->id}}">{{$item->name}}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <div class="custom-col-3 mt-2" id="LocationZipCodeBlock" style="display:none;">
                             <div class="form-group">
                                 <label class="control-label" for="locationZipcode">Zip code</label>
                                 <input type="number" name="locationZipcode" id="locationZipcode"
                                        class="form-control"
                                        data-validate="minlength[5]"
                                        onkeypress="limitKeypress(event,this.value,5)"
                                        onblur="limitZipCodeCheck();"
                                        placeholder="Zip Code"/>
                             </div>
                         </div>
                         <div class="custom-col-12 mt-2">
                             <div class="form-group">
                                 <label class="control-label" for="message">Message</label>
                                 <textarea name="message" id="message" class="form-control" rows="5" cols="80"></textarea>
                             </div>
                         </div>
                       </div>

                       <div class="row mt-4">
                         <div class="col-md-12">
                           <input type="button" class="btn btn-primary w-10 float-left" value="Back"
                                  onclick="ShowParentInformation();"/>
                           <input type="button" class="btn btn-primary w-10 float-right" value="Next"
                                  onclick="ShowScheduleFreeClass();" />
                         </div>
                       </div>
                    </div>
                </div>
             </div>
          </div>
          {{-- Players Information --}}

          {{-- Schedule Free Class --}}
          <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-10">
                  <div class="panel panel-default" id="leadScheduleFreeClass" style="display: none;">
                      <div class="panel-body">
                          <h2 class="panel-title mb-4">
                              SCHEDULE
                          </h2>
                          <div class="custom-row">
                              <div class="custom-col-12 mt-2">
                                  <div class="form-group">
                                      <label class="control-label" for="playerGender">Are you register now or schedule a free class?</label>
                                      <div class="mt-2">
                                        <label class="radio-inline">
                                          <input type="radio" name="getregister_or_schedulefreeclass" id="get_register" value="1">Get Register
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="getregister_or_schedulefreeclass" id="schedule_free_class" value="2">Schedule Free Class
                                        </label>
                                      </div>
                                  </div>
                              </div>
                              <div class="custom-col-3 mt-2 freeClassField" style="display:none;">
                                  <div class="form-group">
                                      <label class="control-label" for="free_class">Free Class</label>
                                      <select class="form-control select2" name="free_class"
                                              id="free_class" onchange="getFreeClassTiming(this.value);">
                                          <option value="">Select</option>
                                          @foreach($FreeClasses as $index => $class)
                                              <option value="{{$class->id}}">{{$class->title}}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>

                              <div class="custom-col-3 mt-2 freeClassField" style="display:none;">
                                  <div class="form-group">
                                      <label class="control-label" for="free_class_time">Free Class Time</label>
                                      <select class="form-control select2" name="free_class_time"
                                              id="free_class_time">
                                          <option value="">Select</option>
                                      </select>
                                  </div>
                              </div>
                          </div>

                          <div class="row mt-4">
                              <div class="col-md-12">
                                  <input type="button" class="btn btn-primary float-left" value="Back"
                                         onclick="ShowPlayerInformation();"/>
                                  <input type="button" class="btn btn-primary float-right" value="Get Register"
                                         onclick="ShowGetRegistered();" style="display:none;" />
                                  <input type="submit" class="btn btn-primary float-right" value="Submit" />
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          {{-- Schedule Free Class --}}

          {{-- Get Registered --}}
          <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-10">
                  <div class="panel panel-default" id="leadGetRegistered" style="display: none;">
                      <div class="panel-body">
                          <h2 class="panel-title mb-4">
                              Registration Details
                          </h2>
                          <div class="custom-row">
                              <div class="custom-col-3 mt-2">

                              </div>
                          </div>

                          <div class="row mt-4">
                              <div class="col-md-12">
                                  <input type="button" class="btn btn-primary w-10 float-left" value="Back"
                                         onclick="ShowScheduleFreeClass();"/>
                                  <input type="submit" class="btn btn-primary w-10 float-right" value="Submit" />
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          {{-- Get Registered --}}
      </div>
    </form>

    @include('dashboard.leads.scripts')
@endsection
