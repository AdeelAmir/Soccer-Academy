@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <?php
                    $ParentLocation = array();
                    $ParentLevel = array();
                    $ParentCategory = array();
                    $PlayerLocation = array();
                    $PlayerLevel = array();
                    $PlayerCategory = array();
                    $CoachLocation = array();
                    $CoachLevel = array();
                    $CoachCategory = array();

                    if ($UserPowers[0]->parent_location != "") {
                        $ParentLocation = explode(",", $UserPowers[0]->parent_location);
                    }
                    if ($UserPowers[0]->parent_level != "") {
                        $ParentLevel = explode(",", $UserPowers[0]->parent_level);
                    }
                    if ($UserPowers[0]->parent_category != "") {
                        $ParentCategory = explode(",", $UserPowers[0]->parent_category);
                    }

                    if ($UserPowers[0]->player_location != "") {
                        $PlayerLocation = explode(",", $UserPowers[0]->player_location);
                    }
                    if ($UserPowers[0]->player_level != "") {
                        $PlayerLevel = explode(",", $UserPowers[0]->player_level);
                    }
                    if ($UserPowers[0]->player_category != "") {
                        $PlayerCategory = explode(",", $UserPowers[0]->player_category);
                    }

                    if ($UserPowers[0]->coach_location != "") {
                        $CoachLocation = explode(",", $UserPowers[0]->coach_location);
                    }
                    if ($UserPowers[0]->coach_level != "") {
                        $CoachLevel = explode(",", $UserPowers[0]->coach_level);
                    }
                    if ($UserPowers[0]->coach_category != "") {
                        $CoachCategory = explode(",", $UserPowers[0]->coach_category);
                    }
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Power > <span class="text-primary">User</span></h3>
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
                                <strong>Message:</strong> {{\Illuminate\Support\Facades\Session::get('success')}}
                            </div>
                        @elseif(\Illuminate\Support\Facades\Session::has('error'))
                            <div class="alert alert-danger" id="message-alert">
                                <strong>Message:</strong> {{\Illuminate\Support\Facades\Session::get('error')}}
                            </div>
                        @endif

                        <form action="{{route('users.power.user.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id" value="{{$UserId}}">
                            <div class="row mb-3">
                                <!-- Parents -->
                                <div class="col-md-12">
                                  <h4 class="text-black">Parents</h4>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="title">Locations</label>
                                    <select class="form-control select2" name="parent_location[]" id="parent_location" multiple>
                                      @foreach($Locations as $index => $item)
                                          <option value="{{$item->id}}" <?php if(in_array($item->id, $ParentLocation)){echo "selected";} ?> >{{$item->name}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="title">Levels</label>
                                    <select class="form-control select2" name="parent_level[]" id="parent_level" multiple>
                                      @foreach($Levels as $level)
                                          <option value="{{$level->id}}" <?php if(in_array($level->id, $ParentLevel)){echo "selected";} ?> >{{$level->symbol}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="title">Categories</label>
                                    <select class="form-control select2" name="parent_category[]" id="parent_category" multiple>
                                      @foreach($Categories as $category)
                                          <option value="{{$category->id}}" <?php if(in_array($category->id, $ParentCategory)){echo "selected";} ?> >{{$category->title}}</option>
                                      @endforeach
                                    </select>
                                </div>

                                <!-- Players -->
                                <div class="col-md-12 mt-4">
                                  <h4 class="text-black">Players</h4>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="title">Locations</label>
                                    <select class="form-control select2" name="player_location[]" id="player_location" multiple>
                                      @foreach($Locations as $index => $item)
                                          <option value="{{$item->id}}" <?php if(in_array($item->id, $PlayerLocation)){echo "selected";} ?> >{{$item->name}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="title">Levels</label>
                                    <select class="form-control select2" name="player_level[]" id="player_level" multiple>
                                      @foreach($Levels as $level)
                                          <option value="{{$level->id}}" <?php if(in_array($level->id, $PlayerLevel)){echo "selected";} ?> >{{$level->symbol}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="title">Categories</label>
                                    <select class="form-control select2" name="player_category[]" id="player_category" multiple>
                                      @foreach($Categories as $category)
                                          <option value="{{$category->id}}" <?php if(in_array($category->id, $PlayerCategory)){echo "selected";} ?> >{{$category->title}}</option>
                                      @endforeach
                                    </select>
                                </div>

                                <!-- Coaches -->
                                <div class="col-md-12 mt-4">
                                  <h4 class="text-black">Coaches</h4>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="title">Locations</label>
                                    <select class="form-control select2" name="coach_location[]" id="coach_location" multiple>
                                      @foreach($Locations as $index => $item)
                                          <option value="{{$item->id}}" <?php if(in_array($item->id, $CoachLocation)){echo "selected";} ?> >{{$item->name}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="title">Levels</label>
                                    <select class="form-control select2" name="coach_level[]" id="coach_level" multiple>
                                      @foreach($Levels as $level)
                                          <option value="{{$level->id}}" <?php if(in_array($level->id, $CoachLevel)){echo "selected";} ?> >{{$level->symbol}}</option>
                                      @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <label for="title">Categories</label>
                                    <select class="form-control select2" name="coach_category[]" id="coach_category" multiple>
                                      @foreach($Categories as $category)
                                          <option value="{{$category->id}}" <?php if(in_array($category->id, $CoachCategory)){echo "selected";} ?> >{{$category->title}}</option>
                                      @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mt-5 text-center">
                                    <input type="submit" class="btn btn-primary " name="submitUpdateUserPowerForm"
                                           id="submitUpdateUserPowerForm" value="Save"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
