<div class="modal fade" id="feesModal">
    <div class="modal-dialog-centered">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background-color: rgba(250, 250, 250, 1);">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Package Fees Structure</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-tabs-justified">
                                <li class="active">
                                    <a href="#tabContent1" data-toggle="tab">
                                        <span>Monthly <br> <i class="fas fa-dollar text-primary font-weight-bold"></i></span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#tabContent2" data-toggle="tab">
                                        <span>Semi-Annual <br> <i class="fa fa-dollar text-primary font-weight-bold"></i></span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#tabContent3" data-toggle="tab">
                                        <span>Annual <br> <i class="fas fa-dollar text-primary font-weight-bold"></i></span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content mb-4">
                                <div class="tab-pane active" id="tabContent1">
                                    <input type="hidden" name="hiddenFeesType[]" value="monthly">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b style="font-size: large;">Fees</b>
                                            <hr class="mt-2 mb-3">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="registration_fee_0" class="control-label">Registration Fees</label>
                                                <input type="text" class="form-control" name="registration_fee[]" id="registration_fee_0"
                                                       maxlength="150" @if(!isset($PackageFeeStructures[0])) onblur="$('#registration_fee_1').val(this.value); $('#registration_fee_2').val(this.value);" @endif value="<?php if(isset($PackageFeeStructures[0])) { echo $PackageFeeStructures[0]->registration_fee; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="holding_fee_0" class="control-label">Holding Fees</label>
                                                <input type="text" class="form-control" name="holding_fee[]" id="holding_fee_0"
                                                       maxlength="150" @if(!isset($PackageFeeStructures[0])) onblur="$('#holding_fee_1').val(this.value); $('#holding_fee_2').val(this.value);" @endif value="<?php if(isset($PackageFeeStructures[0])) { echo $PackageFeeStructures[0]->holding_fee; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="late_payment_fee_0" class="control-label">Late Payment Fees</label>
                                                <input type="text" class="form-control" name="late_payment_fee[]" id="late_payment_fee_0"
                                                       maxlength="150" @if(!isset($PackageFeeStructures[0])) onblur="$('#late_payment_fee_1').val(this.value); $('#late_payment_fee_2').val(this.value);" @endif value="<?php if(isset($PackageFeeStructures[0])) { echo $PackageFeeStructures[0]->late_payment_fee; } ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="termination_fee_0" class="control-label">Early Termination Fees</label>
                                                <input type="text" class="form-control" name="termination_fee[]" id="termination_fee_0"
                                                       maxlength="150" @if(!isset($PackageFeeStructures[0])) onblur="$('#termination_fee_1').val(this.value); $('#termination_fee_2').val(this.value);" @endif value="<?php if(isset($PackageFeeStructures[0])) { echo $PackageFeeStructures[0]->termination_fee; } ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="reactivation_fee_0" class="control-label">Reactivation Fees</label>
                                                <input type="text" class="form-control" name="reactivation_fee[]" id="reactivation_fee_0"
                                                       maxlength="150" @if(!isset($PackageFeeStructures[0])) onblur="$('#reactivation_fee_1').val(this.value); $('#reactivation_fee_2').val(this.value);" @endif value="<?php if(isset($PackageFeeStructures[0])) { echo $PackageFeeStructures[0]->reactivation_fee; } ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <b style="font-size: large;">Monthly Fees</b>
                                            <hr class="mt-2 mb-3">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_1day_0" class="control-label">1 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_1day[]" id="monthly_fee_1day_0"
                                                       maxlength="150" @if(!isset($PackageFeeStructures[0])) onblur="$('#monthly_fee_1day_1').val(this.value); $('#monthly_fee_1day_2').val(this.value);" @endif value="<?php if(isset($PackageFeeStructures[0])) { echo $PackageFeeStructures[0]->monthly_fee_1day; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_2day_0" class="control-label">2 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_2day[]" id="monthly_fee_2day_0"
                                                       maxlength="150" @if(!isset($PackageFeeStructures[0])) onblur="$('#monthly_fee_2day_1').val(this.value); $('#monthly_fee_2day_2').val(this.value);" @endif value="<?php if(isset($PackageFeeStructures[0])) { echo $PackageFeeStructures[0]->monthly_fee_2day; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_3day_0" class="control-label">3 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_3day[]" id="monthly_fee_3day_0"
                                                       maxlength="150" @if(!isset($PackageFeeStructures[0])) onblur="$('#monthly_fee_3day_1').val(this.value); $('#monthly_fee_3day_2').val(this.value);" @endif value="<?php if(isset($PackageFeeStructures[0])) { echo $PackageFeeStructures[0]->monthly_fee_3day; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_4day_0" class="control-label">4 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_4day[]" id="monthly_fee_4day_0"
                                                       maxlength="150" @if(!isset($PackageFeeStructures[0])) onblur="$('#monthly_fee_4day_1').val(this.value); $('#monthly_fee_4day_2').val(this.value);" @endif value="<?php if(isset($PackageFeeStructures[0])) { echo $PackageFeeStructures[0]->monthly_fee_4day; } ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tabContent2">
                                    <input type="hidden" name="hiddenFeesType[]" value="semi-annual">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b style="font-size: large;">Fees</b>
                                            <hr class="mt-2 mb-3">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="registration_fee_1" class="control-label">Registration Fees</label>
                                                <input type="text" class="form-control" name="registration_fee[]" id="registration_fee_1"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[1])) { echo $PackageFeeStructures[1]->registration_fee; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="holding_fee_1" class="control-label">Holding Fees</label>
                                                <input type="text" class="form-control" name="holding_fee[]" id="holding_fee_1"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[1])) { echo $PackageFeeStructures[1]->holding_fee; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="late_payment_fee_1" class="control-label">Late Payment Fees</label>
                                                <input type="text" class="form-control" name="late_payment_fee[]" id="late_payment_fee_1"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[1])) { echo $PackageFeeStructures[1]->late_payment_fee; } ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="termination_fee_1" class="control-label">Early Termination Fees</label>
                                                <input type="text" class="form-control" name="termination_fee[]" id="termination_fee_1"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[1])) { echo $PackageFeeStructures[1]->termination_fee; } ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="reactivation_fee_1" class="control-label">Reactivation Fees</label>
                                                <input type="text" class="form-control" name="reactivation_fee[]" id="reactivation_fee_1"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[1])) { echo $PackageFeeStructures[1]->reactivation_fee; } ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <b style="font-size: large;">Monthly Fees</b>
                                            <hr class="mt-2 mb-3">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_1day_1" class="control-label">1 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_1day[]" id="monthly_fee_1day_1"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[1])) { echo $PackageFeeStructures[1]->monthly_fee_1day; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_2day_1" class="control-label">2 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_2day[]" id="monthly_fee_2day_1"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[1])) { echo $PackageFeeStructures[1]->monthly_fee_2day; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_3day_1" class="control-label">3 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_3day[]" id="monthly_fee_3day_1"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[1])) { echo $PackageFeeStructures[1]->monthly_fee_3day; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_4day_1" class="control-label">4 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_4day[]" id="monthly_fee_4day_1"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[1])) { echo $PackageFeeStructures[1]->monthly_fee_4day; } ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="tabContent3">
                                    <input type="hidden" name="hiddenFeesType[]" value="annual">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b style="font-size: large;">Fees</b>
                                            <hr class="mt-2 mb-3">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="registration_fee_2" class="control-label">Registration Fees</label>
                                                <input type="text" class="form-control" name="registration_fee[]" id="registration_fee_2"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[2])) { echo $PackageFeeStructures[2]->registration_fee; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="holding_fee_2" class="control-label">Holding Fees</label>
                                                <input type="text" class="form-control" name="holding_fee[]" id="holding_fee_2"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[2])) { echo $PackageFeeStructures[2]->holding_fee; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="late_payment_fee_2" class="control-label">Late Payment Fees</label>
                                                <input type="text" class="form-control" name="late_payment_fee[]" id="late_payment_fee_2"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[2])) { echo $PackageFeeStructures[2]->late_payment_fee; } ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="termination_fee_2" class="control-label">Early Termination Fees</label>
                                                <input type="text" class="form-control" name="termination_fee[]" id="termination_fee_2"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[2])) { echo $PackageFeeStructures[2]->termination_fee; } ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="reactivation_fee_2" class="control-label">Reactivation Fees</label>
                                                <input type="text" class="form-control" name="reactivation_fee[]" id="reactivation_fee_2"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[2])) { echo $PackageFeeStructures[2]->reactivation_fee; } ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <b style="font-size: large;">Monthly Fees</b>
                                            <hr class="mt-2 mb-3">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_1day_2" class="control-label">1 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_1day[]" id="monthly_fee_1day_2"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[2])) { echo $PackageFeeStructures[2]->monthly_fee_1day; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_2day_2" class="control-label">2 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_2day[]" id="monthly_fee_2day_2"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[2])) { echo $PackageFeeStructures[2]->monthly_fee_2day; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_3day_2" class="control-label">3 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_3day[]" id="monthly_fee_3day_2"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[2])) { echo $PackageFeeStructures[2]->monthly_fee_3day; } ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="monthly_fee_4day_2" class="control-label">4 Day a week</label>
                                                <input type="text" class="form-control" name="monthly_fee_4day[]" id="monthly_fee_4day_2"
                                                       maxlength="150" value="<?php if(isset($PackageFeeStructures[2])) { echo $PackageFeeStructures[2]->monthly_fee_4day; } ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>