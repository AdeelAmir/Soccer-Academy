@extends('dashboard.layouts.app')
@section('content')
    <style>
        .xe-widget.xe-counter .xe-label .num {
            font-size: 20px;
        }
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
        }
    </style>

    <div class="row">
        <div class="col-md-12 head">
            <h1 class="mt-0">
                Billing
            </h1>
        </div>
        <div class="col-md-12 head_mob">
            <h3 class="mt-0">
                Billing
            </h3>
        </div>

        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{route('billing.expenses')}}" style="text-decoration: none;">
                        <div class="xe-widget xe-counter">
                            <div class="xe-icon"><i class="fas fa-dollar-sign"></i></div>
                            <div class="xe-label"><strong class="num">Expenses</strong></div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="{{route('packages')}}" style="text-decoration: none;">
                        <div class="xe-widget xe-counter xe-counter-purple">
                            <div class="xe-icon"><i class="far fa-money-bill-alt"></i></div>
                            <div class="xe-label"><strong class="num">Packages</strong></div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="{{route('billing.invoices')}}" style="text-decoration: none;">
                        <div class="xe-widget xe-counter xe-counter-blue">
                            <div class="xe-icon"><i class="fas fa-file-invoice"></i></div>
                            <div class="xe-label"><strong class="num">Invoices</strong></div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="{{route('billing.transactions')}}" style="text-decoration: none;">
                        <div class="xe-widget xe-counter xe-counter-yellow">
                            <div class="xe-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div class="xe-label"><strong class="num">Transactions</strong></div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="{{route('billing.subscriptions')}}" style="text-decoration: none;">
                        <div class="xe-widget xe-counter xe-counter-red">
                            <div class="xe-icon"><i class="fas fa-repeat"></i></div>
                            <div class="xe-label"><strong class="num">Memberships</strong></div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="{{route('billing.coupons')}}" style="text-decoration: none;">
                        <div class="xe-widget xe-counter xe-counter-orange">
                            <div class="xe-icon"><i class="fas fa-percentage"></i></div>
                            <div class="xe-label"><strong class="num">Coupons</strong></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection