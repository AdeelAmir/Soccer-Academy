@extends('dashboard.layouts.app')
@section('content')
<style>
        @media (min-width: 992px){
            .head {

            }
            .head_mob{
                display:none;
            }

        }

        @media (max-width: 767px) {
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
    <div class="page-content" id="editTrainingRoomQuizPage">
        <?php
        $TopicId = $Data[0];
        $Topic = $Data[1];
        $TrainingRoomRoleId = $Data[2];
        $Questions = $Data[3];
        ?>

        @if(Session::get('user_role') == 1 || 4)
            <form action="{{url('training-room/quiz/update')}}" method="post" id="editQuizForm"
                  enctype="multipart/form-data">
                @endif
                @csrf
                <input type="hidden" name="id" id="training_room_quiz_id" value="{{$quiz['id']}}"/>
                <input type="hidden" name="training_room_role_id" id="training_room_role_id" value="{{$RoleId}}"/>
                <input type="hidden" name="training_room_folder_id" id="training_room_folder_id" value="{{$FolderId}}"/>
                <section class="contact-area pb-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6 grid-margin mt-4">
                                <div class="col-12 mb-3" id="message-alert">
                                    @if(session()->has('message'))
                                        <div class="alert alert-success">
                                            {{ session('message') }}
                                        </div>
                                    @elseif(session()->has('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="head">Training Room > <span class="text-primary">Edit Quiz</span></span>
                                        <span class="head_mob"><span class="text-primary">Edit Quiz</span></span>
                                        @if($Role == 1)
                                            <button type="button" class="btn btn-primary float-right padd"
                                                    onclick="window.location.href='{{url('training-room/folder/details/' . $FolderId . '/' . $RoleId)}}';">
                                                <i class="fas fa-arrow-left mr-1"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3 mt-1">
                                                <label style="color:#979898;font-size:14px;" for="title">Title</label>
                                                <input type="text" name="title" id="title" class="form-control"
                                                       placeholder="Enter Quiz Title" value="{{$quiz['title']}}"
                                                       required/>
                                            </div>
                                            <div class="col-md-12 mb-3 mt-1">
                                                <div class="repeater-custom-show-hide">
                                                    <div data-repeater-list="questions">
                                                        @foreach($Questions as $index => $question)
                                                            <div data-repeater-item="" style="" class="mb-3">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for=""
                                                                                   class="add_quiz_question_label">Question {{$index + 1}}</label>
                                                                            <input type="text"
                                                                                   name="add_quiz_question"
                                                                                   class="form-control"
                                                                                   autocomplete="off"
                                                                                   value="{{$question->question}}"
                                                                                   required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Choice 1</label>
                                                                            <input type="text"
                                                                                   name="add_quiz_choice1"
                                                                                   class="form-control"
                                                                                   autocomplete="off"
                                                                                   value="{{$question->choice1}}"
                                                                                   required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label" for="">Choice
                                                                                2</label>
                                                                            <input type="text"
                                                                                   name="add_quiz_choice2"
                                                                                   class="form-control"
                                                                                   autocomplete="off"
                                                                                   value="{{$question->choice2}}"
                                                                                   required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Choice 3</label>
                                                                            <input type="text"
                                                                                   name="add_quiz_choice3"
                                                                                   class="form-control"
                                                                                   autocomplete="off"
                                                                                   value="{{$question->choice3}}"
                                                                                   required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Choice 4</label>
                                                                            <input type="text"
                                                                                   name="add_quiz_choice4"
                                                                                   class="form-control"
                                                                                   autocomplete="off"
                                                                                   value="{{$question->choice4}}"
                                                                                   required/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Answer</label>
                                                                            <select name="add_quiz_answer"
                                                                                    class="form-control" required>
                                                                                <option value="" disabled="disabled">
                                                                                    Select Answer
                                                                                </option>
                                                                                <option value="1" <?php if ($question->answer == 1) {
                                                                                    echo "selected";
                                                                                } ?>>Choice 1
                                                                                </option>
                                                                                <option value="2" <?php if ($question->answer == 2) {
                                                                                    echo "selected";
                                                                                } ?>>Choice 2
                                                                                </option>
                                                                                <option value="3" <?php if ($question->answer == 3) {
                                                                                    echo "selected";
                                                                                } ?>>Choice 3
                                                                                </option>
                                                                                <option value="4" <?php if ($question->answer == 4) {
                                                                                    echo "selected";
                                                                                } ?>>Choice 4
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <span data-repeater-delete=""
                                                                              class="btn btn-danger btn-sm float-right deletePayeeBtn">
                                                                            <span class="far fa-trash-alt mr-1"></span>&nbsp;
                                                                            Delete
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <div class="col-sm-12">
                                                    <span data-repeater-create=""
                                                          class="btn btn-primary btn-sm float-right">
                                                        <span class="fa fa-plus"></span>&nbsp;
                                                        Add
                                                    </span>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-center mt-3">
                                                <button type="submit" class="btn btn-primary">
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
    </div>
@endsection
