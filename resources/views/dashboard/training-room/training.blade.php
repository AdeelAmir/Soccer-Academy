@extends('dashboard.layouts.app')
@section('content')
    <style>
        .overlay {
            position: relative;
            z-index: 9;
            width: 750px;
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
    <?php
    /*All Training Assignments*/
    $AllTrainingAssignment = \Illuminate\Support\Facades\DB::table('training_assignments')
        ->join('training_rooms', 'training_assignments.assignment_id', '=', 'training_rooms.id')
        ->where('user_id', '=', \Illuminate\Support\Facades\Auth::id())
        ->where('training_assignment_folder_id', '=', $CourseId)
        ->select('training_assignments.id AS TrainingAssignmentId', 'training_assignments.assignment_id AS AssignmentId', 'training_assignments.assignment_type', 'training_assignments.status', 'training_rooms.*')
        ->get();
    ?>
    <input type="hidden" name="training_course_id" id="training_course_id" value="{{$CourseId}}">
    <div class="page-content">
        <section class="contact-area pb-5">
            <div class="container">
                <div class="row">
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

                    <div class="col-md-12 grid-margin stretch-card mt-5">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Training Room > <span class="text-primary">{{$CourseName}} > ({{round($CourseCompletionRate)}}%)</span>
                                <?php
                                $Url = route('view.faq');
                                $BackUrl = url('/training');
                                ?>
                                <button type="button" class="btn btn-primary float-right"
                                        onclick="window.location.href='{{$BackUrl}}';">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                </button>
                                <button type="button" class="btn btn-primary mr-2 float-right"
                                        onclick="window.location.href='{{$Url}}';">
                                    <i class="fas fa-question-circle mr-1"></i>
                                </button>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    {{--Training Room Sidebar--}}
                                    <div class="col-md-3">
                                        <ul class="StepProgress">
                                            <?php
                                            $StepCount = 1;
                                            $TotalAssignments = sizeof($AllTrainingAssignment);
                                            $CompletedAssignments = 0;
                                            foreach ($AllTrainingAssignment as $item) {
                                                if ($item->status == 1) {
                                                    $CompletedAssignments++;
                                                }
                                            }
                                            $RemainingAssignments = $TotalAssignments - $CompletedAssignments;
                                            ?>
                                            @foreach($AllTrainingAssignment as $assignment)
                                                @if($assignment->status == 1)
                                                    {{--Assignment Done--}}
                                                    @if($TotalAssignments == $StepCount)
                                                        <li class="StepProgress-item is-done cursor-pointer"
                                                            id="barStep_{{$StepCount}}" onclick="SetStepActive(this);">
                                                            <strong>{{$assignment->title}}</strong></li>
                                                    @else
                                                        <li class="StepProgress-item is-done cursor-pointer"
                                                            id="barStep_{{$StepCount}}" onclick="SetStepActive(this);">
                                                            <strong>{{$assignment->title}}</strong></li>
                                                    @endif
                                                @else
                                                    {{--Assignment Pending--}}
                                                    @if(intval($CompletedAssignments + 1) == $StepCount)
                                                        <li class="StepProgress-item complete current-task cursor-pointer"
                                                            id="barStep_{{$StepCount}}" onclick="SetStepActive(this);">
                                                            <strong>{{$assignment->title}}</strong></li>
                                                    @else
                                                        <li class="StepProgress-item" id="barStep_{{$StepCount}}">
                                                            <strong>{{$assignment->title}}</strong></li>
                                                    @endif
                                                @endif
                                                <?php
                                                $StepCount++;
                                                ?>
                                            @endforeach
                                            @if($TotalAssignments == $CompletedAssignments)
                                                <li class="StepProgress-item cursor-pointer is-done" id="barStep_{{$StepCount}}" onclick="SetStepActive(this, 'last');"><strong>Done</strong></li>
                                            @else
                                                <li class="StepProgress-item"><strong>Done</strong></li>
                                            @endif
                                        </ul>
                                    </div>
                                    {{--Training Room Content--}}
                                    <div class="col-md-9">
                                        <?php
                                        $StepCount = 1;
                                        $TotalAssignments = sizeof($AllTrainingAssignment);
                                        $CompletedAssignments = 0;
                                        foreach ($AllTrainingAssignment as $item) {
                                            if ($item->status == 1) {
                                                $CompletedAssignments++;
                                            }
                                        }
                                        $RemainingAssignments = $TotalAssignments - $CompletedAssignments;
                                        $Display = "style='display: none;'";
                                        ?>
                                        @foreach($AllTrainingAssignment as $assignment)
                                            @if($assignment->assignment_type == 'video')
                                            <div class="w-100"
                                                 id="barStepContent_{{$StepCount}}" <?php echo intval($CompletedAssignments + 1) == $StepCount ? '' : $Display; ?>>
                                                <h6 class="panel-title">
                                                    Video
                                                </h6>
                                                <div class="row mt-4">
                                                    <input type="hidden" name="assignmentId" id="assignmentId_{{$StepCount}}"
                                                           value="{{$assignment->TrainingAssignmentId}}">
                                                    <div class="col-md-12">
                                                        <section class="sec-block-200 pt-0 pb-0 half-bg-top">
                                                            <div class="container">
                                                                <div class="videeo-sec overlay">
                                                                    <img src="{{asset('public/assets/images/msc-img2.jpg')}}"
                                                                         alt="">
                                                                    <div class="vide-cap">
                                                                        <a href="{{$assignment->video_url}}" title=""
                                                                           class="html5lightbox">
                                                                            <img src="{{asset('public/assets/images/play-icon.png')}}"
                                                                                 alt="">
                                                                        </a>
                                                                    </div>
                                                                </div><!--videeo-sec end-->
                                                            </div>
                                                        </section>
                                                    </div>
                                                    @if($assignment->status != 1)
                                                        <div class="col-md-12 mt-3 text-center">
                                                            <input type="button" value="Mark As Complete"
                                                                   class="btn btn-primary"
                                                                   onclick="MarkVideoAsComplete('{{$StepCount}}');"/>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @elseif($assignment->assignment_type == 'article')
                                            <div class="w-100"
                                                 id="barStepContent_{{$StepCount}}" <?php echo intval($CompletedAssignments + 1) == $StepCount ? '' : $Display; ?>>
                                                <h4>
                                                    {!! $assignment->title !!}
                                                </h4>
                                                <div class="row mt-4">
                                                    <input type="hidden" name="assignmentId" id="assignmentId_{{$StepCount}}"
                                                           value="{{$assignment->TrainingAssignmentId}}">
                                                    <div class="col-md-12">
                                                        {!! $assignment->article_details !!}
                                                    </div>
                                                    @if($assignment->status != 1)
                                                        <div class="col-md-12 mt-2 text-center">
                                                            <input type="button" value="Mark As Complete"
                                                                   class="btn btn-primary"
                                                                   onclick="MarkArticleAsComplete('{{$StepCount}}');"/>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @elseif($assignment->assignment_type == 'quiz')
                                            <div class="w-100"
                                                 id="barStepContent_{{$StepCount}}" <?php echo intval($CompletedAssignments + 1) == $StepCount ? '' : $Display; ?>>
                                                <h4>
                                                    {!! $assignment->title !!}
                                                </h4>
                                                <div class="row mt-4">
                                                    <input type="hidden" name="assignmentId" id="assignmentId_{{$StepCount}}"
                                                           value="{{$assignment->TrainingAssignmentId}}">
                                                    <div class="col-md-12">
                                                        <?php
                                                        $Count = 0;
                                                        $QuizQuestions = \Illuminate\Support\Facades\DB::table('training_quizzes')
                                                            ->where('topic_id', '=', $assignment->AssignmentId)
                                                            ->get();
                                                        ?>
                                                        @foreach($QuizQuestions as $question)
                                                            <div class="mb-1" id="quizQuestionDiv{{$StepCount}}{{$Count}}"
                                                                 style="padding: 5px;">
                                                                <p class="mb-1" style="font-size: 15px; font-weight: bold;">
                                                                    Q.&nbsp;{{$Count + 1}}&nbsp;&nbsp;{{$question->question}}</p>
                                                                <div class="question-options-div">
                                                                    @if($question->choice1 != '')
                                                                        <label class="question-option-label mb-1 w-100">
                                                                            <input type="radio"
                                                                                   name="question{{$StepCount}}{{$Count}}"
                                                                                   value="1">&nbsp;&nbsp;{{$question->choice1}}
                                                                        </label>
                                                                    @endif
                                                                    @if($question->choice2 != '')
                                                                        <label class="question-option-label mb-1 w-100">
                                                                            <input type="radio"
                                                                                   name="question{{$StepCount}}{{$Count}}"
                                                                                   value="2">&nbsp;&nbsp;{{$question->choice2}}
                                                                        </label>
                                                                    @endif
                                                                    @if($question->choice3 != '')
                                                                        <label class="question-option-label mb-1 w-100">
                                                                            <input type="radio"
                                                                                   name="question{{$StepCount}}{{$Count}}"
                                                                                   value="3">&nbsp;&nbsp;{{$question->choice3}}
                                                                        </label>
                                                                    @endif
                                                                    @if($question->choice4 != '')
                                                                        <label class="question-option-label mb-1 w-100">
                                                                            <input type="radio"
                                                                                   name="question{{$StepCount}}{{$Count}}"
                                                                                   value="4">&nbsp;&nbsp;{{$question->choice4}}
                                                                        </label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="questionAnswer{{$StepCount}}{{$Count}}"
                                                                   id="questionAnswer{{$StepCount}}{{$Count}}" value="{{$question->answer}}">
                                                            <?php
                                                            $Count++;
                                                            ?>
                                                        @endforeach
                                                        <input type="hidden" name="questionsCount{{$StepCount}}" id="questionsCount{{$StepCount}}"
                                                               value="{{$Count}}"/>
                                                    </div>
                                                </div>
                                                @if($assignment->status != 1)
                                                    <div class="col-md-12 text-center">
                                                        <input type="button" value="Mark As Complete" class="btn btn-primary"
                                                               onclick="MarkQuizAsComplete('{{$StepCount}}');"/>
                                                    </div>
                                                @endif
                                            </div>
                                            @endif
                                            <?php
                                                $StepCount++;
                                            ?>
                                        @endforeach
                                        @if($TotalAssignments == $CompletedAssignments)
                                            <div class="w-100" id="barStepContent_{{$StepCount}}">
                                                <h4>
                                                    Training Room
                                                </h4>
                                                <div class="row mt-4">
                                                    <div class="col-md-12 text-center">
                                                        <b>CONGRATULATIONS!</b>&nbsp;&nbsp;All the tasks are completed
                                                        successfully!
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('dashboard.training-room.scripts')
    @include('dashboard.training-room.quizResultsModal')
@endsection
