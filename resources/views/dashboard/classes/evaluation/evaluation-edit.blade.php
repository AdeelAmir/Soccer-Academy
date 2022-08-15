@extends('dashboard.layouts.app')
@section('content')
<style media="screen">
  .questionTitle{
    font-size: 16px;font-weight: 600;color:black;
  }
  .primaryColor{
    color: #062C90;
  }
  @media (min-width: 992px){

      .head {

      }
      .head_mob{
          display:none;
      }
  }


  @media (max-width: 767px) {
      .head {
          display: none;
      }

      .head_mob {

      }
      .padd{
          padding: 4px 9px;
      }
  }
</style>
    <div class="container-fluid" id="addPlayerEvaluationPage">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3 head">Classes > Evaluation > <span class="text-primary">Player</span><br>
                        <br><span style="font-size: 14px;color: grey;">Class Title:&nbsp;{{$ClassDetails[0]->title}}</span>
                        <br><span style="font-size: 14px;color: grey;">Player Name:&nbsp;{{$PlayerDetails[0]->firstName}} {{$PlayerDetails[0]->lastName}}</span>
                        <br><span style="font-size: 14px;color: grey;">Evaluation Date:&nbsp;{{\Carbon\Carbon::parse($EvaluationDate)->format('m/d/Y')}}</span>
                      </h3>
                        <h3 class="panel-title pt-2 head_mob" style="font-size: 14px">Classes > Evaluation > <span class="text-primary">Player</span><br>
                            <br><span style="font-size: 14px;color: grey;">Class Title:&nbsp;{{$ClassDetails[0]->title}}</span>
                            <br><span style="font-size: 14px;color: grey;">Player Name:&nbsp;{{$PlayerDetails[0]->firstName}} {{$PlayerDetails[0]->lastName}}</span>
                            <br><span style="font-size: 14px;color: grey;">Evaluation Date:&nbsp;{{\Carbon\Carbon::parse($EvaluationDate)->format('m/d/Y')}}</span>
                        </h3>
                        <button type="button" class="btn btn-primary mb-0 mr-2 mt-3 padd" style="float: right;"
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

                        <form action="{{route('classes.evaluation.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="evaluation_id" value="{{$EvaluationDetails[0]->id}}">
                            <input type="hidden" name="class_id" id="class_id" value="{{$ClassId}}">
                            <input type="hidden" name="player_id" id="player_id" value="{{$PlayerId}}">
                            <input type="hidden" name="evaluation_date" id="evaluation_date" value="{{$EvaluationDate}}">
                            <input type="hidden" name="old_report_pdf" id="old_report_pdf" value="{{$EvaluationDetails[0]->report_pdf}}">
                            <input type="hidden" name="report_no" value="{{$EvaluationDetails[0]->report_no}}">
                            <div class="row mb-3">
                                <!-- Behavior - Start -->
                                <div class="col-md-12">
                                  <h4 class="text-left questionTitle">Behavior</h4>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">RESPECTIVE</label>
                                        <input type="text" class="form-control" name="respective"
                                               value="{{$EvaluationDetails[0]->respective}}"
                                               disabled
                                               id="respective" placeholder="Marks" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">ATTENTION</label>
                                        <input type="text" class="form-control" name="attention"
                                               value="{{$EvaluationDetails[0]->attention}}"
                                               disabled
                                               id="attention" placeholder="Marks" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">CONCENTRATION</label>
                                        <input type="text" class="form-control" name="concentration"
                                               value="{{$EvaluationDetails[0]->concentration}}"
                                               disabled
                                               id="concentration" placeholder="Marks" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">LEADERSHIP</label>
                                        <input type="text" class="form-control" name="leadership"
                                               value="{{$EvaluationDetails[0]->leadership}}"
                                               disabled
                                               id="leadership" placeholder="Marks" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">ENERGETIC</label>
                                        <input type="text" class="form-control" name="energetic"
                                               value="{{$EvaluationDetails[0]->energetic}}"
                                               disabled
                                               id="energetic" placeholder="Marks" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">DISCIPLINE</label>
                                        <input type="text" class="form-control" name="discipline"
                                               value="{{$EvaluationDetails[0]->discipline}}"
                                               disabled
                                               id="discipline" placeholder="Marks" required/>
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
                                               value="{{$EvaluationDetails[0]->running}}"
                                               disabled
                                               id="running" placeholder="Marks" required/>
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
                                               value="{{$EvaluationDetails[0]->passing_receiving}}"
                                               disabled
                                               id="passing_receiving" placeholder="Marks" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">KICKING</label>
                                        <input type="text" class="form-control" name="kicking"
                                               value="{{$EvaluationDetails[0]->kicking}}"
                                               disabled
                                               id="kicking" placeholder="Marks" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">BALL CONTROL</label>
                                        <input type="text" class="form-control" name="ball_control"
                                               value="{{$EvaluationDetails[0]->ball_control}}"
                                               disabled
                                               id="ball_control" placeholder="Marks" required/>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-4">
                                    <div class="form-group">
                                        <label for="">SHOOTING</label>
                                        <input type="text" class="form-control" name="shooting"
                                               value="{{$EvaluationDetails[0]->shooting}}"
                                               disabled
                                               id="shooting" placeholder="Marks" required/>
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
                                               value="{{$EvaluationDetails[0]->balance}}"
                                               disabled
                                               id="balance" placeholder="Marks" required/>
                                    </div>
                                </div>
                                <!-- Physical - End -->

                                <div class="col-md-12 mt-5 text-center">
                                    <button type="button" name="editEvaluationBtn" id="editEvaluationBtn" class="btn btn-primary" onclick="checkConfirmation();">Edit</button>
                                    <input type="submit" class="btn btn-primary " name="submitEditPlayerEvaluationBtn"
                                           id="submitEditPlayerEvaluationBtn" value="Save" style="display:none;"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.classes.evaluation.editConfirmationModal')
    @include('dashboard.classes.scripts')
@endsection
