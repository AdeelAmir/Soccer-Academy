@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">


        @media (max-width: 767px) {

            .padd{
                padding: 4px 9px;
            }
        }


    </style>
    <div class="container-fluid" id="AddPlayerLocationPage">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Locations > <span class="text-primary">New</span></h3>
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

                        <form action="{{route('locations.store')}}" id="addLocationForm" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="custom-row">
                                <div class="custom-col-4 mb-2">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           placeholder="Location Name" maxlength="100" required/>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="state">State</label>
                                    <select class="form-control select2" name="state" id="state"
                                            onchange="LoadStateCountyCity();" required>
                                        <option value="" selected>Select State</option>
                                        @foreach($States as $state)
                                            <option value="{{$state->name}}">{{$state->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2" id="citySection" style="display: none;">
                                    <label for="city">City</label>
                                    <select name="city" id="city" class="form-control select2">
                                        <option value="" selected>Select City</option>
                                    </select>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="street">Street</label>
                                    <input type="text" name="street" id="street" class="form-control"
                                           placeholder="Street" maxlength="100" required/>
                                </div>
                                <div class="custom-col-4 mb-2">
                                    <label for="zipcode">Zip Code</label>
                                    <input type="number" step="any" name="zipcode" id="zipcode"
                                           class="form-control"
                                           placeholder="Zip Code" onkeypress="limitKeypress(event,this.value,5)"
                                           onblur="limitZipCodeCheck();" required/>
                                </div>
                            </div>
                            <input type="hidden" name="address" id="address" value="null">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <h4 style="color: black;">Levels</h4>
                                    <hr class="mt-2 mb-2">
                                </div>

                                <div class="col-md-12" id="LocationLevelsBlock">

                                </div>

                                <div class="col-md-12 mb-2">
                                    <span class="btn btn-primary btn-sm" onclick="MakeLocationLevels();">
                                        <span class="fa fa-plus"></span> Add
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <input type="submit" class="btn btn-primary mt-5" name="submitFormBtn" value="Save"/>
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
    @include('dashboard.locations.scripts')
@endsection
