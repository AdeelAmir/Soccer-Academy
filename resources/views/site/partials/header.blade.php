<section class="bg-custom-secondary text-black fs-13 d-none d-md-block">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <a href="#" class="float-end">B2B</a>
                <a href="#" class="mr-3 float-end">Care & Repair</a>
                <a href="#" class="mr-3 float-end">Clearance Sale</a>
                <a href="#" class="mr-3 float-end">Discounted Vouchers</a>
                <a href="#" class="mr-3 float-end">About Us</a>
            </div>
        </div>
    </div>
</section>

<header class="ltn__header-area ltn__header-5 ltn__header-transparent--- gradient-color-4---">
    <div class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-white">
        <div class="container">
            {{--Desktop Header--}}
            <div class="row mb-2 d-none d-md-flex">
                <div class="col col-md-2">
                    <div class="site-logo-wrap">
                        <div class="site-logo">
                            <a href="./"><img src="{{asset('public/assets/images/logo.png')}}" alt="Logo" style="width: 172px; max-width: 172px;"></a>
                        </div>
                    </div>
                </div>
                <div class="col col-md-10 header-menu-column">
                    <div class="header-menu">
                        <div class="row">
                            <div class="col-8 pr-0">
                                <i class="fa fa-search absolute-search-icon"></i>
                                <input type="text" class="form-control search-bar border-radium-5 mb-0" placeholder="Search for products....." style="box-shadow: 0 0 5px 0 rgb(0 0 0 / 50%);" />
                            </div>
                            <div class="col-4 pl-0 upper-menu">
                                <nav>
                                    <div class="ltn__main-menu">
                                        <ul class="float-end">
                                            <li class="menu-icon"><a href="{{route('login')}}"><i class="fa fa-user text-custom-primary"></i> <span>Sign In</span></a></li>
                                            <li class="menu-icon"><a href="javascript:void(0);"><i class="fa fa-heart text-custom-primary"></i> Wishlist</a></li>
                                            <li class="menu-icon"><a href="javascript:void(0);"><i class="fa fa-shopping-cart text-custom-primary"></i> Cart</a></li>
                                            <li class="menu-icon"><a href="javascript:void(0);"><i class="fa fa-map-marker-alt text-custom-primary"></i> Stores</a></li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col ltn__header-options"></div>
            </div>

            <div class="row d-none d-md-flex mb-2">
                <div class="col col-md-12 header-menu-column">
                    <div class="header-menu">
                        <div class="row">
                            <div class="col-12 upper-menu">
                                <nav>
                                    <div class="__menu ltn__main-menu">
                                        <ul class="text-center">
                                            <li class="menu-icon"><a href="{{route('CategoryRoute')}}">Mobile Phones</a></li>
                                            <li class="menu-icon"><a href="{{route('CategoryRoute')}}">TV</a></li>
                                            <li class="menu-icon"><a href="{{route('CategoryRoute')}}">RAC</a></li>
                                            <li class="menu-icon"><a href="{{route('CategoryRoute')}}">Refrigerator</a></li>
                                            <li class="menu-icon"><a href="{{route('CategoryRoute')}}">Washing Machine</a></li>
                                            <li class="menu-icon"><a href="{{route('CategoryRoute')}}">Dishwasher</a></li>
                                            <li class="menu-icon"><a href="{{route('CategoryRoute')}}">Cooking Appliances</a></li>
                                            <li class="menu-icon"><a href="{{route('CategoryRoute')}}">SDA</a></li>
                                            <li class="menu-icon"><a href="{{route('CategoryRoute')}}">Air Purifier</a></li>
                                            <li class="menu-icon"><a href="{{route('CategoryRoute')}}" class="text-custom-primary">IOT</a></li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col ltn__header-options"></div>
            </div>

            <div class="row d-none d-md-block mb-2">
                <div class="offset-2 col-md-9 d-flex text-custom-primary">
                    <div class="w-25 d-flex align-items-center">
                        <img src="{{asset('public/assets/images/header/camera.png')}}" alt="CAMERA" class="img-fluid" style="width: 32px; margin-right: 10px;" />
                        <div style="line-height: 1.3; font-size: 11px;" class="w-75 text-start">
                            ShopLive 24/7, video call  an expert to help you shop
                        </div>
                    </div>

                    <div class="w-25 d-flex align-items-center">
                        <img src="{{asset('public/assets/images/header/van.png')}}" alt="VAN" class="img-fluid" style="width: 32px; margin-right: 10px;" />
                        <div style="line-height: 1.3; font-size: 11px;" class="w-75 text-start">
                            Free Delivery & same day delivery for order placed before 11 am
                        </div>
                    </div>

                    <div class="w-25 d-flex align-items-center">
                        <img src="{{asset('public/assets/images/header/prize.png')}}" alt="PRIZE" class="img-fluid" style="width: 24px; margin-right: 10px;" />
                        <div style="line-height: 1.3; font-size: 11px;" class="w-75 text-start">
                            1 Month free replacement Warranty
                        </div>
                    </div>

                    <div class="w-25 d-flex align-items-center">
                        <img src="{{asset('public/assets/images/header/referral-code.png')}}" alt="referral-code" class="img-fluid" style="width: 32px; margin-right: 10px;" />
                        <div style="line-height: 1.3; font-size: 11px;" class="w-75 text-start">
                            OReferral Code
                        </div>
                    </div>
                </div>
                <div class="col ltn__header-options"></div>
            </div>
            {{--Desktop Header--}}

            {{--Mobile Header--}}
            <div class="row d-flex d-md-none">
                <div class="col-8">
                    <div class="site-logo-wrap">
                        <div class="site-logo">
                            <a href="#"><img src="{{asset('public/assets/images/logo.png')}}" alt="Logo" style="width: 130px; max-width: 130px;"></a>
                        </div>
                    </div>
                </div>

                <div class="col-4"> {{-- ltn__header-options ltn__header-options-2 mb-sm-20--}}
                    <!-- Mobile Menu Button -->
                    <div class="mobile-menu-toggle d-xl-none">
                        <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                            <svg viewBox="0 0 800 600">
                                <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                <path d="M300,320 L540,320" id="middle"></path>
                                <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            {{--Mobile Header--}}
        </div>
    </div>
</header>
<!-- HEADER AREA END -->

<!-- Utilize Mobile Menu Start -->
<div id="ltn__utilize-mobile-menu" class="ltn__utilize ltn__utilize-mobile-menu">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">
        <div class="ltn__utilize-menu-head">
            <div class="site-logo">
                <a href="{{\App\Helpers\SiteHelper::settings()['SiteUrl']}}"><img src="{{asset('public/assets/images/logo.png')}}" alt="Logo" style="width: 130px; max-width: 130px;"></a>
            </div>
            <button class="ltn__utilize-close">×</button>
        </div>
        <div class="ltn__utilize-menu">
            <ul>
                <li class="menu-icon"><a href="./">HOME</a></li>
                {{--<li class="menu-icon"><a href="{{\App\Helpers\SiteHelper::settings()['SiteUrl']}}#services">OUR SERVICES</a></li>
                <li class="menu-icon"><a href="{{\App\Helpers\SiteHelper::settings()['SiteUrl']}}#programs">OUR EXPERTISE</a></li>
                <li class="menu-icon"><a href="{{\App\Helpers\SiteHelper::settings()['SiteUrl']}}#faq">FAQS</a></li>
                <li class="menu-icon"><a href="{{\App\Helpers\SiteHelper::settings()['SiteUrl']}}#contact-us">CONTACT US</a></li>
                <li class="menu-icon"><a href="./">ASSESSMENT FORM</a></li>
                <li class="menu-icon"><a href="{{\App\Helpers\SiteHelper::settings()['SiteUrl']}}">BLOG</a></li>--}}
            </ul>
        </div>
        {{--<div class="ltn__social-media-2">
            <ul>
                <li><a href="{{\App\Helpers\SiteHelper::settings()['Instagram']}}" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                <li><a href="{{\App\Helpers\SiteHelper::settings()['Facebook']}}" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="{{\App\Helpers\SiteHelper::settings()['Twitter']}}" title="Twitter"><i class="fab fa-twitter"></i></a></li>
            </ul>
        </div>--}}
    </div>
</div>
<!-- Utilize Mobile Menu End -->

<div class="ltn__utilize-overlay"></div>
