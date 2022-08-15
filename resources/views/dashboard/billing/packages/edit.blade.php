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
                padding: 4px 7px;
            }
        }
    </style>
    <div class="container-fluid">
        <div class="row" id="EditPackagePage">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3 head">Billing > Packages > <span class="text-primary">View</span></h3>
                        <h3 class="panel-title pt-2 head_mob" style="font-size: 14px">Billing > Packages > <span class="text-primary">View</span></h3>
                        <button type="button" class="btn btn-primary float-right mb-0 padd"
                                onclick="window.location.href='{{route('packages')}}';"><i
                                    class="fas fa-arrow-left"></i></button>
                        <button type="button" class="btn btn-primary float-right mb-0 mr-2 padd"
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

                        <form action="{{route('packages.update')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="hiddenPackageId" value="{{$Package[0]->id}}">

                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                           maxlength="100" value="{{$Package[0]->title}}" disabled required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="limit">Limit</label>
                                    <input type="number" step="any" class="form-control" name="limit"
                                           id="limit" maxlength="100" value="{{$Package[0]->limit}}" disabled required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="invitation">Invitation Only</label>
                                    <br>
                                    <input type="checkbox" class="iswitch iswitch-primary" value="0" name="invitation" id="invitation"
                                           <?php if($Package[0]->invitation == 'Yes'){ echo 'checked'; } ?> disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label for="startDate">Start Date</label>
                                    <input type="text" class="form-control datepicker" name="startDate"
                                           id="startDate" data-format="mm/dd/yyyy"
                                           value="{{\Carbon\Carbon::parse($Package[0]->start_date)->format('m/d/Y')}}"
                                           disabled required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="endDate">End Date</label>
                                    <input type="text" class="form-control datepicker" name="endDate"
                                           id="endDate" data-format="mm/dd/yyyy"
                                           value="{{\Carbon\Carbon::parse($Package[0]->end_date)->format('m/d/Y')}}"
                                           disabled required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="level">Level</label>
                                    <select class="form-control select2" name="level" id="level" disabled required>
                                        <?php
                                            $SelectedLevels = explode(',', $Package[0]->level);
                                        ?>
                                        @foreach($Levels as $level)
                                            <option value="{{$level->id}}" <?php if(in_array($level->id, $SelectedLevels)) { echo 'selected'; } ?>>{{$level->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <?php
                                $PackageCategories = explode(",", $Package[0]->category);
                                $PackageLocations = explode(",", $Package[0]->location);
                                ?>
                                <div class="col-md-4 mb-2">
                                    <label for="category">Category</label>
                                    <select class="form-control select2" name="category[]" id="category" multiple disabled required>
                                        <option value="" disabled>Select Category</option>
                                        @foreach($Categories as $category)
                                            @if(in_array($category->id, $PackageCategories))
                                                <option value="{{$category->id}}" selected>{{$category->title}}</option>
                                            @else
                                                <option value="{{$category->id}}">{{$category->title}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <label for="location">Location</label>
                                    <select class="form-control select2" name="location[]" id="location" multiple disabled required>
                                        <option value="" disabled>Select</option>
                                        @foreach($Locations as $index => $item)
                                          @if(in_array($item->id, $PackageLocations))
                                            <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                          @else
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                          @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4 style="color: black;">Items</h4>
                                    <hr class="mt-2 mb-3">
                                </div>

                                <div class="col-md-12">
                                    <div class="repeater-custom-show-hide">
                                        <div data-repeater-list="item">
                                            @if(sizeof($PackageItems) > 0)
                                                @foreach($PackageItems as $index => $item)
                                                    @if($item->type == null)
                                                        <div data-repeater-item="" style="">
                                                            <div class="row">
                                                                <div class="col-md-4 mb-2">
                                                                    <label for="item">Item</label>
                                                                    <input type="text"
                                                                           name="item"
                                                                           id="item"
                                                                           maxlength="100"
                                                                           value="{{$item->item}}"
                                                                           class="form-control"/>
                                                                </div>

                                                                <div class="col-md-4 mb-2">
                                                                    <label for="price">Price</label>
                                                                    <input type="text"
                                                                           name="price"
                                                                           id="price"
                                                                           maxlength="100"
                                                                           value="{{$item->price}}"
                                                                           class="form-control"/>
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
                                                    @endif
                                                @endforeach
                                            @else
                                                <div data-repeater-item="" style="">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label for="item">Item</label>
                                                            <input type="text"
                                                                   name="item"
                                                                   id="item"
                                                                   maxlength="100"
                                                                   class="form-control"/>
                                                        </div>

                                                        <div class="col-md-4 mb-2">
                                                            <label for="price">Price</label>
                                                            <input type="text"
                                                                   name="price"
                                                                   id="price"
                                                                   maxlength="100"
                                                                   class="form-control"/>
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
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <span data-repeater-create="" class="btn btn-primary btn-sm hide-data-repeater-btn">
                                                    <span class="fa fa-plus"></span> Item
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center mt-5">
                                    <button type="button" name="editPackageBtn" id="editPackageBtn" class="btn btn-primary" onclick="checkConfirmation();">Edit</button>
                                    <input type="submit" class="btn btn-primary" name="submitBtn" id="submitBtn" value="Update" style="display:none;"/>
                                </div>
                            </div>

                            @include('dashboard.billing.packages.fees')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.billing.packages.editConfirmationModal')
    @include('dashboard.billing.packages.scripts')
@endsection
