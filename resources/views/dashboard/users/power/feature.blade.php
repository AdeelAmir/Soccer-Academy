@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Power > <span class="text-primary">Feature</span></h3>
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
                        <table class="table w-100 tbl-responsive text-center" id="userPowerFeatureTable">
                            <thead>
                            <tr class="replace-inputs">
                                <th>Feature</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>KPI</td>
                                <td>
                                  <div class="col-md-12 mt-2">
                                      <input type="checkbox" class="iswitch iswitch-primary" name="kpi_power_feature"
                                             id="kpi_power_feature" onchange="CheckPowerFeature(this.checked,'kpi',{{$UserId}});"
                                             <?php if($UserPowers[0]->kpi == 1){echo "checked";} ?> >
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>Lead Funnel</td>
                                <td>
                                  <div class="col-md-12 mt-2">
                                      <input type="checkbox" class="iswitch iswitch-primary" name="lead_funnel_power_feature"
                                             id="lead_funnel_power_feature" onchange="CheckPowerFeature(this.checked,'lead_funnel',{{$UserId}});"
                                             <?php if($UserPowers[0]->lead_funnel == 1){echo "checked";} ?>>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>Reports</td>
                                <td>
                                  <div class="col-md-12 mt-2">
                                      <input type="checkbox" class="iswitch iswitch-primary" name="reports_power_feature"
                                             id="reports_power_feature" onchange="CheckPowerFeature(this.checked,'reports',{{$UserId}});"
                                             <?php if($UserPowers[0]->reports == 1){echo "checked";} ?>>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.users.delete')
    @include('dashboard.users.scripts')
    @include('dashboard.users.ban')
    @include('dashboard.users.power-type')
@endsection
