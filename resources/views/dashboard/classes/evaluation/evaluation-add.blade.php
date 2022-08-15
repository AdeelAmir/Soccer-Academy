@extends('dashboard.layouts.app')
@section('content')
<style media="screen">
  .questionTitle{
    font-size: 16px;font-weight: 600;color:black;
  }
  .primaryColor{
    color: #062C90;
  }
</style>
    <div class="container-fluid" id="addPlayerEvaluationPage">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Classes > Evaluation > <span class="text-primary">Player</span><br>
                        <br><span style="font-size: 14px;color: grey;">Location:&nbsp;{{$ClassDetails[0]->LocationName}}</span>
                      </h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2" style="float: right;"
                                onclick="window.location.href='{{route('classes.evaluation', [$ClassId])}}';"><i
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

                        <form action="{{route('classes.evaluation.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="class_id" id="class_id" value="{{$ClassId}}">
                            <input type="hidden" name="player_id" id="player_id" value="{{$PlayerId}}">
                            <input type="hidden" name="evaluation_date" id="evaluation_date" value="{{$EvaluationDate}}">
                            <div class="row mb-3">
                                <!-- Behavior - Start -->
                                <div class="col-md-12">
                                  <h4 class="text-left questionTitle">Behavior</h4>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">RESPECTIVE</label>
                                        <input type="text" class="form-control" name="respective"
                                               id="respective" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">ATTENTION</label>
                                        <input type="text" class="form-control" name="attention"
                                               id="attention" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">CONCENTRATION</label>
                                        <input type="text" class="form-control" name="concentration"
                                               id="concentration" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">LEADERSHIP</label>
                                        <input type="text" class="form-control" name="leadership"
                                               id="leadership" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">ENERGETIC</label>
                                        <input type="text" class="form-control" name="energetic"
                                               id="energetic" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">DISCIPLINE</label>
                                        <input type="text" class="form-control" name="discipline"
                                               id="discipline" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>
                                <!-- Behavior - End -->

                                <!-- Coordination - Start -->
                                <div class="col-md-12">
                                  <h4 class="text-left questionTitle">Coordination</h4>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">RUNNING</label>
                                        <input type="text" class="form-control" name="running"
                                               id="running" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>
                                <!-- Coordination - End -->

                                <!-- Pass / Kick / Control - Start -->
                                <div class="col-md-12">
                                  <h4 class="text-left questionTitle">Pass / Kick / Control</h4>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">PASSING AND RECEIVING</label>
                                        <input type="text" class="form-control" name="passing_receiving"
                                               id="passing_receiving" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">KICKING</label>
                                        <input type="text" class="form-control" name="kicking"
                                               id="kicking" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">BALL CONTROL</label>
                                        <input type="text" class="form-control" name="ball_control"
                                               id="ball_control" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">SHOOTING</label>
                                        <input type="text" class="form-control" name="shooting"
                                               id="shooting" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>
                                <!-- Pass / Kick / Control - End -->

                                <!-- Physical - Start -->
                                <div class="col-md-12">
                                  <h4 class="text-left questionTitle">Physical</h4>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">BALANCE</label>
                                        <input type="text" class="form-control" name="balance"
                                               id="balance" placeholder="Marks" onkeypress="return isNumberKey(event)" required/>
                                    </div>
                                </div>
                                <!-- Physical - End -->

                                <div class="col-md-12 mt-5 text-center">
                                    <input type="submit" class="btn btn-primary" name="submitUserEvaluationFormBtn" id="submitUserEvaluationFormBtn" value="Save" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.classes.scripts')
@endsection
