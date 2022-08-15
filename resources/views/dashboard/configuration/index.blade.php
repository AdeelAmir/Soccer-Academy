@extends('dashboard.layouts.app')
@section('content')
    <style>
        .xe-widget.xe-counter .xe-label .num {
            font-size: 20px;
        }
        @media (min-width: 992px){
           .head{

           }
            .head_mob{
                display: none;
            }
        }


        @media (max-width: 767px) {
            .head{
                display: none;
            }
            .head_mob{

            }

        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-0 head">
                Configuration
            </h1>
            <h4 class="mt-0 head_mob">
                Configuration
            </h4>
        </div>

        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{route('configuration.level')}}" style="text-decoration: none;">
                        <div class="xe-widget xe-counter">
                            <div class="xe-icon"><i class="fas fa-signal"></i></div>
                            <div class="xe-label"><strong class="num">Levels</strong></div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="{{route('configuration.categories')}}" style="text-decoration: none;">
                        <div class="xe-widget xe-counter xe-counter-purple">
                            <div class="xe-icon"><i class="fas fa-list"></i></div>
                            <div class="xe-label"><strong class="num">Categories</strong></div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="{{route('configuration.player-position')}}" style="text-decoration: none;">
                        <div class="xe-widget xe-counter xe-counter-info">
                            <div class="xe-icon"><i class="fas fa-running"></i></div>
                            <div class="xe-label"><strong class="num">Player Position</strong></div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="{{route('configuration.magic-numbers')}}" style="text-decoration: none;">
                        <div class="xe-widget xe-counter xe-counter-info">
                            <div class="xe-icon"><i class="fab fa-slack-hash" style="background-color: #C80000;color:#ffffff;"></i></div>
                            <div class="xe-label"><strong class="num">Magic Numbers</strong></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
