@extends('dashboard.layouts.app')
@section('content')
    <style>
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3 head">Billing > Packages > <span class="text-primary">New</span></h3>
                        <h3 class="panel-title pt-2 head_mob">Packages > <span class="text-primary">New</span></h3>
                        <button type="button" class="btn btn-primary mb-0 padd" style="float: right;"
                                onclick="window.location.href='{{route('packages')}}';"><i
                                    class="fas fa-arrow-left"></i></button>
                        <button type="button" class="btn btn-primary mb-0 mr-2 padd" style="float: right;"
                                onclick="ShowFeesModal();"><i
                                    class="fas fa-cog"></i></button>
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

                        <form action="{{route('packages.store')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title" maxlength="100" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="limit">Limit</label>
                                    <input type="number" step="any" class="form-control" name="limit" id="limit" maxlength="100" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="invitation">Invitation Only</label>
                                    <br>
                                    <input type="checkbox" class="iswitch iswitch-primary" value="0" name="invitation"
                                           id="invitation">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="startDate">Start Date</label>
                                    <input type="text" class="form-control datepicker" name="startDate" id="startDate" data-format="mm/dd/yyyy" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="endDate">End Date</label>
                                    <input type="text" class="form-control datepicker" name="endDate" id="endDate" data-format="mm/dd/yyyy" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="level">Level</label>
                                    <select class="form-control select2" name="level" id="level" required>
                                        @foreach($Levels as $level)
                                            <option value="{{$level->id}}">{{$level->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="category">Category</label>
                                    <select class="form-control select2" name="category[]" id="category" multiple required>
                                        <option value="" disabled>Select Category</option>
                                        @foreach($Categories as $category)
                                            <option value="{{$category->id}}">{{$category->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <label for="location">Location</label>
                                    <select class="form-control select2" name="location[]" id="location" multiple required>
                                        <option value="" disabled>Select</option>
                                        @foreach($Locations as $index => $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{--<div class="col-md-4">
                                    <label for="class">Class</label>
                                    <select class="form-control select2" name="class" id="class" onchange="SetClassItemCost();">
                                        <option value="" selected>Select</option>
                                        @foreach($Classes as $index => $item)
                                            <option value="{{$item->id}}" data-registration="{{$item->registration_fee}}" data-monthly="{{$item->monthly_fee}}">{{$item->class_id . ' - ' . $item->title}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="hiddenRegistrationFee" id="hiddenRegistrationFee" value="0">
                                    <input type="hidden" name="hiddenMonthlyFee" id="hiddenMonthlyFee" value="0">
                                    <input type="hidden" name="hiddenClassCost" id="hiddenClassCost" value="0">
                                </div>--}}
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4 style="color: black;">Items</h4>
                                    <hr class="mt-2 mb-2">
                                </div>

                                <div class="col-md-12">
                                    <div class="repeater-custom-show-hide">
                                        <div data-repeater-list="item">
                                            <div data-repeater-item="" style="">
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <label for="item">Item</label>
                                                        <input type="text"
                                                               name="item"
                                                               id="item"
                                                               maxlength="100"
                                                               class="form-control" />
                                                    </div>

                                                    <div class="col-md-4 mb-2">
                                                        <label for="price">Price</label>
                                                        <input type="text"
                                                               name="price"
                                                               id="price"
                                                               maxlength="100"
                                                               class="form-control" />
                                                    </div>

                                                    <div class="col-md-4 mb-2">
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
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <span data-repeater-create="" class="btn btn-primary btn-sm">
                                                    <span class="fa fa-plus"></span> Item
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <input type="submit" class="btn btn-primary" value="Save"/>
                                </div>
                            </div>

                            @include('dashboard.billing.packages.fees')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.billing.packages.scripts')
@endsection
