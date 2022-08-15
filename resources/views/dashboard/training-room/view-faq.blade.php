@extends('dashboard.layouts.app')
@section('content')
    <style>
        .cntr {
            display: table;
            width: 100%;
            height: 100%;
        }
        .cntr .cntr-innr {
            display: table-cell;
            text-align: right;
            vertical-align: middle;
        }
        /*** STYLES ***/
        .search {
            display: inline-block;
            position: relative;
            height: 35px;
            width: 35px;
            box-sizing: border-box;
            /*margin: 0px 8px 7px 0px;*/
            padding: 3px 9px 0 9px;
            border: 3px solid #023A51;
            border-radius: 25px;
            transition: all 200ms ease;
            cursor: text;
        }
        .search:after {
            content: "";
            position: absolute;
            width: 3px;
            height: 20px;
            right: -5px;
            top: 21px;
            background: #023A51;
            border-radius: 3px;
            transform: rotate(-45deg);
            transition: all 200ms ease;
        }
        .search.active,
        .search:hover {
            width: 100%;
            margin-right: 0;
        }
        .search.active:after,
        .search:hover:after {
            height: 0;
        }
        .search input {
            width: 100%;
            border: none;
            box-sizing: border-box;
            font-family: Helvetica;
            font-size: 15px;
            color: inherit;
            background: transparent;
            outline-width: 0;
        }

        #searchFaq{
            width: 75%;
            border-radius: 50px;
            margin: 0 auto;
            padding-left: 30px;
        }

        .searchIcon1 {
            position: absolute;
            left: 90px;
            top: 11px;
        }

        .searchIcon2 {
            position: absolute;
            left: 25px;
            top: 11px;
        }

        #searchFaq.active,
        #searchFaq:hover {
            width: 100%;
        }
    </style>

    <div class="page-content">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="col-8 mb-3" id="message-alert">
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @elseif(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h3 class="panel-title text-primary pt-3">KNOWLEDGE ZONE</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <i class="fa fa-search searchIcon searchIcon1"></i>
                                    <input type="text" class="form-control" name="searchFaq" id="searchFaq" onmouseenter="MoveFaqSearchIcon(1);" onmouseleave="MoveFaqSearchIcon(2);" onfocus="SearchFaqActive(this);" onfocusout="SearchFaqBlur(this);" onkeyup="SearchFaq(this);" />
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>
                        {{--Faq Search Result--}}
                        <div class="row mt-4" id="searchResultsFaqDiv" style="display: none;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
    @include('dashboard.training-room.scripts')
    @include('dashboard.includes.questionAnswerModal')
@endsection
