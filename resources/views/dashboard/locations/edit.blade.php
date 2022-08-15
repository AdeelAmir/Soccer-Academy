@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
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
            .padd{
                padding: 4px 9px;
            }
        }
    </style>
    <div class="container-fluid" id="EditPlayerLocationPage">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3 head">Locations > {{$Location[0]->name}} > <span class="text-primary">View</span>
                            <br><br><span style="font-size: 14px;color: grey;">Manager:&nbsp;{{$LocationManager}}</span>
                        </h3>
                        <h3 class="panel-title pt-2 head_mob">{{$Location[0]->name}} > <span class="text-primary">View</span>
                            <br><br><span style="font-size:11px;color: grey;" class="mt-0">Manager:&nbsp;{{$LocationManager}}</span>
                        </h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2 padd" style="float: right;"
                                onclick="window.location.href='{{route('locations')}}';"><i
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

                        <form action="{{route('locations.update')}}" method="post"
                              id="editLocationForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="hiddenLocationId" value="{{$Location[0]->id}}">
                            <div class="custom-row">
                                <div class="custom-col-4 mb-2">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           placeholder="Location Name" value="{{$Location[0]->name}}" maxlength="100"
                                           required disabled/>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="state">State</label>
                                    <select class="form-control select2" name="state" id="state"
                                            onchange="LoadStateCountyCity();" required disabled>
                                        <option value="" selected>Select State</option>
                                        @foreach($States as $state)
                                            @if($Location[0]->state == $state->name)
                                                <option value="{{$state->name}}" selected>{{$state->name}}</option>
                                            @else
                                                <option value="{{$state->name}}">{{$state->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2" id="citySection" <?php if ($Location[0]->city != '') {
                                    echo '';
                                } else {
                                    echo 'style="display: none;"';
                                } ?>>
                                    <label for="city">City</label>
                                    <select name="city" id="city" class="form-control select2" disabled>
                                        <option value="" selected>Select City</option>
                                        @foreach($cities as $index => $item)
                                            @if($Location[0]->city == $item->city)
                                                <option value="{{$item->city}}" selected>{{$item->city}}</option>
                                            @else
                                                <option value="{{$item->city}}">{{$item->city}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="street">Street</label>
                                    <input type="text" name="street" id="street" class="form-control"
                                           placeholder="Street" maxlength="100" value="{{$Location[0]->street}}"
                                           required disabled/>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="zipcode">Zip Code</label>
                                    <input type="number" step="any" name="zipcode" id="zipcode"
                                           class="form-control"
                                           placeholder="Zip Code" onkeypress="limitKeypress(event,this.value,5)"
                                           value="{{$Location[0]->zipcode}}"
                                           onblur="limitZipCodeCheck();" required disabled/>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="status">Status</label>
                                    <br>
                                    <input type="checkbox" class="iswitch iswitch-primary" value="1" name="status"
                                           id="status" <?php if($Location[0]->status == 1) { echo 'checked'; } ?> disabled>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="mt-4" style="color: black;">Levels & Categories</h4>
                                    <hr class="mt-2 mb-2">
                                </div>
                                <?php
                                  $LocationLevelsCounter = 0;
                                  foreach ($LocationLevels as $key => $value) {
                                    $LocationLevelsCounter++;
                                    $SelectedCategories = explode(",", $value->category);
                                ?>
                                <div class="col-md-12" id="EditLocationLevelsBlock">
                                  <div class="row" id="locationLevel_{{$LocationLevelsCounter}}">
                                      <div class="col-md-4 mb-2">
                                          <label for="level">Level</label>
                                          <select class="form-control select2" name="level" id="level_{{$LocationLevelsCounter}}">
                                            <option value="">Select</option>
                                            <?php
                                            foreach($Levels as $level){
                                            ?>
                                              <option value="{{$level->id}}" <?php if ($level->id == $value->level) {
                                                  echo 'selected';
                                              } ?>>{{$level->title}}</option>
                                            <?php
                                            }
                                            ?>
                                          </select>
                                      </div>
                                      <div class="col-md-4 mb-2">
                                          <label for="category">Category</label>
                                          <select class="form-control select2" name="category[]" id="category_{{$LocationLevelsCounter}}" multiple>
                                            <?php
                                            foreach($Categories as $category){
                                            ?>
                                              <option value="{{$category->id}}" <?php if (in_array($category->id, $SelectedCategories)) {
                                                  echo 'selected';
                                              } ?>>{{$category->title}}</option>
                                            <?php
                                            }
                                            ?>
                                          </select>
                                      </div>
                                      <div class="col-md-4 mb-2">
                                          <label> &nbsp;</label>
                                          <div>
                                              <span class="btn btn-danger btn-sm hide-data-repeater-btn" id="removeLocationLevel_{{$LocationLevelsCounter}}" onclick="RemoveLocationLevel(this.id);">
                                                  <span class="far fa-trash-alt mr-1"></span> Delete
                                              </span>
                                          </div>
                                      </div>
                                  </div>
                                </div>
                                <?php
                                  }
                                ?>
                                <input type="hidden" name="_locationLevelCounter" id="_locationLevelCounter" value="{{$LocationLevelsCounter}}" />
                                <div class="col-md-12 mb-2">
                                    <span class="btn btn-primary btn-sm hide-data-repeater-btn" onclick="MakeEditLocationLevels();">
                                        <span class="fa fa-plus"></span> Add
                                    </span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center mt-5">
                                    <button type="button" name="editLocationBtn" id="editLocationBtn" class="btn btn-primary" onclick="checkConfirmation();">Edit</button>
                                    <input type="submit" class="btn btn-primary" name="submitBtn" id="submitBtn" value="Update" style="display:none;"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        let categories = JSON.parse('<?= $Categories; ?>');
        let levels = JSON.parse('<?= $Levels; ?>');
    </script>
    @include('dashboard.locations.editConfirmationModal')
    @include('dashboard.locations.scripts')
@endsection
