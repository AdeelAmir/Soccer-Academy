@extends('dashboard.layouts.app')
@section('content')
    <style>
        @media (max-width: 767px) {

            .padd{
                padding: 4px 9px;
            }
        }
    </style>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Training Room</h3>
                </div>
                <div class="panel-body">
                        <table id="admin_training_room_roles" class="table table-bordered text-center">
                            <thead>
                            <tr>
                                <th style="width: 50%;">Role</th>
                                <th style="width: 50%;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($Role == 1)
                            {{--<tr>
                                <td>Global Manager</td>
                                <td>
                                    <button class="btn btn btn-primary mr-2 padd" id="role_2_Global Manager"
                                            onclick="openTrainingRoom(this.id);"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>--}}
                            <tr>
                                <td>Manager</td>
                                <td>
                                    <button class="btn btn btn-primary padd"
                                            id="role_3_Manager"
                                            onclick="openTrainingRoom(this.id);"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Coach</td>
                                <td>
                                    <button class="btn btn btn-primary padd"
                                            id="role_4_Coach"
                                            onclick="openTrainingRoom(this.id);"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Parent</td>
                                <td>
                                    <button class="btn btn btn-primary padd" id="role_5_Parent"
                                            onclick="openTrainingRoom(this.id);"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            @endif
                            @if($Role == 1 || $Role == 4 )
                            <tr>
                                <td>Player</td>
                                <td>
                                    <button class="btn btn btn-primary padd" id="role_6_Player"
                                            onclick="openTrainingRoom(this.id);"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            @endif
                            @if($Role == 1)
                            <tr>
                                <td>Affiliates</td>
                                <td>
                                    <button class="btn btn btn-primary padd" id="role_7_Affiliates"
                                            onclick="openTrainingRoom(this.id);"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Virtual Assistant</td>
                                <td>
                                    <button class="btn btn btn-primary padd" id="role_8_Virtual Assistant"
                                            onclick="openTrainingRoom(this.id);"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            @endif
                            @if($Role == 1 || $Role == 4 )
                            <tr style="background-color: #f2f2f2;">
                                <td>Knowledge Zone</td>
                                <td>
                                    <?php
                                    $Url = url('training-room/faqs');
                                    ?>
                                    <button class="btn btn-primary padd "
                                            onclick="window.location.href='{{$Url}}';"><i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.training-room.scripts')
@endsection
