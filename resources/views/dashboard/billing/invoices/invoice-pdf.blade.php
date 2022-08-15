<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MSA Invoice</title>
    <style>
      .body{
        font-family:"Calibri, sans-serif";
      }
      .primaryColor{
        color: #fc4440;
      }
      .primaryFontFamily{
        font-family:"Calibri, sans-serif";
      }
      .titleSetting{
        font-size: 1.1em;
        text-align: right;
      }
      .regardsSetting{
        font-size: 1.1em;
        text-align: left;
        margin-left: -10px;
      }
      .table{
        width:100%;
        max-width:100%;
        margin-bottom:18px;
        border-collapse: collapse;
        border-spacing: 2px;
      }
      th{
        font-weight: 400;
        font-size: 12px;
        color: white;
        text-align: inherit;
        font-family:"Calibri, sans-serif";
      }
      td{
        font-size: 12px;
        font-family:"Calibri, sans-serif";
      }
      thead{
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
    </style>
  </head>
  <body class="bg-white">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-6" style="text-align: left;">
            <h4 class="primaryFontFamily primaryColor pt-1 mt-2" style="font-size: 14px;">MY SOCCER ACADEMY</h4>
            <p class="primaryFontFamily" style="font-size: 12px;">17 N John Young Pkwy,<br>Kissimmee, FL 34741</p>
          </div>
          <div class="col-md-6">
            <img style="height:85px;float:right;margin-top: -90px;" src="{{asset('public/assets/images/Logo.jpg')}}" alt="" class="img-fluid" />
            <!-- <img style="height:85px;float:right;margin-top: -90px;" src="http://localhost/soccer_acedmy/public/assets/images/Logo.jpg" alt="" class="img-fluid" /> -->
          </div>
      </div>
    </div>
    <br>
    <div>
      <hr style="color: #fc4440;">
    </div>
    <br>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-6" style="float:left;">
                <p class="primaryColor">Bill To</p>
                <p class="primaryFontFamily" style="font-size: 12px;">{{$parent_name}}</p>
                <p class="primaryFontFamily" style="font-size: 12px;margin-top: -5px;">{{$parent_address}}</p>
            </div>
            <div class="col-md-6" style="float:right;">
              <table style="margin-top: 14px;">
                <tbody>
                  <tr>
                    <td class="primaryFontFamily primaryColor" style="font-size: 14px;padding-right: 30px;">Invoice No</td>
                    <td class="primaryFontFamily" style="font-size: 14px;">{{$invoice_no}}</td>
                  </tr>
                  <tr style="">
                    <td class="primaryFontFamily primaryColor" style="font-size: 14px;padding-right: 30px;padding-top: 5px;">Invoice Date</td>
                    <td class="primaryFontFamily" style="font-size: 14px;padding-top: 5px;">{{$invoice_date}}</td>
                  </tr>
                  @if($due_date != "")
                  <tr>
                    <td class="primaryFontFamily primaryColor" style="font-size: 14px;padding-right: 30px;padding-top: 5px;">Due Date</td>
                    <td class="primaryFontFamily" style="font-size: 14px;padding-top: 5px;">{{$due_date}}</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br><br><br><br><br><br><br>
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
    <div class="container-fluid" style="margin-bottom: 1em;">
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
        <div class="col-md-12" style="float:right;">
          <table style="margin-top: 14px;">
            <tbody>
              <tr>
                <td class="primaryFontFamily" style="font-size: 14px;padding-right: 30px;">Subtotal</td>
                <td class="primaryFontFamily" style="font-size: 14px;">{{number_format((float)$subtotal, 2, '.', '')}}</td>
              </tr>
              @if($discount_percentage > 0)
              <tr style="">
                <td class="primaryFontFamily" style="font-size: 14px;padding-right: 30px;padding-top: 5px;">Discount {{number_format((float)$discount_percentage, 2, '.', '')}}%</td>
                <td class="primaryFontFamily" style="font-size: 14px;padding-top: 5px;">{{number_format((float)$discount_price, 2, '.', '')}}</td>
              </tr>
              @endif
              <tr style="">
                <td class="primaryFontFamily" style="font-size: 14px;padding-right: 30px;padding-top: 5px;">Processing Fee {{number_format((float)$processing_fee_percentage, 2, '.', '')}}%</td>
                <td class="primaryFontFamily" style="font-size: 14px;padding-top: 5px;">{{number_format((float)$processing_fee_price, 2, '.', '')}}</td>
              </tr>
              <tr style="">
                <td class="primaryFontFamily" style="font-size: 14px;padding-right: 30px;padding-top: 5px;">Sales Tax {{number_format((float)$tax_rate_percentage, 2, '.', '')}}%</td>
                <td class="primaryFontFamily" style="font-size: 14px;padding-top: 5px;">{{number_format((float)$tax_rate_price, 2, '.', '')}}</td>
              </tr>
              <tr>
                <td class="primaryFontFamily" style="font-size: 14px;padding-right: 30px;padding-top: 5px;">Total</td>
                <td class="primaryFontFamily" style="font-size: 14px;padding-top: 5px;">${{number_format((float)$total_bill, 2, '.', '')}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>
