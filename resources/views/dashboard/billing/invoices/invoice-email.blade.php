<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MSA Invoice</title>
    <style>
        .body {
            font-family: "Calibri, sans-serif";
        }

        .primaryColor {
            color: #fc4440;
        }

        .primaryFontFamily {
            font-family: "Calibri, sans-serif";
        }

        .titleSetting {
            font-size: 1.1em;
            text-align: right;
        }

        .regardsSetting {
            font-size: 1.1em;
            text-align: left;
            margin-left: -10px;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
            border-spacing: 2px;
        }

        th {
            font-weight: 400;
            font-size: 12px;
            color: white;
            text-align: inherit;
            font-family: "Calibri, sans-serif";
        }

        td {
            font-size: 12px;
            font-family: "Calibri, sans-serif";
        }

        thead {
            background-color: #fc4440;
        }

        .table-bordered thead td, .table-bordered thead th {
            border-bottom-width: 2px;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .table td, .table th {
            padding: 0.3rem;
        }

        .button {
            background-color: #fc4440;
            border: 1px solid #fc4440;
            color: white !important;
            padding: 5px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 50px;
        }

        .button:hover {
            background-color: transparent;
            color: #fc4440 !important;
        }

        /*Media Query for Mobile*/
        .width1 {
            width: 75%;
        }

        .width2 {
            width: 25%;
        }

        .fs-14 {
            font-size: 14px;
        }

        @media only screen and (max-width: 768px) {
            .width1 {
                width: 50%;
            }

            .width2 {
                width: 50%;
            }

            .fs-14 {
                font-size: 12px;
            }
        }
    </style>
</head>
<body class="bg-white">
<div class="container-fluid">
    <div class="row" style="display: flex; margin-bottom: 15px;">
        <div class="col-md-6" style="text-align: left; width: 50%;">
            <h4 class="primaryFontFamily primaryColor fs-14" style="margin-top: 15px; margin-bottom: 5px;">MY
                SOCCER ACADEMY</h4>
            <p class="primaryFontFamily mb-0" style="font-size: 12px; margin-top: 0; margin-bottom: 0;">17 N John Young
                Pkwy,<br>Kissimmee, FL 34741</p>
        </div>
        <div class="col-md-6" style="width: 50%;">
            <img style="height: 85px; float: right;" src="{{asset('public/assets/images/Logo.jpg')}}"
                 alt="" class="img-fluid"/>
        </div>
    </div>
</div>

<div>
    <hr style="border-color: #fc4440; margin: 0;">
</div>

<div class="container-fluid" style="margin-bottom: 30px;">
    <div class="row">
        <div class="col-md-12">
            <div class="row" style="display: flex;">
                <div class="width1">
                    <p class="primaryColor" style="margin-top: 20px; margin-bottom: 5px;">Bill To</p>
                    <p class="primaryFontFamily" style="font-size: 12px; margin-top: 0; margin-bottom: 5px;">{{$parent_name}}</p>
                    <p class="primaryFontFamily" style="font-size: 12px; margin-top: 0; margin-bottom: 0;">{{$parent_address}}</p>
                </div>
                <div class="width2">
                    <table style="width: 100%; margin-top: 20px;">
                        <tbody>
                        <tr>
                            <td class="primaryFontFamily primaryColor fs-14" style="width: 50%;">
                                Invoice No
                            </td>
                            <td class="primaryFontFamily fs-14" style="width: 50%; text-align: right;">{{$invoice_no}}</td>
                        </tr>
                        <tr style="">
                            <td class="primaryFontFamily primaryColor fs-14" style="width: 50%;">
                                Invoice Date
                            </td>
                            <td class="primaryFontFamily fs-14" style="width: 50%; text-align: right;">
                                {{$invoice_date}}
                            </td>
                        </tr>
                        @if($due_date != "")
                            <tr>
                                <td class="primaryFontFamily primaryColor fs-14" style="width: 50%;">
                                    Due Date
                                </td>
                                <td class="primaryFontFamily fs-14" style="width: 50%; text-align: right;">
                                    {{$due_date}}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$ItemTitle = array();
$ItemPrice = array();
$ItemQuantity = array();
if ($item_title != "") {
    $ItemTitle = json_decode($item_title);
}
if ($item_price != "") {
    $ItemPrice = json_decode($item_price);
}
if ($item_quantity != "") {
    $ItemQuantity = json_decode($item_quantity);
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered" style="text-align: center;">
                <thead>
                <tr>
                    <th style="width: 5%;">Qty</th>
                    <th style="width: 55%;">Description</th>
                    <th style="width: 20%;">Unit Price</th>
                    <th style="width: 20%;">Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($ItemTitle as $key => $description):
                $quantity = $ItemQuantity[$key];
                $unit_price = $ItemPrice[$key];
                $amount = $unit_price * $quantity;
                ?>
                <tr>
                    <td>{{$quantity}}</td>
                    <td>{{$description}}</td>
                    <td>{{number_format((float)$unit_price, 2, '.', '')}}</td>
                    <td>{{number_format((float)$amount, 2, '.', '')}}</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row" style="display: flex; margin-top: 15px;">
        <div class="width1"></div>
        <div class="width2" style="float: right;">
            <table style="width: 100%;">
                <tbody>
                <tr>
                    <td class="primaryFontFamily primaryColor fs-14" style="width: 50%;">Subtotal</td>
                    <td class="primaryFontFamily fs-14" style="width: 50%; text-align: right;">
                        {{number_format((float)$subtotal, 2, '.', '')}}
                    </td>
                </tr>
                @if($discount_percentage > 0)
                    <tr>
                        <td class="primaryFontFamily primaryColor fs-14" style="width: 50%;">
                            Discount {{number_format((float)$discount_percentage, 2, '.', '')}}%
                        </td>
                        <td class="primaryFontFamily fs-14"
                            style="text-align: right; width: 50%;">{{number_format((float)$discount_price, 2, '.', '')}}</td>
                    </tr>
                @endif
                <tr style="">
                    <td class="primaryFontFamily primaryColor fs-14" style="width: 50%;">
                        Processing Fee {{number_format((float)$processing_fee_percentage, 2, '.', '')}}%
                    </td>
                    <td class="primaryFontFamily fs-14"
                        style="text-align: right; width: 50%;">{{number_format((float)$processing_fee_price, 2, '.', '')}}</td>
                </tr>
                <tr style="">
                    <td class="primaryFontFamily primaryColor fs-14" style="width: 50%;">Sales
                        Tax {{number_format((float)$tax_rate_percentage, 2, '.', '')}}%
                    </td>
                    <td class="primaryFontFamily fs-14"
                        style="text-align: right; width: 50%;">{{number_format((float)$tax_rate_price, 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td class="primaryFontFamily primaryColor fs-14" style="width: 50%;">Total
                    </td>
                    <td class="primaryFontFamily fs-14" style="width: 50%; text-align: right;">
                        ${{number_format((float)$total_bill, 2, '.', '')}}</td>
                </tr>
                <tr>
                    <?php
                        $PaymentUrl = route('billing.invoices.payment-page', array(base64_encode($invoice_id)));
                    ?>
                    <td colspan="2" class="fs-14" style="width: 100%; text-align: center;">
                        <a href="{{$PaymentUrl}}" class="button" style="margin-top: 10px; width: 80%;" target="_blank">Pay Now</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
