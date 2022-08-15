@extends('dashboard.layouts.app')
@section('content')
<style media="screen">
    @media (min-width: 992px){
        .responsive {

        }
    }


    @media (max-width: 767px) {
        .responsive {
            overflow-x:auto;
        }
        .padd{
            padding: 4px 9px;
        }
    }
  .d-none{
    display: none;
  }
  .w-0{
    width: 0 !important;
  }
  .bootstrap-datetimepicker-widget{
    padding: 20px !important;
  }
  .dataTables_wrapper .dataTables_filter .form-control {
    margin-left: 0px;
  }
  .modal-dialog-centered {
    display: flex;
    align-items: inherit;
  }
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8" id="tablePage">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title pt-3">Leads</h3>
                        <button type="button" class="btn btn-primary mb-0 mr-3 ml-2 padd"
                                data-toggle="tooltip" title="Add"
                                onclick="window.location.href='{{route('leads.add')}}';"
                                style="float: right;">
                             <i class="fas fa-plus-square"></i></button>
                        @if($Role == 1)
                        <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 ml-1 padd"
                                style="display:none;float: right;"
                                data-toggle="tooltip" title="Delete Selected Leads" onclick="DeleteMultipleLeads();" id="deleteAllLeadsBtn">
                             <i class="fas fa-trash mr-1"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0 mr-0 padd"
                                style="float: right;"
                                data-toggle="tooltip" title="Action" onclick="HandleLeadAction();">
                             <i class="fa fa-tasks mr-1"></i>
                        </button>
                        @endif
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
                        <form action="{{url('')}}" method="post" enctype="multipart/form-data" id="leadsForm">
                          @csrf
                          @include('dashboard.leads.delete')
                        <div class="responsive" >
                          <table class="table w-100 tbl-responsive" id="leadsTable">
                              <thead>
                              <tr class="replace-inputs">
                                  <th>Created At</th>
                                  <th style="width:0;padding:0;" class="allLeadsActionCheckBoxColumn">
                                      <input type="checkbox" name="checkAllBox" class="allLeadsCheckBox"
                                             id="checkAllBox" onchange="CheckAllLeadRecords(this);"/>
                                  </th>
                                  <th style="width:5%;">#</th>
                                  <th style="width: 25%;">Affiliate</th>
                                  <th style="width: 35%;">Parent</th>
                                  <th style="width: 15%;">Status</th>
                                  <th style="width: 15%;">Action</th>
                              </tr>
                              </thead>
                              <tbody>
                              </tbody>
                          </table>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.leads.leadUpdateStatusModal')
    @include('dashboard.leads.delete')
    @include('dashboard.leads.leadCommentsModal')
    @include('dashboard.leads.scripts')
@endsection
