@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row" id="EditClassPage">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Classes > <span class="text-primary">View</span><br><span class="mt-5">({{$Class[0]->class_id}})</span></h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2" style="float: right;"
                                onclick="window.location.href='{{route('classes')}}';"><i
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

                        <form action="{{route('classes.update')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" id="hiddenClassId" value="{{$Class[0]->id}}">

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="{{$Class[0]->title}}" required disabled>
                                </div>
                                <div class="col-md-4">
                                    <label for="coach">Coach</label>
                                    <select class="form-control select2" name="coach" id="coach" required disabled>
                                        <option value="" selected>Select Coach</option>
                                        <?php
                                        $CoachName = "";
                                        ?>
                                        @foreach($Coaches as $coach)
                                        <?php
                                        if ($coach->middleName != "") {
                                          $CoachName = $coach->firstName . " " . $coach->middleName . " " . $coach->lastName;
                                        } else {
                                          $CoachName = $coach->firstName . " " . $coach->lastName;
                                        }
                                        ?>
                                        <option value="{{$coach->id}}" <?php if($Class[0]->coach == $coach->id){echo "selected";} ?>>{{$CoachName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="category">Category</label>
                                    <select class="form-control select2" name="category" id="category" required disabled>
                                        <option value="" selected>Select Category</option>
                                        @foreach($Categories as $category)
                                            @if($Class[0]->category == $category->id)
                                                <option value="{{$category->id}}" selected>{{$category->title}}</option>
                                            @else
                                                <option value="{{$category->id}}">{{$category->title}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <label for="location">Location</label>
                                    <select class="form-control select2" name="location" id="location" required disabled>
                                        <option value="">Select</option>
                                        @foreach($Locations as $index => $item)
                                            <option value="{{$item->id}}" <?php if($Class[0]->location == $item->id){echo "selected";} ?>>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <label for="is_free">Is Free?</label>
                                    <br>
                                    <input type="checkbox" class="iswitch iswitch-primary" value="0" name="is_free"
                                           id="is_free" <?php if($Class[0]->is_free == 1) { echo 'checked'; } ?>
                                           onchange="CheckClassFeeStatus(this.checked);" disabled>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h4 style="color: black;">Schedule</h4>
                                        <hr class="mt-2 mb-3">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="repeater-custom-show-hide">
                                            <div data-repeater-list="timing">
                                                @if(sizeof($ClassTimings) > 0)
                                                    @foreach($ClassTimings as $index => $item)
                                                        <div data-repeater-item="" style="">
                                                            <div class="row">
                                                              <div class="col-md-4 mb-2">
                                                                  <label for="days">Day</label>
                                                                  <select name="days"
                                                                          class="form-control">
                                                                      <option value="">Select</option>
                                                                      <option value="1" <?php if($item->day == 1){echo "selected";} ?>>Monday</option>
                                                                      <option value="2" <?php if($item->day == 2){echo "selected";} ?>>Tuesday</option>
                                                                      <option value="3" <?php if($item->day == 3){echo "selected";} ?>>Wednesday</option>
                                                                      <option value="4" <?php if($item->day == 4){echo "selected";} ?>>Thursday</option>
                                                                      <option value="5" <?php if($item->day == 5){echo "selected";} ?>>Friday</option>
                                                                      <option value="6" <?php if($item->day == 6){echo "selected";} ?>>Saturday</option>
                                                                      <option value="7" <?php if($item->day == 7){echo "selected";} ?>>Sunday</option>
                                                                  </select>
                                                              </div>
                                                              <div class="col-md-4 mb-2">
                                                                  <label for="price">Time</label>
                                                                  <div class="input-group input-group-minimal">
                                                                      <input type="text"
                                                                             class="form-control timepicker"
                                                                             name="time"
                                                                             data-template="dropdown"
                                                                             data-show-seconds="false"
                                                                             data-default-time="08:00 AM"
                                                                             value="{{\Carbon\Carbon::parse($item->time)->format('h:i:s A')}}"
                                                                             data-show-meridian="true"
                                                                             data-minute-step="5"
                                                                             data-second-step="5"/>
                                                                      <div class="input-group-addon">
                                                                          <a href="#">
                                                                              <i class="linecons-clock"></i>
                                                                          </a>
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                              <div class="col-md-4 mb-2">
                                                                  <label> &nbsp;</label>
                                                                  <div>
                                                                      <span data-repeater-delete=""
                                                                            class="btn btn-danger btn-sm hide-data-repeater-btn">
                                                                          <span class="far fa-trash-alt mr-1"></span> Delete
                                                                      </span>
                                                                  </div>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div data-repeater-item="" style="">
                                                        <div class="row">
                                                          <div class="col-md-4 mb-2">
                                                              <label for="days">Day</label>
                                                              <select name="days"
                                                                      class="form-control">
                                                                  <option value="">Select</option>
                                                                  <option value="1">Monday</option>
                                                                  <option value="2">Tuesday</option>
                                                                  <option value="3">Wednesday</option>
                                                                  <option value="4">Thursday</option>
                                                                  <option value="5">Friday</option>
                                                                  <option value="6">Saturday</option>
                                                                  <option value="7">Sunday</option>
                                                              </select>
                                                          </div>

                                                          <div class="col-md-4 mb-2">
                                                              <label for="price">Time</label>
                                                              <div class="input-group input-group-minimal">
                                                                  <input type="text"
                                                                         class="form-control timepicker"
                                                                         name="time"
                                                                         data-template="dropdown"
                                                                         data-show-seconds="false"
                                                                         data-default-time="08:00 AM"
                                                                         data-show-meridian="true"
                                                                         data-minute-step="5"
                                                                         data-second-step="5"/>
                                                                  <div class="input-group-addon">
                                                                      <a href="#">
                                                                          <i class="linecons-clock"></i>
                                                                      </a>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-2">
                                                    <span data-repeater-create="" class="btn btn-primary btn-sm hide-data-repeater-btn">
                                                        <span class="fa fa-plus"></span> Add
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-2 text-center">
                                    <button type="button" name="editClassBtn" id="editClassBtn" class="btn btn-primary" onclick="checkConfirmation();">Edit</button>
                                    <input type="submit" class="btn btn-primary " name="submitEditClassForm"
                                           id="submitEditClassForm" value="Save" style="display:none;"/>
                                </div>
                            </div>
                            <input type="hidden" name="level" id="level" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.classes.editConfirmationModal')
    @include('dashboard.classes.scripts')
@endsection
