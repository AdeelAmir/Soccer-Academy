@extends('dashboard.layouts.app')
@section('content')
    <style media="screen">
        @media (max-width: 767px) {
            .head_mob {
                display: none;
            }
        }
    </style>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            @if(\Illuminate\Support\Facades\Session::has('success'))
                <div class="alert alert-success mb-3" id="message-alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span> <span
                                class="sr-only">Close</span></button>
                    {{\Illuminate\Support\Facades\Session::get('success')}}
                </div>
            @elseif(\Illuminate\Support\Facades\Session::has('error'))
                <div class="alert alert-danger mb-3" id="message-alert">
                    {{\Illuminate\Support\Facades\Session::get('error')}}
                </div>
            @endif

            <h3 class="mt-0 mb-4 head_mob">Configuration > <span class="text-primary">Magic Numbers</span></h3>
            <button type="button" class="btn btn-primary" style="float:right;"
                    onclick="window.location.href='{{route('configuration')}}';">
                <i class="fas fa-arrow-left"></i>
            </button>
        </div>
    </div>

    <div class="row">
        <form action="{{route('configuration.magic-numbers.update')}}" method="post" enctype="multipart/form-data"
              id="checkvalue">
            @csrf
            <input type="hidden" name="id" value="1">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <ul class="nav nav-tabs nav-tabs-justified">
                    <li class="active">
                        <a href="#tabContent2" data-toggle="tab">
                            <span>Deadline <br> <i class="fa fa-calendar text-primary font-weight-bold"
                                                   aria-hidden="true"></i></span>
                        </a>
                    </li>
                    <li>
                        <a href="#tabContent3" data-toggle="tab">
                            <span>Rate <br> <i class="fas fa-percentage text-primary font-weight-bold"></i></span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content mb-4">
                    <div class="tab-pane active" id="tabContent2">
                        <div class="row">
                            <div class="col-md-12">
                                <b style="font-size: large;">Deadline</b>
                                <hr class="mt-2 mb-3">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="document_deadline" class="control-label">Document Deadline</label>
                                    <input type="text" class="form-control" name="document_deadline"
                                           id="document_deadline"
                                           maxlength="150" value="{{$MagicNumbers[0]->document_deadline}}" required
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="holding_deadline" class="control-label">Holding Deadline</label>
                                    <input type="text" class="form-control" name="holding_deadline"
                                           id="holding_deadline"
                                           maxlength="150" value="{{$MagicNumbers[0]->holding_deadline}}" required
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_deadline" class="control-label">Payment Deadline</label>
                                    <input type="text" class="form-control" name="payment_deadline"
                                           id="payment_deadline"
                                           maxlength="150" value="{{$MagicNumbers[0]->payment_deadline}}" required
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_reminder" class="control-label">Payment Reminder</label>
                                    <input type="text" class="form-control" name="payment_reminder"
                                           id="payment_reminder"
                                           maxlength="150" value="{{$MagicNumbers[0]->payment_reminder}}" required
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="suspended_account" class="control-label">Suspended Account</label>
                                    <input type="text" class="form-control" name="suspended_account"
                                           id="suspended_account"
                                           placeholder="Days"
                                           maxlength="150" value="{{$MagicNumbers[0]->suspended_account}}" required
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="late_fee" class="control-label">Late Fee</label>
                                    <input type="text" class="form-control" name="late_fee" id="late_fee"
                                           placeholder="Days"
                                           maxlength="150" value="{{$MagicNumbers[0]->late_fee}}" required disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tabContent3">
                        <div class="row">
                            <div class="col-md-12">
                                <b style="font-size: large;">Rate</b>
                                <hr class="mt-2 mb-3">
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="processing_fee" class="control-label">Processing Fee</label>
                                    <input type="number" class="form-control" name="processing_fee" id="processing_fee"
                                           step="any" placeholder="%" maxlength="100"
                                           value="{{$MagicNumbers[0]->processing_fee}}" required disabled>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="affiliate_commission" class="control-label">Affiliate Commission</label>
                                    <input type="text" class="form-control" name="affiliate_commission"
                                           id="affiliate_commission"
                                           maxlength="150" value="{{$MagicNumbers[0]->affiliate_commission}}" required
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tax_rate" class="control-label">Tax Rate</label>
                                    <input type="text" class="form-control" name="tax_rate" id="tax_rate"
                                           maxlength="150" value="{{$MagicNumbers[0]->tax_rate}}" required disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 text-center mb-2">
                <button type="button" name="editMagicNumberBtn" id="editMagicNumberBtn" class="btn btn-primary"
                        onclick="checkConfirmation();">Edit
                </button>
                <button type="submit" class="btn btn-primary" name="submitEditMagicNumberForm"
                        id="submitEditMagicNumberForm" style="display:none;">Save changes
                </button>
            </div>
        </form>
    </div>
    @include('dashboard.configuration.magic-numbers.editConfirmationModal')
@endsection

@push('extended-scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            let Alert = $("#message-alert");
            if (Alert.length > 0) {
                setTimeout(function () {
                    Alert.slideUp();
                }, 10000);
            }


        });

        // Edit Confirmation
        function checkConfirmation() {
            $("#editConfirmationModal").modal('toggle');
        }

        function ConfirmEditLevel() {
            $("#document_deadline").prop('disabled', false);
            $("#holding_deadline").prop('disabled', false);
            $("#payment_deadline").prop('disabled', false);
            $("#payment_reminder").prop('disabled', false);
            $("#suspended_account").prop('disabled', false);
            $("#late_fee").prop('disabled', false);
            $("#processing_fee").prop('disabled', false);
            $("#affiliate_commission").prop('disabled', false);
            $("#tax_rate").prop('disabled', false);
            $("#editMagicNumberBtn").hide();
            $("#submitEditMagicNumberForm").show();
            $("#editConfirmationModal").modal('toggle');

        }
    </script>
@endpush
