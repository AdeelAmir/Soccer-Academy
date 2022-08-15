@extends('layouts.siteapp')
@section('content')
    <div id="page-container">
        <div id="content-wrap">
            <div class="container-fluid pt-5">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img src="{{ asset('public/assets/images/Logo.jpg')}}" alt="logo-small"
                             style="width: 125px;" class="img-fluid mb-3">
                    </div>

                    <div class="col-md-12">
                        @if($Status == 'success')
                            <h3 class="text-center mt-4 mb-5">Payment Completed</h3>
                        @else
                            <h3 class="text-center mt-4 mb-5">Payment Failed</h3>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-2 col-md-8">
                        @if($Status == 'success')
                            <div class="alert alert-success mb-0">
                                <button type="button" class="close" data-dismiss="alert"><span
                                            aria-hidden="true">×</span> <span
                                            class="sr-only">Close</span></button>
                                Payment has been processed and invoice has been paid.
                            </div>
                            <div class="text-center">
                                <img src="{{asset('public/assets/images/checked.png')}}" alt="Success Image" class="img-fluid mt-5 mb-5" style="width: 120px;" />
                            </div>
                        @else
                            <div class="alert alert-danger mb-0">
                                Payment cannot be processed due to some technical issues.
                            </div>
                            <div class="text-center">
                                <img src="{{asset('public/assets/images/failed.png')}}" alt="Error Image" class="img-fluid mt-5 mb-5" style="width: 120px;" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    {{--Footer--}}
    <!-- <div class="container-fluid" style="background: #222222; margin-top: 150px;"> -->
        <footer id="footer">
            <div class="container-fluid" style="background: #222222;">
                <div class="row">
                    <div class="col-md-12 text-center" style="margin-top: 20px">
                        <a style="color:white" href="https://www.mysocceracademy.com/" target="_blank">HOME</a>
                        <br><br>
                        <a style="color:white" href="{{url('registration')}}">REGISTRATION</a>
                        <br><br>
                        <a style="color:white" href="https://www.mysocceracademy.com/contact/"
                           target="_blank">CONTACT</a>
                        <br><br>
                        <a style="color:white" href="{{url('login')}}">LOGIN</a>
                    </div>
                    <div class="col-md-12 text-center">
                        <br>
                        <a class="btn btn-primary icon" style="background-color: transparent;"
                           href="https://www.facebook.com/mySoccerAcademy/" role="button" target="_blank">
                            <i class="fab fa-facebook" style="font-size: 17px"></i></a>
                        <a class="btn btn-primary icon" style="background-color: transparent;"
                           href="https://instagram.com/mysocceracademy" role="button" target="_blank">
                            <i class="fab fa-instagram" style="font-size: 17px"></i></a>
                        <a class="btn btn-primary icon" style="background-color:transparent;"
                           href="https://twitter.com/socceracademy" role="button" target="_blank">
                            <i class="fab fa-twitter" style="font-size: 17px"></i></a>
                        <a class="btn btn-primary icon" style="background-color:transparent;"
                           href="#" role="button" target="_blank">{{--https://twitter.com/socceracademe--}}
                            <i class="fab fa-youtube" style="font-size: 17px"></i></a>
                    </div>
                    <div class="col-md-12 text-center mt-3 mb-5">
                        <p class="text-center text-muted mb-3" style="color: white;font-size: 15px">
                            &copy; Copyright. My Soccer Academy LLC. All Rights Reserved.
                        </p>
                        <p class="text-center text-muted mb-3" style="color: white;">
                            <a style="color:white" href="https://www.mysocceracademy.com/about/">Terms and
                                Conditions</a>&nbsp;|&nbsp;<a style="color:white;" href="#">Privacy Policy</a>
                        </p>
                        <p class="text-center text-muted mb-0" style="color: white;">
                            Developed & Powered by <a style="color:white" href="{{url('/')}}">WORX LLC</a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection
