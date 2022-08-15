@extends('site.layouts.app')
@section('content')
    {{--Shop by Category Section--}}
    <section class="mb-4 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center" style="line-height: 3;">
                    <span style="cursor: pointer; margin: 0 10px; padding: 5px 10px; border: 1px solid black; border-radius: 10px; color: black; font-weight: 500;">
                        ALL
                    </span>
                    <span style="cursor: pointer; margin: 0 10px; padding: 5px 10px; border: 1px solid black; border-radius: 10px; color: black; font-weight: 500;">
                        FHD
                    </span>
                    <span style="cursor: pointer; margin: 0 10px; padding: 5px 10px; border: 1px solid black; border-radius: 10px; color: black; font-weight: 500;">
                        UHD
                    </span>
                    <span style="cursor: pointer; margin: 0 10px; padding: 5px 10px; border: 1px solid black; border-radius: 10px; color: black; font-weight: 500;">
                        SUHD
                    </span>
                    <span style="cursor: pointer; margin: 0 10px; padding: 5px 10px; border: 1px solid black; border-radius: 10px; color: black; font-weight: 500;">
                        8K
                    </span>
                    <span style="cursor: pointer; margin: 0 10px; padding: 5px 10px; border: 1px solid black; border-radius: 10px; color: black; font-weight: 500;">
                        OLED
                    </span>
                    <span style="cursor: pointer; margin: 0 10px; padding: 5px 10px; border: 1px solid black; border-radius: 10px; color: black; font-weight: 500;">
                        QLED
                    </span>
                </div>
            </div>
        </div>
    </section>
    {{--Shop by Category Section--}}

    {{--Phones Category--}}
    <section class="mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12 mb-2">
                    <div class="row">
                        <div class="col-md-10">
                            <h2 class="section-title text-custom-primary mb-0 fs-large">
                                Find the Best TV Deals.
                                &nbsp;
                                <input type="checkbox" id="compareTvs" name="compareTvs" class="form-check-input">
                                <label for="compareTvs" class="form-check-label small pt-1">Compare</label>
                            </h2>
                        </div>
                        <div class="col-md-2">
                            <a href="{{route('DealsRoute')}}">
                                <label for=""
                                       class="form-check-label text-custom-primary cursor-pointer small pt-1 float-right">See
                                    all deals <i class="fa fa-arrow-right" aria-hidden="true"></i></label>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row products-category-slider ltn__category-products-slider slick-arrow-1">
                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/phones/1.png')}}" alt="Phones"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    Iphone 13
                                </p>
                                <p class="mb-0">
                                    upto 19.5 hours talk time
                                </p>
                                <p class="mb-0">
                                    A15 Bionic Chip
                                </p>
                                <p class="mb-0">
                                    120Htz Refresh
                                </p>
                                <p class="mb-0">
                                    12 mp camera.
                                </p>
                                <p class="mb-0">
                                    128, 256 GB
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 218,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/phones/2.png')}}" alt="Phones"
                                         class="img-fluid"/>
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    Iphone pro 13
                                </p>
                                <p class="mb-0">
                                    upto 19.5 hours talk time
                                </p>
                                <p class="mb-0">
                                    A15 Bionic Chip
                                </p>
                                <p class="mb-0">
                                    120Htz Refresh
                                </p>
                                <p class="mb-0">
                                    12 mp camera.
                                </p>
                                <p class="mb-0">
                                    128, 256 GB
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-danger"></i>&nbsp;Stock out
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 218,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/phones/3.png')}}" alt="Phones"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    Iphone pro max 13
                                </p>
                                <p class="mb-0">
                                    upto 19.5 hours talk time
                                </p>
                                <p class="mb-0">
                                    A15 Bionic Chip
                                </p>
                                <p class="mb-0">
                                    120Htz Refresh
                                </p>
                                <p class="mb-0">
                                    12 mp camera.
                                </p>
                                <p class="mb-0">
                                    128, 256 GB
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 218,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/phones/4.png')}}" alt="Phones"
                                         class="img-fluid"/>
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    Samsung S21Ultra
                                </p>
                                <p class="mb-0">
                                    upto 19.5 hours talk time
                                </p>
                                <p class="mb-0">
                                    A15 Bionic Chip
                                </p>
                                <p class="mb-0">
                                    120Htz Refresh
                                </p>
                                <p class="mb-0">
                                    12 mp camera.
                                </p>
                                <p class="mb-0">
                                    128, 256 GB
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-danger"></i>&nbsp;Stock out
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 218,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/phones/5.png')}}" alt="Phones"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    Samsung A72
                                </p>
                                <p class="mb-0">
                                    upto 19.5 hours talk time
                                </p>
                                <p class="mb-0">
                                    A15 Bionic Chip
                                </p>
                                <p class="mb-0">
                                    120Htz Refresh
                                </p>
                                <p class="mb-0">
                                    12 mp camera.
                                </p>
                                <p class="mb-0">
                                    128, 256 GB
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 218,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/phones/6.png')}}" alt="Phones"
                                         class="img-fluid"/>
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    Xiomi 11 Ultra
                                </p>
                                <p class="mb-0">
                                    upto 19.5 hours talk time
                                </p>
                                <p class="mb-0">
                                    A15 Bionic Chip
                                </p>
                                <p class="mb-0">
                                    120Htz Refresh
                                </p>
                                <p class="mb-0">
                                    12 mp camera.
                                </p>
                                <p class="mb-0">
                                    128, 256 GB
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-danger"></i>&nbsp;Stock out
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 218,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/phones/7.png')}}" alt="Phones"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    Vivo X70 Pro
                                </p>
                                <p class="mb-0">
                                    upto 19.5 hours talk time
                                </p>
                                <p class="mb-0">
                                    A15 Bionic Chip
                                </p>
                                <p class="mb-0">
                                    120Htz Refresh
                                </p>
                                <p class="mb-0">
                                    12 mp camera.
                                </p>
                                <p class="mb-0">
                                    128, 256 GB
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 218,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--Phones Category--}}

    {{--Led Category--}}
    <section class="mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12 mb-2">
                    <div class="row">
                        <div class="col-md-10">
                            <h2 class="section-title text-custom-primary mb-0 fs-large">
                                Find the Best TV Deals.
                                &nbsp;
                                <input type="checkbox" id="compareTvs" name="compareTvs" class="form-check-input">
                                <label for="compareTvs" class="form-check-label small pt-1">Compare</label>
                            </h2>
                        </div>
                        <div class="col-md-2">
                            <a href="#">
                                <label for="{{route('DealsRoute')}}"
                                       class="form-check-label text-custom-primary cursor-pointer small pt-1 float-right">See
                                    all deals <i class="fa fa-arrow-right" aria-hidden="true"></i></label>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row products-category-slider ltn__category-products-slider slick-arrow-1">
                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/led/1.png')}}" alt="LED"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG 32LF510
                                </p>
                                <p class="mb-0">
                                    32” inches
                                </p>
                                <p class="mb-0">
                                    1366*768
                                </p>
                                <p class="mb-0">
                                    Triple XD Engine
                                </p>
                                <p class="mb-0">
                                    2 USB Ports
                                </p>
                                <p class="mb-0">
                                    2 HDMI Ports
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 90,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/led/2.png')}}" alt="LED"
                                         class="img-fluid"/>
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG 32LF510
                                </p>
                                <p class="mb-0">
                                    32” inches
                                </p>
                                <p class="mb-0">
                                    1366*768
                                </p>
                                <p class="mb-0">
                                    Triple XD Engine
                                </p>
                                <p class="mb-0">
                                    2 USB Ports
                                </p>
                                <p class="mb-0">
                                    2 HDMI Ports
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 90,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/led/3.png')}}" alt="LED"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG 32LF510
                                </p>
                                <p class="mb-0">
                                    32” inches
                                </p>
                                <p class="mb-0">
                                    1366*768
                                </p>
                                <p class="mb-0">
                                    Triple XD Engine
                                </p>
                                <p class="mb-0">
                                    2 USB Ports
                                </p>
                                <p class="mb-0">
                                    2 HDMI Ports
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 90,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/led/4.png')}}" alt="LED"
                                         class="img-fluid"/>
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG 32LF510
                                </p>
                                <p class="mb-0">
                                    32” inches
                                </p>
                                <p class="mb-0">
                                    1366*768
                                </p>
                                <p class="mb-0">
                                    Triple XD Engine
                                </p>
                                <p class="mb-0">
                                    2 USB Ports
                                </p>
                                <p class="mb-0">
                                    2 HDMI Ports
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 90,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/led/5.png')}}" alt="LED"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG 32LF510
                                </p>
                                <p class="mb-0">
                                    32” inches
                                </p>
                                <p class="mb-0">
                                    1366*768
                                </p>
                                <p class="mb-0">
                                    Triple XD Engine
                                </p>
                                <p class="mb-0">
                                    2 USB Ports
                                </p>
                                <p class="mb-0">
                                    2 HDMI Ports
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 90,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/led/6.png')}}" alt="LED"
                                         class="img-fluid"/>
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG 32LF510
                                </p>
                                <p class="mb-0">
                                    32” inches
                                </p>
                                <p class="mb-0">
                                    1366*768
                                </p>
                                <p class="mb-0">
                                    Triple XD Engine
                                </p>
                                <p class="mb-0">
                                    2 USB Ports
                                </p>
                                <p class="mb-0">
                                    2 HDMI Ports
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 90,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/led/7.png')}}" alt="LED"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG 32LF510
                                </p>
                                <p class="mb-0">
                                    32” inches
                                </p>
                                <p class="mb-0">
                                    1366*768
                                </p>
                                <p class="mb-0">
                                    Triple XD Engine
                                </p>
                                <p class="mb-0">
                                    2 USB Ports
                                </p>
                                <p class="mb-0">
                                    2 HDMI Ports
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 90,000
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--Led Category--}}

    {{--AC Category--}}
    <section class="mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12 mb-2">
                    <div class="row">
                        <div class="col-md-10">
                            <h2 class="section-title text-custom-primary mb-0 fs-large">
                                Wide range of Air Conditioner according to your desire.
                                &nbsp;
                                <input type="checkbox" id="compareAC" name="compareAC" class="form-check-input">
                                <label for="compareAC" class="form-check-label small pt-1">Compare</label>
                            </h2>
                        </div>
                        <div class="col-md-2">
                            <a href="{{route('DealsRoute')}}">
                                <label for=""
                                       class="form-check-label text-custom-primary cursor-pointer small pt-1 float-right">See
                                    all deals <i class="fa fa-arrow-right" aria-hidden="true"></i></label>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row products-category-slider ltn__category-products-slider slick-arrow-1">
                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/ac/1.png')}}" alt="AC"
                                         class="img-fluid"/>
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG S126CH
                                </p>
                                <p class="mb-0">
                                    S186CH
                                </p>
                                <p class="mb-0">
                                    Moist Fresh
                                </p>
                                <p class="mb-0">
                                    T3 Compressor
                                </p>
                                <p class="mb-0">
                                    Heat & Cool
                                </p>
                                <p class="mb-0">
                                    Gold Fin
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 429,999
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/ac/2.png')}}" alt="AC"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG S126CH
                                </p>
                                <p class="mb-0">
                                    S186CH
                                </p>
                                <p class="mb-0">
                                    Moist Fresh
                                </p>
                                <p class="mb-0">
                                    T3 Compressor
                                </p>
                                <p class="mb-0">
                                    Heat & Cool
                                </p>
                                <p class="mb-0">
                                    Gold Fin
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 429,999
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/ac/3.png')}}" alt="AC"
                                         class="img-fluid"/>
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG S126CH
                                </p>
                                <p class="mb-0">
                                    S186CH
                                </p>
                                <p class="mb-0">
                                    Moist Fresh
                                </p>
                                <p class="mb-0">
                                    T3 Compressor
                                </p>
                                <p class="mb-0">
                                    Heat & Cool
                                </p>
                                <p class="mb-0">
                                    Gold Fin
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 429,999
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/ac/4.png')}}" alt="AC"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG S126CH
                                </p>
                                <p class="mb-0">
                                    S186CH
                                </p>
                                <p class="mb-0">
                                    Moist Fresh
                                </p>
                                <p class="mb-0">
                                    T3 Compressor
                                </p>
                                <p class="mb-0">
                                    Heat & Cool
                                </p>
                                <p class="mb-0">
                                    Gold Fin
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 429,999
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;5.0
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/ac/5.png')}}" alt="AC"
                                         class="img-fluid"/>
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG S126CH
                                </p>
                                <p class="mb-0">
                                    S186CH
                                </p>
                                <p class="mb-0">
                                    Moist Fresh
                                </p>
                                <p class="mb-0">
                                    T3 Compressor
                                </p>
                                <p class="mb-0">
                                    Heat & Cool
                                </p>
                                <p class="mb-0">
                                    Gold Fin
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 429,999
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;4.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/ac/6.png')}}" alt="AC"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG S126CH
                                </p>
                                <p class="mb-0">
                                    S186CH
                                </p>
                                <p class="mb-0">
                                    Moist Fresh
                                </p>
                                <p class="mb-0">
                                    T3 Compressor
                                </p>
                                <p class="mb-0">
                                    Heat & Cool
                                </p>
                                <p class="mb-0">
                                    Gold Fin
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 429,999
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 mt-2 mb-2">
                            <div class="product-category-square text-center">
                                <span class="product-category-square-rating">
                                    <i class="fa fa-star text-warning"></i>&nbsp;3.3
                                </span>
                                <span class="product-category-square-img mt-2 mb-2">
                                    <img src="{{asset('public/assets/images/home/products/ac/7.png')}}" alt="AC"
                                         class="img-fluid"/>
                                </span>
                                <span class="product-category-square-discount bg-custom-primary text-white">
                                    10% OFF
                                </span>
                                <p class="mb-1 text-black fs-14 fw-600">
                                    LG S126CH
                                </p>
                                <p class="mb-0">
                                    S186CH
                                </p>
                                <p class="mb-0">
                                    Moist Fresh
                                </p>
                                <p class="mb-0">
                                    T3 Compressor
                                </p>
                                <p class="mb-0">
                                    Heat & Cool
                                </p>
                                <p class="mb-0">
                                    Gold Fin
                                </p>
                                <table class="mt-1 mb-1 w-100">
                                    <tr>
                                        <td class="fs-11" style="width: 40%;">
                                            <i class="fa fa-circle text-success"></i>&nbsp;In stock
                                        </td>
                                        <td class="text-end text-black fs-12 fw-600" style="width: 60%;">
                                            PKR 429,999
                                        </td>
                                    </tr>
                                </table>
                                <div class="product-category-square-bottom">
                                    <div class="row fs-12">
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn border-right cursor-pointer">
                                            Add to cart
                                        </div>
                                        <div class="col-6 text-center px-1 py-2 product-category-square-btn cursor-pointer">
                                            Wishlist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--AC Category--}}
@endsection
