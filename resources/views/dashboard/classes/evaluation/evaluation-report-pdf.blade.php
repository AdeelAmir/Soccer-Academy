<!DOCTYPE html>
<html lang="en">
  <head>
    <!--<link rel="stylesheet" href="{{asset('public/assets/css/invoicestyle.css')}}" type="text/css" />-->
    <meta charset="utf-8">
    <!-- Favicon -->
    <link rel="icon" href="{{asset('public/assets/images/Logo.png')}}" type="image/png">
    <title>MSA Evaluation Report</title>
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
      .tableRowBackgroundColor{
        background-color: #ededed;
      }
      .tableRowTextColor{
        color: #7b7b7b;
      }
      .text-left{
        text-align: left;
      }
    </style>
  </head>
  <body class="bg-white">
    <div class="container-fluid">
      <div class="row">
          <div class="col-md-6" style="text-align: left;">
            <h4 class="primaryFontFamily primaryColor pt-1 mt-2" style="font-size: 14px;">MY SOCCER ACADEMY</h4>
            <p class="primaryFontFamily" style="font-size: 12px;">17 N John Young Pkwy,<br>Kissimmee, FL 34741<br>www.mySoccerAcadeny.com<br>info@mysocceracademy.com</p>
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
              <table style="margin-top: 14px;">
                <tbody>
                  <tr>
                    <td class="primaryFontFamily primaryColor" style="font-size: 14px;padding-right: 30px;">Player's Name</td>
                    <td class="primaryFontFamily" style="font-size: 14px;">{{$player_name}}</td>
                  </tr>
                  <tr style="">
                    <td class="primaryFontFamily primaryColor" style="font-size: 14px;padding-right: 30px;padding-top: 5px;">ID</td>
                    <td class="primaryFontFamily" style="font-size: 14px;padding-top: 5px;">{{$player_id}}</td>
                  </tr>
                  <tr>
                    <td class="primaryFontFamily primaryColor" style="font-size: 14px;padding-right: 30px;padding-top: 5px;">{{$loggedin_user_role}}</td>
                    <td class="primaryFontFamily" style="font-size: 14px;padding-top: 5px;">{{$loggedin_user_name}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-md-6" style="float:right;">
              <table style="margin-top: 14px;">
                <tbody>
                  <tr>
                    <td class="primaryFontFamily primaryColor" style="font-size: 14px;padding-right: 30px;">Report No</td>
                    <td class="primaryFontFamily" style="font-size: 14px;"><i>{{$report_no}}</i></td>
                  </tr>
                  <tr style="">
                    <td class="primaryFontFamily primaryColor" style="font-size: 14px;padding-right: 30px;padding-top: 5px;">Date</td>
                    <td class="primaryFontFamily" style="font-size: 14px;padding-top: 5px;"><i>{{$date}}</i></td>
                  </tr>
                  <tr>
                    <td class="primaryFontFamily primaryColor" style="font-size: 14px;padding-right: 30px;padding-top: 5px;">Category</td>
                    <td class="primaryFontFamily" style="font-size: 14px;padding-top: 5px;"><i>{{$category}}</i></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br><br><br><br><br><br><br>
    <div class="container-fluid" style="margin-bottom: 1em;">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered" style="text-align: center;">
              <thead>
              <tr>
                  <th colspan="2" style="padding-bottom: 10px;"><strong>Performance Evaluation Section</strong></th>
                  <th><strong>Grade</strong></th>
              </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="3" class="tableRowBackgroundColor tableRowTextColor"><strong>Behavior</strong></td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">RESPECTIVE</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">Positive feeling or action shown towards someone.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($respective == "-") {
                      echo "NC";
                  }
                  elseif ($respective > 4 && $respective <= 5) {
                      echo "E";
                  }
                  elseif ($respective > 3 && $respective <= 4) {
                      echo "S";
                  }
                  elseif ($respective > 2 && $respective <= 3) {
                      echo "N";
                  }
                  elseif ($respective >= 0 && $respective <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">ATTENTION</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">Notice taken of someone or something.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($attention == "-") {
                      echo "NC";
                  }
                  elseif ($attention > 4 && $attention <= 5) {
                      echo "E";
                  }
                  elseif ($attention > 3 && $attention <= 4) {
                      echo "S";
                  }
                  elseif ($attention > 2 && $attention <= 3) {
                      echo "N";
                  }
                  elseif ($attention >= 0 && $attention <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">CONCENTRATION</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">The action or power of focusing one's attention or mental effort.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($concentration == "-") {
                      echo "NC";
                  }
                  elseif ($concentration > 4 && $concentration <= 5) {
                      echo "E";
                  }
                  elseif ($concentration > 3 && $concentration <= 4) {
                      echo "S";
                  }
                  elseif ($concentration > 2 && $concentration <= 3) {
                      echo "N";
                  }
                  elseif ($concentration >= 0 && $concentration <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">LEADERSHIP</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">The action of leading a group of people or an organization.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($leadership == "-") {
                      echo "NC";
                  }
                  elseif ($leadership > 4 && $leadership <= 5) {
                      echo "E";
                  }
                  elseif ($leadership > 3 && $leadership <= 4) {
                      echo "S";
                  }
                  elseif ($leadership > 2 && $leadership <= 3) {
                      echo "N";
                  }
                  elseif ($leadership >= 0 && $leadership <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">ENERGETIC</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">Possessing or exhibiting energy. especially in the audience.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($energetic == "-") {
                      echo "NC";
                  }
                  elseif ($energetic > 4 && $energetic <= 5) {
                      echo "E";
                  }
                  elseif ($energetic > 3 && $energetic <= 4) {
                      echo "S";
                  }
                  elseif ($energetic > 2 && $energetic <= 3) {
                      echo "N";
                  }
                  elseif ($energetic >= 0 && $energetic <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">DISCIPLINE</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">Is the player listening to the coach?</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($discipline == "-") {
                      echo "NC";
                  }
                  elseif ($discipline > 4 && $discipline <= 5) {
                      echo "E";
                  }
                  elseif ($discipline > 3 && $discipline <= 4) {
                      echo "S";
                  }
                  elseif ($discipline > 2 && $discipline <= 3) {
                      echo "N";
                  }
                  elseif ($discipline >= 0 && $discipline <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="3" class="tableRowBackgroundColor tableRowTextColor"><strong>Coordination</strong></td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">RUNNING</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">The action or movement of an individual.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($running == "-") {
                      echo "NC";
                  }
                  elseif ($running > 4 && $running <= 5) {
                      echo "E";
                  }
                  elseif ($running > 3 && $running <= 4) {
                      echo "S";
                  }
                  elseif ($running > 2 && $running <= 3) {
                      echo "N";
                  }
                  elseif ($running >= 0 && $running <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="3" class="tableRowBackgroundColor tableRowTextColor"><strong>Pass / Kick / Control</strong></td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">PASSING AND RECEIVING</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">Keep possession of the ball between different players.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($passing_receiving == "-") {
                      echo "NC";
                  }
                  elseif ($passing_receiving > 4 && $passing_receiving <= 5) {
                      echo "E";
                  }
                  elseif ($passing_receiving > 3 && $passing_receiving <= 4) {
                      echo "S";
                  }
                  elseif ($passing_receiving > 2 && $passing_receiving <= 3) {
                      echo "N";
                  }
                  elseif ($passing_receiving >= 0 && $passing_receiving <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">KICKING</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">Ways to shoot, pass, and kick a soccer ball, and which parts of the foot to use.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($kicking == "-") {
                      echo "NC";
                  }
                  elseif ($kicking > 4 && $kicking <= 5) {
                      echo "E";
                  }
                  elseif ($kicking > 3 && $kicking <= 89) {
                      echo "S";
                  }
                  elseif ($kicking > 2 && $kicking <= 3) {
                      echo "N";
                  }
                  elseif ($kicking >= 0 && $kicking <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">BALL CONTROL</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">Ability to handle the ball with a constructive first touch.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($ball_control == "-") {
                      echo "NC";
                  }
                  elseif ($ball_control > 4 && $ball_control <= 5) {
                      echo "E";
                  }
                  elseif ($ball_control > 3 && $ball_control <= 4) {
                      echo "S";
                  }
                  elseif ($ball_control > 2 && $ball_control <= 3) {
                      echo "N";
                  }
                  elseif ($ball_control >= 0 && $ball_control <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">SHOOTING</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">To strike the ball accurately to help improve your chances of scoring a goal.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($shooting == "-") {
                      echo "NC";
                  }
                  elseif ($shooting > 4 && $shooting <= 5) {
                      echo "E";
                  }
                  elseif ($shooting > 3 && $shooting <= 4) {
                      echo "S";
                  }
                  elseif ($shooting > 2 && $shooting <= 3) {
                      echo "N";
                  }
                  elseif ($shooting >= 0 && $shooting <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="3" class="tableRowBackgroundColor tableRowTextColor"><strong>Physical</strong></td>
                </tr>
                <tr>
                  <td style="width: 25%;" class="tableRowTextColor text-left">BALANCE</td>
                  <td style="width: 65%;" class="tableRowTextColor text-left">Distribution of weight enables someone to remain upright and steady.</td>
                  <td style="width: 10%;" class="tableRowTextColor text-left">
                  <?php
                  if ($balance == "-") {
                      echo "NC";
                  }
                  elseif ($balance > 4 && $balance <= 5) {
                      echo "E";
                  }
                  elseif ($balance > 3 && $balance <= 4) {
                      echo "S";
                  }
                  elseif ($balance > 2 && $balance <= 3) {
                      echo "N";
                  }
                  elseif ($balance >= 0 && $balance <= 2) {
                      echo "U";
                  }
                  ?>
                  </td>
                </tr>
              </tbody>
           </table>
         </div>

         <br>
         <div class="col-md-12">
           <hr style="color: #fc4440;">
         </div>
         <br>

         <div class="col-md-12">
           <table class="table table-bordered" style="text-align: center;">
               <thead>
               <tr>
                   <th colspan="5" style="padding-bottom: 10px;background-color: #ffffff;font-size: 14px;" class="primaryColor text-left"><strong>Evaluation Key</strong></th>
               </tr>
               </thead>
               <tbody>
                 <tr style="background-color: #ededed;">
                   <td style="width: 20%;" class="tableRowTextColor">
                     Excellent
                     <br><br>
                     5-4
                     <br><br>
                     E
                   </td>
                   <td style="width: 20%;" class="tableRowTextColor">
                     Satisfactory
                     <br><br>
                     4-3
                     <br><br>
                     S
                   </td>
                   <td style="width: 20%;" class="tableRowTextColor">
                     Need Work
                     <br><br>
                     3-2
                     <br><br>
                     N
                   </td>
                   <td style="width: 20%;" class="tableRowTextColor">
                     Under Performance
                     <br><br>
                     0-2
                     <br><br>
                     U
                   </td>
                   <td style="width: 20%;" class="tableRowTextColor">
                     Not Covered
                     <br><br>
                     -
                     <br><br>
                     NC
                   </td>
                 </tr>
               </tbody>
            </table>
          </div>
      </div>
    </div>
  </body>
</html>
