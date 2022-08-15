<?php

namespace App\Http\Controllers;

use App\Helpers\SiteHelper;
use App\Models\Coupons;
use App\Models\LeadConversions;
use App\Models\OrderInvoices;
use App\Models\Orders;
use App\Models\StripeProducts;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserDetails;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\LeadDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Stripe\Customer;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\SubscriptionSchedule;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->to('login');
    }

    public function lead()
    {
        $States = DB::table('states')->get();
        $Locations = DB::table('player_locations')
            ->where('status', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();
        $FreeClasses = DB::table('classes')
            ->where('is_free', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();
        return view('home', compact('States', 'Locations', 'FreeClasses'));
    }

    public function verifyDuplicateLead($LastName, $Phone, $Zipcode) /*, $LeadId*/
    {
        $Lead = DB::table('leads')
            ->where('parentLastName', $LastName)
            ->where('parentPhone', $Phone)
            ->where('zipcode', $Zipcode)
            ->get();
        return $Lead;
        /*$Lead = array();
        if ($LeadId != "") {
            $Lead = DB::table('leads')
                ->where('parentLastName', $LastName)
                ->where('parentPhone', $Phone)
                ->where('zipcode', $Zipcode)
                ->get();
        } else {
            $Lead = DB::table('leads')
                ->where('parentLastName', $LastName)
                ->where('parentPhone', $Phone)
                ->where('zipcode', $Zipcode)
                ->where('id', '!=', $LeadId)
                ->get();
        }

        return $Lead;*/
    }

    public function store(Request $request)
    {
        $LeadId = $request['LeadId'];
        $ParentFirstName = $request['ParentFirstName'];
        $ParentLastName = $request['ParentLastName'];
        $ParentPhone1 = $request['ParentPhone1'];
        $ParentPhone2 = $request['ParentPhone2'];
        $ParentEmail = $request['ParentEmail'];
        $ParentDOB = $request['ParentDOB'];
        $State = $request['State'];
        $City = $request['City'];
        $Street = $request['Street'];
        $Zipcode = $request['Zipcode'];
        $Status = $request['Status'];
        $PlayerFirstName = $request['PlayerFirstName'];
        $PlayerLastName = $request['PlayerLastName'];
        $PlayerDOB = $request['PlayerDOB'];
        $PlayerEmail = $request['PlayerEmail'];
        $PlayerGender = $request['PlayerGender'];
        $PlayerRelationship = $request['PlayerRelationship'];
        $Location = $request['PlayerLocation'];
        $LocationZipcode = $request['PlayerLocationZipcode'];
        $Message = $request['Message'];
        $GetRegisterOrScheduleFreeClass = $request['getregister_or_schedulefreeclass'];
        $FreeClass = $request['FreeClass'];
        $FreeClassDate = null;
        $FreeClassTime = null;

        if ($ParentDOB != "") {
            $ParentDOB = Carbon::parse($ParentDOB)->format("Y-m-d");
        }

        // if free class is selected and free class time is given then get day and time
        if ($GetRegisterOrScheduleFreeClass == 2) {
            if ($FreeClass != "" && $request['free_class_date'] != "" && $request['free_class_time'] != "") {
                $FreeClassDate = Carbon::parse($request['free_class_date'])->format('Y-m-d');
                $FreeClassTime = $request['free_class_time'];
            }
        }

        $data = array();
        /*if (($ParentFirstName != "" || $ParentLastName != "") && $ParentPhone1 != "" && $Zipcode !== "") {*/
        if ($ParentLastName != "" && $ParentPhone1 != "" && $Zipcode != "") {
            if ($LeadId == "") {
                $LeadNumber = rand(1000000, 9999999);
                $isDuplicate = 0;

                // Verify Duplicate Lead
                $DuplicatedLeadInfo = $this->verifyDuplicateLead($ParentLastName, $ParentPhone1, $Zipcode);
                if(sizeof($DuplicatedLeadInfo) > 0) {
                    /* Duplicated Lead */
                    // Check if lead is converted
                    $LeadConversion = DB::table('lead_conversions')
                        ->where('lead_id', '=', $DuplicatedLeadInfo[0]->id)
                        ->get();
                    if(sizeof($LeadConversion) > 0) {
                        $data['status'] = 'lead_converted';
                    } else {
                        $data['status'] = 'success';
                    }
                    $data['lead_id'] = $DuplicatedLeadInfo[0]->id;
                    echo json_encode($data);
                    exit();
                }
                /*if ($ParentLastName != "" && $ParentPhone1 != "" && $Zipcode != "") {
                    $VerifyLead = $this->verifyDuplicateLead($ParentLastName, $ParentPhone1, $Zipcode, "");
                    if (count($VerifyLead) > 0) {
                        $LeadNumber = $VerifyLead[0]->lead_number;
                        $isDuplicate = 1;
                    }
                }*/

                DB::beginTransaction();
                $Affected = null;
                $Affected1 = null;
                $Data = array(
                    'lead_number' => $LeadNumber,
                    'parentFirstName' => $ParentFirstName,
                    'parentLastName' => $ParentLastName,
                    'parentPhone' => $ParentPhone1,
                    'parentPhone2' => $ParentPhone2,
                    'parentEmail' => $ParentEmail,
                    'parentDOB' => $ParentDOB,
                    'state' => $State,
                    'city' => $City,
                    'street' => $Street,
                    'zipcode' => $Zipcode,
                    'getregister_or_schedulefreeclass' => $GetRegisterOrScheduleFreeClass,
                    'is_duplicate' => $isDuplicate,
                    'created_by' => 1,
                    'created_at' => Carbon::now(),
                );
                if($Status != '') {
                    $Data['lead_status'] = $Status;
                }

                $Affected = Lead::create($Data);

                if ($Affected) {
                    DB::commit();
                    $data['lead_id'] = $Affected->id;
                    $data['status'] = 'success';
                } else {
                    DB::rollback();
                    $data['lead_id'] = '';
                    $data['status'] = 'error';
                }
            } else {
                DB::beginTransaction();
                $Affected = null;
                $Affected1 = null;
                $LeadNumber = "";
                $LeadDetails = DB::table('leads')->where('id', $LeadId)->get();
                $LeadNumber = $LeadDetails[0]->lead_number;
                $isDuplicate = $LeadDetails[0]->is_duplicate;

                // Verify Duplicate Lead
//                $DuplicatedLeadInfo = $this->verifyDuplicateLead($ParentLastName, $ParentPhone1, $Zipcode);
//                if(sizeof($DuplicatedLeadInfo) > 0) {
//                    /* Duplicated Lead */
//                    $data['lead_id'] = $DuplicatedLeadInfo[0]->id;
//                    $data['status'] = 'success';
//                    echo json_encode($data);
//                    exit();
//                }
                /*if ($isDuplicate == 0) {
                    if ($ParentLastName != "" && $ParentPhone1 != "" && $Zipcode != "") {
                        $VerifyLead = $this->verifyDuplicateLead($ParentLastName, $ParentPhone1, $Zipcode, $LeadId);
                        if (count($VerifyLead) > 0) {
                            $LeadNumber = $VerifyLead[0]->lead_number;
                            $isDuplicate = 1;
                        }
                    }
                }*/

                $Data = array(
                    'lead_number' => $LeadNumber,
                    'parentFirstName' => $ParentFirstName,
                    'parentLastName' => $ParentLastName,
                    'parentPhone' => $ParentPhone1,
                    'parentPhone2' => $ParentPhone2,
                    'parentEmail' => $ParentEmail,
                    'parentDOB' => $ParentDOB,
                    'state' => $State,
                    'city' => $City,
                    'street' => $Street,
                    'zipcode' => $Zipcode,
                    'getregister_or_schedulefreeclass' => $GetRegisterOrScheduleFreeClass,
                    'is_duplicate' => $isDuplicate,
                    'updated_at' => Carbon::now(),
                );
                if($Status != '') {
                    $Data['lead_status'] = $Status;
                }
                $Affected = DB::table('leads')
                    ->where('id', $LeadId)
                    ->update($Data);

                // Delete old lead details
                DB::table('lead_details')->where('lead_id', $LeadId)->delete();
                if (($PlayerFirstName != "" || $PlayerLastName != "") && $PlayerDOB != "" && $PlayerGender != "") {
                    // Add new player
                    $startDate = Carbon::parse($PlayerDOB);
                    $endDate = Carbon::now();
                    $PlayerAge = $startDate->diffInYears($endDate);
                    $Affected1 = LeadDetails::create([
                        'lead_id' => $LeadId,
                        'playerFirstName' => $PlayerFirstName,
                        'playerLastName' => $PlayerLastName,
                        'playerDOB' => Carbon::parse($PlayerDOB)->format("Y-m-d"),
                        'playerEmail' => $PlayerEmail,
                        'playerAge' => $PlayerAge,
                        'playerGender' => $PlayerGender,
                        'playerRelationship' => $PlayerRelationship,
                        'location' => $Location,
                        'locationZipcode' => $LocationZipcode,
                        'message' => $Message,
                        'free_class' => $FreeClass,
                        'free_class_date' => $FreeClassDate,
                        'free_class_time' => $FreeClassTime,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }

                if ($Affected) {
                    DB::commit();
                    $data['lead_id'] = $LeadId;
                    $data['status'] = 'success';
                } else {
                    DB::rollback();
                    $data['lead_id'] = $LeadId;
                    $data['status'] = 'error';
                }
            }
        } else{
            $data['lead_id'] = '';
            $data['status'] = 'error';
        }
        echo json_encode($data);
    }

    public function update(Request $request)
    {
        $LeadId = $request['lead_id'];
        $ParentFirstName = $request['parentFirstName'];
        $ParentLastName = $request['parentLastName'];
        $ParentPhone1 = $request['parentPhone'];
        $ParentPhone2 = $request['parentPhone2'];
        $ParentEmail = $request['parentEmail'];
        $ParentDOB = $request['parentDOB'];
        $ParentGender = null;
        $State = $request['state'];
        $City = $request['city'];
        $Street = $request['street'];
        $Zipcode = $request['zipcode'];
        $PlayerFirstName = $request['playerFirstName'];
        $PlayerLastName = $request['playerLastName'];
        $PlayerDOB = $request['playerDOB'];
        $PlayerEmail = $request['playerEmail'];
        $PlayerGender = $request['playerGender'];
        $PlayerRelationship = $request['playerRelationship'];
        $Location = $request['location'];
        $LocationZipcode = $request['locationZipcode'];
        $Message = $request['message'];
        $GetRegisterOrScheduleFreeClass = $request['getregister_or_schedulefreeclass'];
        $FreeClass = $request['free_class'];
        $FreeClassDate = null;
        $FreeClassTime = null;

        if ($ParentDOB != "") {
            $ParentDOB = Carbon::parse($ParentDOB)->format("Y-m-d");
        }

        // if free class is selected and free class time is given then get day and time
        if ($GetRegisterOrScheduleFreeClass == 2) {
            $FreeClass = $request['free_class'];
            if ($request['free_class_date'] != "" && $request['free_class_time'] != ""){
                $FreeClassDate = Carbon::parse($request['free_class_date'])->format('Y-m-d');
                $FreeClassTime = $request['free_class_time'];
            }
        }

        if ($LeadId != "")
        {
            $LeadNumber = "";
            $LeadDetails = DB::table('leads')->where('id', $LeadId)->get();
            $LeadNumber = $LeadDetails[0]->lead_number;
            $isDuplicate = $LeadDetails[0]->is_duplicate;

            // Verify Duplicate Lead
            if ($isDuplicate == 0) {
                if ($ParentLastName != "" && $ParentPhone1 != "" && $Zipcode != "") {
                    $VerifyLead = $this->verifyDuplicateLead($ParentLastName, $ParentPhone1, $Zipcode, $LeadId);
                    if (count($VerifyLead) > 0) {
                        $LeadNumber = $VerifyLead[0]->lead_number;
                        $isDuplicate = 1;
                    }
                }
            }

            DB::beginTransaction();
            $Affected = null;
            $Affected = DB::table('leads')
                        ->where('id', $LeadId)
                        ->update([
                            'lead_number' => $LeadNumber,
                            'parentFirstName' => $ParentFirstName,
                            'parentLastName' => $ParentLastName,
                            'parentPhone' => $ParentPhone1,
                            'parentPhone2' => $ParentPhone2,
                            'parentEmail' => $ParentEmail,
                            'parentDOB' => $ParentDOB,
                            'state' => $State,
                            'city' => $City,
                            'street' => $Street,
                            'zipcode' => $Zipcode,
                            'getregister_or_schedulefreeclass' => $GetRegisterOrScheduleFreeClass,
                            'lead_status' => 6, /*Schedule for class*/
                            'is_duplicate' => $isDuplicate,
                            'updated_at' => Carbon::now(),
                        ]);

            // Delete old lead details
            DB::table('lead_details')->where('lead_id', $LeadId)->delete();
            if (($PlayerFirstName != "" || $PlayerLastName != "") && $PlayerDOB != "") {
                $startDate = Carbon::parse($PlayerDOB);
                $endDate = Carbon::now();
                $PlayerAge = $startDate->diffInYears($endDate);
                $Affected1 = LeadDetails::create([
                    'lead_id' => $LeadId,
                    'playerFirstName' => $PlayerFirstName,
                    'playerLastName' => $PlayerLastName,
                    'playerDOB' => Carbon::parse($PlayerDOB)->format("Y-m-d"),
                    'PlayerEmail' => $PlayerEmail,
                    'playerAge' => $PlayerAge,
                    'playerGender' => $PlayerGender,
                    'playerRelationship' => $PlayerRelationship,
                    'location' => $Location,
                    'locationZipcode' => $LocationZipcode,
                    'message' => $Message,
                    'free_class' => $FreeClass,
                    'free_class_date' => $FreeClassDate,
                    'free_class_time' => $FreeClassTime,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            /*Parent Registration*/
            if ($PlayerRelationship == "Father") {
                $ParentGender = "Male";
            } elseif ($PlayerRelationship == "Mother") {
                $ParentGender = "Female";
            }
            $UserId = substr($ParentFirstName, 0, 1) . substr($ParentLastName, 0, 1) . mt_rand(10000, 99999);
            $Affected1 = User::create([
                'userId' => $UserId,
                'email' => $ParentEmail,
                'password' => bcrypt($request->post('_password')),
                'role_id' => 5,
                'status' => 1,
                'default_pass_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $NewUserId = $Affected1->id;
            $Affected2 = UserDetails::create([
                'user_id' => $NewUserId,
                'firstName' => $ParentFirstName,
                'lastName' => $ParentLastName,
                'dob' => $ParentDOB,
                'gender' => $ParentGender,
                'street' => $Street,
                'city' => $City,
                'state' => $State,
                'zipcode' => $Zipcode,
                'phone1' => $ParentPhone1,
                'phone2' => $ParentPhone2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            /*User Power Entry*/
            SiteHelper::InsertUserPower($NewUserId, 0, 0, 0);

            /*Training Room Entries*/
            SiteHelper::InsertTrainingRoom(5, $NewUserId);

            /*Player Registration*/
            $PUserId = substr($PlayerFirstName, 0, 1) . substr($PlayerLastName, 0, 1) . mt_rand(10000, 99999);
            $PlayerPassword = "";
            $UserBirthdayMonth = Carbon::parse($PlayerDOB)->format('M');
            $UserBirthdayYear = Carbon::parse($PlayerDOB)->format('Y');
            $PlayerPassword .= $UserBirthdayMonth . "!" . $UserBirthdayYear;
            $Affected3 = User::create([
                'userId' => $PUserId,
                'parent_id' => $NewUserId,
                'email' => $request->post('playerEmail'),
                'password' => bcrypt($PlayerPassword),
                'role_id' => 6,
                'status' => 1,
                'default_pass_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $PNewUserId = $Affected3->id;
            $Affected4 = UserDetails::create([
                'user_id' => $PNewUserId,
                'parent_id' => $NewUserId,
                'firstName' => $PlayerFirstName,
                'lastName' => $PlayerLastName,
                'dob' => Carbon::parse($PlayerDOB)->format("Y-m-d"),
                'gender' => $PlayerGender,
                'athletesParent' => $NewUserId,
                'athletesRelationship' => $PlayerRelationship,
                'street' => $Street,
                'city' => $City,
                'state' => $State,
                'zipcode' => $Zipcode,
                'phone1' => $ParentPhone1,
                'phone2' => $ParentPhone2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            /*User Power Entry*/
            SiteHelper::InsertUserPower($PNewUserId, 0, 0, 0);

            /*Training Room Entries*/
            SiteHelper::InsertTrainingRoom(6, $PNewUserId);

            LeadConversions::create([
                'lead_id' => $LeadId,
                'order_id' => null,
                'parent_id' => $NewUserId,
                'conversion_type' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            DB::commit();

            /*login*/
            $userData = array(
                'email' => $ParentEmail,
                'password' => $request->post('_password')
            );
            if(Auth::attempt($userData)) {
                Session::put('user_role', 5);
                return redirect('login');
            } else {
                return redirect()->route('createLeadRoute')->with('success', 'Information has been submitted successfully');
            }
        } else {
            return redirect()->route('createLeadRoute')->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function getFreeClassDays(Request $request)
    {
        $DaysIncluded = array();
        $DaysExcluded = array();
        $ClassTimings = DB::table('class_timings')
                        ->where('class_id', $request['class_id'])
                        ->get();

        foreach ($ClassTimings as $index => $timing)
        {
            if ($timing->day == 1){
                array_push($DaysIncluded, 1);
            } elseif ($timing->day == 2){
                array_push($DaysIncluded, 2);
            } elseif ($timing->day == 3){
                array_push($DaysIncluded, 3);
            } elseif ($timing->day == 4){
                array_push($DaysIncluded, 4);
            } elseif ($timing->day == 5){
                array_push($DaysIncluded, 5);
            } elseif ($timing->day == 6){
                array_push($DaysIncluded, 6);
            } elseif ($timing->day == 7){
                array_push($DaysIncluded, 0);
            }
        }

        for ($i=0; $i < 7; $i++)
        {
            if (!in_array($i, $DaysIncluded)){
                array_push($DaysExcluded, $i);
            }
        }
        echo json_encode($DaysExcluded);
    }

    public function getFreeClassTiming(Request $request)
    {
        $timestamp = strtotime($request['class_date']);
        $class_day = date('D', $timestamp);
        $Day = "";
        if ($class_day == "Mon"){
            $Day = 1;
        } elseif ($class_day == "Tue"){
            $Day = 2;
        } elseif ($class_day == "Wed"){
            $Day = 3;
        } elseif ($class_day == "Thu"){
            $Day = 4;
        } elseif ($class_day == "Fri"){
            $Day = 5;
        } elseif ($class_day == "Sat"){
            $Day = 6;
        } elseif ($class_day == "Sun"){
            $Day = 7;
        }

        $ClassTimings = DB::table('class_timings')
            ->where('class_id', $request['class_id'])
            ->where('day', $Day)
            ->get();

        $options = "<option value='' selected>Select</option>";
        foreach ($ClassTimings as $index => $timing)
        {
            $class_time = Carbon::parse($timing->time)->format('h:i A');
            $options .= "<option value='". $timing->time ."'>" . $class_time . "</option>";
        }
        echo json_encode($options);
    }

    public function LoadCities(Request $request)
    {
        $State = $request['State'];
        // Cities list
        $cities = DB::table('locations')
            ->where('state_name', '=', $State)
            ->orderBy("city", "ASC")
            ->get()
            ->unique("city");
        $options = '';
        if($request->has('ServingLocation')){
            $options = '<option value="" selected>Select City</option>';
        }
        else{
            $options = '<option value="" selected>Select City</option>';
        }
        foreach ($cities as $city) {
            $options .= '<option value="' . $city->city . '">' . $city->city . '</option>';
        }

        echo json_encode($options);
    }

    public function stripeSetup(Request $request)
    {
        $Price = floatval(ltrim($request->post('Price'), '$'));
        $FirstName = $request->post('FirstName');
        $LastName = $request->post('LastName');
        $Phone = $request->post('Phone');
        $stripe = new StripeClient(Config::get('services.stripe.secret'));
        try {
            Stripe::setApiKey(Config::get('services.stripe.secret'));
            $customer = Customer::create([
                'name' => $FirstName . ' ' . $LastName,
                'phone' => $Phone
            ]);
            $StripeCustomerId = $customer->id;
            /*$StripeCustomerId = 'cus_LV2DEuMiXEtIxp';*/
            $PaymentIntent = $stripe->paymentIntents->create([
                'amount' => $Price * 100,
                'currency' => 'usd',
                'customer' => $StripeCustomerId,
                'automatic_payment_methods' => ['enabled' => true],
                'setup_future_usage' => 'off_session',
            ]);
            $PaymentIntentId = $PaymentIntent->id;
            $ClientSecret = $PaymentIntent->client_secret;

            return response(['status' => true, 'payment_intent' => $PaymentIntentId, 'client_secret' => $ClientSecret, 'customer_id' => $StripeCustomerId]);
        } catch (\Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function stripeOrderCreate(Request $request)
    {
        $OrderCheck = DB::table('orders')
            ->where('payment_intent_id', '=', $request->post('PaymentIntentId'))
            ->where('client_secret_id', '=', $request->post('ClientSecret'))
            ->get();

        if(sizeof($OrderCheck) > 0) {
            /*Update*/
            DB::table('orders')
                ->where('payment_intent_id', '=', $request->post('PaymentIntentId'))
                ->where('client_secret_id', '=', $request->post('ClientSecret'))
                ->update([
                    'email' => $request->post('Email'),
                    'password' => $request->post('Password'),
                    'lead_id' => $request->post('LeadId'),
                    'package_id' => $request->post('PackageId'),
                    'category_id' => $request->post('CategoryId'),
                    'selected_days' => $request->post('SelectedDays'),
                    'package_type' => $request->post('PackageType'),
                    'registration_fee' => floatval($request->post('RegistrationFee')),
                    'coupon_code_id' => $request->post('CouponCode'),
                    'coupon_amount' => $request->post('CouponAmount'),
                    'sub_fee' => floatval($request->post('SubPrice')),
                    'tax' => floatval($request->post('Tax')),
                    'processing' => floatval($request->post('ProcessingFee')),
                    'amount' => floatval(ltrim($request->post('Price'), '$')),
                    'phone' => $request->post('Phone'),
                    'state' => $request->post('State'),
                    'city' => $request->post('City'),
                    'street' => $request->post('Street'),
                    'zipcode' => $request->post('ZipCode'),
                    'status' => 0,
                    'updated_at' => Carbon::now()
                ]);
        } else {
            /* Create */
            $OrderId = '';
            $PreviousOrderId = DB::table('orders')
                ->max('id');
            if ($PreviousOrderId != 0) {
                $OrderId = str_pad($PreviousOrderId + 1, 8, '0', STR_PAD_LEFT);
            } else {
                $OrderId = '00000001';
            }
            $TotalInvoices = 0;
            $CreatedInvoices = 0;
            $Status = 1;
            if($request->post('PackageType') == 'monthly') {
                $TotalInvoices = 12;
                $CreatedInvoices = 1;
                $Status = 1; /*Active*/
            } elseif($request->post('PackageType') == 'semi') {
                $TotalInvoices = 6;
                $CreatedInvoices = 1;
                $Status = 1; /*Active*/
            } elseif($request->post('PackageType') == 'annual') {
                $TotalInvoices = 1;
                $CreatedInvoices = 1;
                $Status = 4; /*Completed*/
            }
            Orders::create([
                'order_id' => $OrderId,
                'email' => $request->post('Email'),
                'password' => $request->post('Password'),
                'payment_intent_id' => $request->post('PaymentIntentId'),
                'client_secret_id' => $request->post('ClientSecret'),
                'stripe_customer_id' => $request->post('StripeCustomerId'),
                'lead_id' => $request->post('LeadId'),
                'package_id' => $request->post('PackageId'),
                'category_id' => $request->post('CategoryId'),
                'selected_days' => $request->post('SelectedDays'),
                'package_type' => $request->post('PackageType'),
                'total_invoices' => $TotalInvoices,
                'created_invoices' => $CreatedInvoices,
                'registration_fee' => floatval($request->post('RegistrationFee')),
                'coupon_code_id' => $request->post('CouponCode'),
                'coupon_amount' => $request->post('CouponAmount'),
                'sub_fee' => floatval($request->post('SubPrice')),
                'tax' => floatval($request->post('Tax')),
                'processing' => floatval($request->post('ProcessingFee')),
                'amount' => floatval(ltrim($request->post('Price'), '$')),
                'phone' => $request->post('Phone'),
                'state' => $request->post('State'),
                'city' => $request->post('City'),
                'street' => $request->post('Street'),
                'zipcode' => $request->post('ZipCode'),
                'status' => $Status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        DB::table('leads')
            ->where('id', '=', $request->post('LeadId'))
            ->update([
                'subscribe' => $request->post('Subscribe')
            ]);
    }

    public function stripeOrderCouponApply(Request $request)
    {
        $Code = $request->post('CouponCode');
        $ActualPrice = floatval(ltrim($request->post('Price'), '$'));
        $RegistrationFee = $request->post('RegistrationFee');
        $SubPrice = $request->post('SubPrice');
        $Price = floatval($RegistrationFee) + floatval($SubPrice);
        $ProcessingFee = $request->post('ProcessingFee');
        $Tax = $request->post('Tax');
        $DiscountPrice = 0;

        $ChangeInAmount = 0;
        $NewPrice = 0;
        $Coupon = DB::table('coupons')
            ->where('coupon_code', '=', $Code)
            ->where('deleted_at', '=', null)
            ->whereRaw('CONVERT(coupon_limit, SIGNED) > CONVERT(coupon_usage, SIGNED)')
            ->get();

        if(sizeof($Coupon) > 0) {
            if($Coupon[0]->coupon_type == 'flat') {
                $DiscountPrice = floatval($Coupon[0]->coupon_rate);
                $NewPrice = $Price - $DiscountPrice;
            } else {
                $DiscountPrice = round(($Price * floatval($Coupon[0]->coupon_rate)) / 100, 2);
                $NewPrice = $Price - $DiscountPrice;
            }
            /*Add Tax To New Price*/
            $NewTax = round(($NewPrice * floatval($Tax))/100, 2);
            $NewPrice += $NewTax;
            /*Add Processing Fee To New Price*/
            $NewProcessingFee = round(($NewPrice * floatval($ProcessingFee))/100, 2);
            $NewPrice += $NewProcessingFee;
            /*Change in Price*/
            $ChangeInAmount = $ActualPrice - $NewPrice;

            /*Update Stripe Payment Intent*/
            $stripe = new StripeClient(Config::get('services.stripe.secret'));
            $ClientSecret = "";
            try {
                Stripe::setApiKey(Config::get('services.stripe.secret'));
                $PaymentIntent = $stripe->paymentIntents->update($request->post('PaymentIntentId'), [
                    'amount' => $NewPrice * 100,
                    'currency' => 'usd',
                ]);
                $ClientSecret = $PaymentIntent->client_secret;
            } catch (\Exception $exception) {
                return response(['status' => false, 'message' => 'Stripe Error!']);
            }
            return response(['status' => true, 'message' => 'Discount code applied!', 'changeInAmount' => $ChangeInAmount, 'ActualPrice' => $ActualPrice, 'NewPrice' => $NewPrice, 'DiscountPrice' => $DiscountPrice, 'NewTax' => $NewTax, 'NewProcessingFee' => $NewProcessingFee, 'ClientSecret' => $ClientSecret, 'CouponCodeId' => $Coupon[0]->id]);
        } else {
            return response(['status' => false, 'message' => 'Discount code invalid!']);
        }
    }

    public function stripeOrderFinish()
    {
        $PaymentIntent = isset($_GET['payment_intent'])? $_GET['payment_intent'] : '';
        $ClientSecret = isset($_GET['payment_intent_client_secret'])? $_GET['payment_intent_client_secret'] : '';
        $RedirectionStatus = isset($_GET['redirect_status'])? $_GET['redirect_status'] : '';
        $ParentGender = null;
        if ($RedirectionStatus == 'succeeded') {
            /*Payment Successful*/
            DB::beginTransaction();
            $CheckoutData = DB::table('orders')
                ->where('payment_intent_id', '=', $PaymentIntent)
                ->where('client_secret_id', '=', $ClientSecret)
                ->where('email', '<>', null)
                ->where('password', '<>', null)
                ->get();
            if(sizeof($CheckoutData) > 0) {
                /*Success*/
                $Lead = DB::table('leads')
                    ->where('id', '=', $CheckoutData[0]->lead_id)
                    ->get();
                $LeadDetails = DB::table('lead_details')
                    ->where('lead_id', '=', $CheckoutData[0]->lead_id)
                    ->get();

                /*Parent Registration*/
                if ($LeadDetails[0]->playerRelationship == "Father") {
                    $ParentGender = "Male";
                } elseif ($LeadDetails[0]->playerRelationship == "Mother") {
                    $ParentGender = "Female";
                }
                $UserId = substr($Lead[0]->parentFirstName, 0, 1) . substr($Lead[0]->parentLastName, 0, 1) . mt_rand(10000, 99999);
                $Affected1 = User::create([
                    'userId' => $UserId,
                    /*'parent_id' => Auth::id(),*/
                    'email' => $CheckoutData[0]->email,
                    'password' => bcrypt($CheckoutData[0]->password),
                    'role_id' => 5,
                    'status' => 1,
                    'default_pass_status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                $NewUserId = $Affected1->id;
                $Affected2 = UserDetails::create([
                    'user_id' => $NewUserId,
                    'firstName' => $Lead[0]->parentFirstName,
                    'lastName' => $Lead[0]->parentLastName,
                    'dob' => $Lead[0]->parentDOB,
                    'gender' => $ParentGender,
                    'street' => $Lead[0]->street,
                    'city' => $Lead[0]->city,
                    'state' => $Lead[0]->state,
                    'zipcode' => $Lead[0]->zipcode,
                    'phone1' => $Lead[0]->parentPhone,
                    'phone2' => $Lead[0]->parentPhone2,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                /*User Power Entry*/
                SiteHelper::InsertUserPower($NewUserId, 0, 0, 0);

                /*Training Room Entries*/
                SiteHelper::InsertTrainingRoom(5, $NewUserId);

                /*Player Registration*/
                $PUserId = substr($LeadDetails[0]->playerFirstName, 0, 1) . substr($LeadDetails[0]->playerLastName, 0, 1) . mt_rand(10000, 99999);
                $PlayerPassword = "";
                $UserBirthdayMonth = Carbon::parse($LeadDetails[0]->playerDOB)->format('M');
                $UserBirthdayYear = Carbon::parse($LeadDetails[0]->playerDOB)->format('Y');
                $PlayerPassword .= $UserBirthdayMonth . "!" . $UserBirthdayYear;
                $Affected3 = User::create([
                    'userId' => $PUserId,
                    'parent_id' => $NewUserId,
                    'email' => $LeadDetails[0]->playerEmail,
                    'password' => bcrypt($PlayerPassword),
                    'role_id' => 6,
                    'status' => 1,
                    'default_pass_status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                $PNewUserId = $Affected3->id;
                $Affected4 = UserDetails::create([
                    'user_id' => $PNewUserId,
                    'parent_id' => $NewUserId,
                    'firstName' => $LeadDetails[0]->playerFirstName,
                    'lastName' => $LeadDetails[0]->playerLastName,
                    'dob' => $LeadDetails[0]->playerDOB,
                    'gender' => $LeadDetails[0]->playerGender,
                    'athletesParent' => $NewUserId,
                    'athletesRelationship' => $LeadDetails[0]->playerRelationship,
                    'street' => $Lead[0]->street,
                    'city' => $Lead[0]->city,
                    'state' => $Lead[0]->state,
                    'zipcode' => $Lead[0]->zipcode,
                    'phone1' => $Lead[0]->parentPhone,
                    'phone2' => $Lead[0]->parentPhone2,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                /*User Power Entry*/
                SiteHelper::InsertUserPower($PNewUserId, 0, 0, 0);

                /*Training Room Entries*/
                SiteHelper::InsertTrainingRoom(6, $PNewUserId);

                /*Change Order Status*/
                DB::table('orders')
                    ->where('id', '=', $CheckoutData[0]->id)
                    ->update([
                        'user_id' => $NewUserId,
                        'player_id' => $PNewUserId,
                        'email' => null,
                        'password' => null,
                        'status' => 1
                    ]);

                /*Order Invoices*/
                OrderInvoices::create([
                    'order_id' => $CheckoutData[0]->id,
                    'invoice_id' => $this->generateRandomString(8),
                    'invoice_date' => Carbon::now(),
                    /*'invoice_expiry' => Carbon::now()->addMonths(1),*/
                    'invoice_expiry' => Carbon::now()->endOfMonth(),
                    'tax' => $CheckoutData[0]->tax,
                    'processing' => $CheckoutData[0]->processing,
                    'amount' => $CheckoutData[0]->sub_fee,
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                /*Change Lead Status*/
                DB::table('leads')
                    ->where('id', '=', $Lead[0]->id)
                    ->update([
                        'lead_status' => 8
                    ]);

                Transaction::create([
                    'type' => 2,
                    'bill_to' => $NewUserId,
                    'order_id' => $CheckoutData[0]->id,
                    'total_amount' => $CheckoutData[0]->amount,
                    'amount_paid' => $CheckoutData[0]->amount,
                    'status' => 2,
                    'paid_date' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                LeadConversions::create([
                    'lead_id' => $Lead[0]->id,
                    'order_id' => $CheckoutData[0]->id,
                    'parent_id' => $NewUserId,
                    'conversion_type' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                /*Update Coupon Code Count if Used*/
                if($CheckoutData[0]->coupon_code_id != '') {
                    DB::table('coupons')
                        ->where('id', '=', $CheckoutData[0]->coupon_code_id)
                        ->update([
                            'coupon_usage' => (Coupons::find($CheckoutData[0]->coupon_code_id)->coupon_usage) + 1,
                            'updated_at' => Carbon::now()
                        ]);
                }

                /*Create Stripe Subscription*/
//                if($CheckoutData[0]->package_type == 'monthly') {
//                    $ProductId = 'prod_LhN7TXHgq8Q6r3'; /*Standard*/
//                    $Response = $this->createSubscription($CheckoutData[0]->sub_fee, 'month', 1, $ProductId, $CheckoutData[0]->stripe_customer_id, 12);
//                }

                DB::commit();
                /*login*/
                $userData = array(
                    'email' => $CheckoutData[0]->email,
                    'password' => $CheckoutData[0]->password
                );
                if(Auth::attempt($userData)) {
                    Session::put('user_role', 5);
                    return redirect('login');
                } else {
                    return redirect()->route('createLeadRoute')->with('payment-success', true);
                }
            } else {
                /*Invalid Response*/
                return redirect('login')->with('payment-error', true);
            }
        } else {
            /*Payment Unsuccessful*/
            return redirect()->route('createLeadRoute')->with('payment-error', true);
        }
    }

    public function fetchPlayerPackage(Request $request)
    {
        $PlayerDob = Carbon::parse($request->post('PlayerDOB'));
        $PlayerAge = $PlayerDob->age;
        $Category = DB::table('categories')
            ->where('start_age', '<=', $PlayerAge)
            ->where('end_age', '>=', $PlayerAge)
            ->get();
        if(sizeof($Category) == 0) {
            return response(['status' => false, 'message' => 'Select valid Player DOB']);
        } else {
            $Package = DB::table('packages')
                ->join('package_fee_structures', 'packages.id', '=', 'package_fee_structures.package')
                ->where('deleted_at', '=', null)
                ->where('level', '=', 4)
                ->where('packages.start_date', '<=', Carbon::now())
                ->where('packages.end_date', '>=', Carbon::now())
                ->whereRaw('FIND_IN_SET(?, category)', array($Category[0]->id))
                ->whereRaw('packages.limit > packages.package_usage')
                ->get();
            if(sizeof($Package) == 0) {
                return response(['status' => true, 'package_status' => false, 'message' => 'No Package Found!', 'category' => $Category]);
            } else {
                return response(['status' => true, 'package_status' => true, 'message' => 'success', 'category' => $Category, 'package' => $Package]);
            }
        }
    }

    public function CheckUniqueEmail(Request $request)
    {
        $Email = $request['Email'];
        $User = DB::table('users')
            ->where('email', '=', $Email)
            ->get();
        if (sizeof($User) > 0) {
            return response(['status' => false, 'message' => 'Email already exists']);
        } else {
            return response(['status' => true, 'message' => 'Success']);
        }
    }

    public function ResetUserDocumentStatus() {
        DB::table('users')
            ->where('id', '=', Auth::id())
            ->update([
                'player_document_status' => 0,
                'updated_at' => Carbon::now()
            ]);
    }

    public function createSubscription($Price, $Interval, $IntervalCount, $Product, $Customer, $Iterations)
    {
        try {
            Stripe::setApiKey(Config::get('services.stripe.secret'));
            $price = Price::create([
                'unit_amount' => floatval($Price) * 100,
                'currency' => 'usd',
                'recurring' => [
                    'interval' => $Interval,
                    'interval_count' => $IntervalCount
                ],
                'product' => $Product
            ]);
            $PriceId = $price->id;

            $subscriptionSchedule = SubscriptionSchedule::create([
                'customer' => $Customer,
                'start_date' => Carbon::now()->addMonths(1)->timestamp,
                'end_behavior' => 'release',
                'phases' => [
                    [
                        'items' => [
                            [
                                'price' => $PriceId,
                                'quantity' => 1,
                            ],
                        ],
                        'iterations' => $Iterations,
                    ]
                ]
            ]);
            $ScheduleSubscriptionId = $subscriptionSchedule->id;
            $SubscriptionId = $subscriptionSchedule->subscription;

            return array('status' => true, 'schedule_subscription_id' => $ScheduleSubscriptionId, 'subscription_id' => $SubscriptionId);
        } catch (\Exception $exception) {
            return array('status' => false, 'message' => $exception->getMessage());
        }
    }

    public function stripeProductCreate()
    {
        $ProductTitle = 'Standard';
        try {
            Stripe::setApiKey(Config::get('services.stripe.secret'));
            $product = Product::create([
                'name' => $ProductTitle
            ]);
            $ProductId = $product->id;
            StripeProducts::create([
                'product_id' => $ProductId,
                'product_title' => $ProductTitle,
                'product_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return response(['status' => true, 'product_id' => $ProductId, 'product_title' => $ProductTitle]);
        } catch (\Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function stripePriceCreate()
    {
        $Product = 'prod_LhN7TXHgq8Q6r3';
        try {
            Stripe::setApiKey(Config::get('services.stripe.secret'));
            $price = Price::create([
                'unit_amount' => 8 * 100,
                'currency' => 'usd',
                'recurring' => [
                    'interval' => 'month',
                    'interval_count' => 1
                ],
                'product' => $Product
            ]);
            $PriceId = $price->id;

            return response(['status' => true, 'price_id' => $PriceId, 'recurring' => $price->recurring, 'type' => $price->type]);
        } catch (\Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function stripeSubscriptionCreate()
    {
        try {
            Stripe::setApiKey(Config::get('services.stripe.secret'));
            $subscriptionSchedule = SubscriptionSchedule::create([
                'customer' => 'cus_Lgfeb7eV3FU9gL',
                'start_date' => Carbon::now()->addMonths(1)->timestamp,
                'end_behavior' => 'release',
                'phases' => [
                    [
                        'items' => [
                            [
                                'price' => 'price_1KzytaFSDmjWzDJMX35voOmE',
                                'quantity' => 1,
                            ],
                        ],
                        'iterations' => 12,
                    ]
                ]
            ]);
            $ScheduleSubscriptionId = $subscriptionSchedule->id;
            $SubscriptionId = $subscriptionSchedule->subscription;
            return response(['status' => true, 'schedule_subscription_id' => $ScheduleSubscriptionId, 'subscription_id' => $SubscriptionId]);
        } catch (\Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function GetStripeCustomerPaymentMethods($CustomerId)
    {
        try {
            $stripe = new StripeClient(Config::get('services.stripe.secret'));
            $CustomerPaymentMethods = $stripe->customers->allPaymentMethods($CustomerId, ['type' => 'card']);
            if(isset($CustomerPaymentMethods->data)) {
                if(isset($CustomerPaymentMethods->data[0])) {
                    return $CustomerPaymentMethods->data[0]->id;
                    /*echo '<pre>';
                    echo print_r($CustomerPaymentMethods->data[0]);
                    echo '</pre>';
                    exit();*/
                } else {
                    return '';
                }
            } else {
                return '';
            }
        } catch (\Exception $exception) {
            /*return response(['status' => false, 'message' => $exception->getMessage()]);*/
            return '';
        }
    }

    public function StripeChargeASavedCard()
    {
        try {
            $CustomerId = 'cus_LiYj59pQPhAaWj';
            /*$PaymentMethodId = "pm_1L17csFSDmjWzDJMHU5atRFa";*/
            $PaymentMethodId = $this->GetStripeCustomerPaymentMethods($CustomerId);
            /*$SetupIntentId = "seti_1L19ALFSDmjWzDJMFw93Amus";*/
            Stripe::setApiKey('sk_test_4eC39HqLyjWDarjtT1zdp7dc');
            $stripe = new StripeClient(Config::get('services.stripe.secret'));
            /*$PaymentIntent = PaymentIntent::create([
                'payment_method_types' => ['card'],
                'amount' => 1099,
                'currency' => 'usd',
                'customer' => $CustomerId,
                'payment_method' => $PaymentMethodId,
            ]);
            $stripe->paymentIntents->capture($PaymentIntent->id, []);*/

            /*$SetupIntent = $stripe->setupIntents->create([
                'payment_method_types' => ['card'],
                'customer' => $CustomerId,
                'payment_method' => $PaymentMethodId
            ]);
            $stripe->setupIntents->confirm($SetupIntent->id, ['payment_method' => $PaymentMethodId]);
            echo $SetupIntent->id;
            echo '<br><br>';
            echo $stripe->setupIntents->retrieve($SetupIntent->id);*/

            $PaymentIntent = $stripe->paymentIntents->create([
                'amount' => 12 * 100,
                'currency' => 'usd',
                'customer' => $CustomerId,
            ]);
            $PaymentStatus = $stripe->paymentIntents->confirm($PaymentIntent->id, ['payment_method' => $PaymentMethodId]);
            if($PaymentStatus->status == 'succeeded') {
                /*Payment Successful*/
                return response(['status' => true, 'message' => "Payment Successful"]);
            } else {
                /*Payment unsuccessful*/
                return response(['status' => false, 'message' => $PaymentStatus->cancellation_reason]);
            }
        } catch (\Exception $exception) {
            /*Payment unsuccessful*/
            return response(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function ManageOrderInvoiceCronJob()
    {
        Stripe::setApiKey('sk_test_4eC39HqLyjWDarjtT1zdp7dc');
        $stripe = new StripeClient(Config::get('services.stripe.secret'));

        $this->ManageAccountReactivateCronJob();

        DB::beginTransaction();
        /*Update Individual Invoice Expiry Status*/
        $Affected = DB::table('order_invoices')
            ->whereRaw('CURDATE() > order_invoices.invoice_expiry')
            ->update([
                'status' => 2
            ]);

        /*Check for create new invoice*/
        $Orders = DB::table('orders')
            ->whereRaw('total_invoices <> created_invoices')
            ->whereIn('orders.status', array(1, 3))
            ->get();
        /*Get Late Payment Fee Days*/
        $MagicNumbers = DB::table('magic_numbers')
            ->where('id', '=', 1)
            ->get();
        $LatePaymentDays = intval($MagicNumbers[0]->late_fee);
        $LatePaymentDate = Carbon::now()->startOfMonth()->addDays($LatePaymentDays);
        /*echo $LatePaymentDate;*/
        foreach ($Orders as $index => $order) {
            /*Late Payment Fee for Package*/
            $PackageFeeStructure = DB::table('package_fee_structures')
                ->where('package', '=', $order->package_id)
                ->where('fee_Type', '=', $order->package_type == 'semi'? 'semi-annual' : $order->package_type)
                ->get();
            $HoldingFee = 0;
            $LatePaymentFee = 0;
            if(sizeof($PackageFeeStructure) > 0) {
                $HoldingFee = $PackageFeeStructure[0]->holding_fee;
                $LatePaymentFee = $PackageFeeStructure[0]->late_payment_fee;
            } else {
                continue;
            }
            /*Late Payment Fee for Package*/

            $OrderInvoices = DB::table('order_invoices')
                ->where('order_id', '=', $order->id)
                ->where('status', '=', 1)
                ->get();
            /*Check for In Progress Invoice*/
            if(sizeof($OrderInvoices) == 0) {
                /*No In Progress Invoice*/
                /*Create New Invoice Here*/
                /*Auto Payment Here*/
                $Amount = floatval($order->sub_fee);
                /*Coupon Amount Calculation*/
                $CouponCodeAmount = 0;
                if($order->coupon_code_id != null) {
                    $Coupon = DB::table('coupons')
                        ->where('id', '=', $order->coupon_code_id)
                        ->where('deleted_at', '=', null)
                        ->get();
                    if(sizeof($Coupon) != 0) {
                        if($Coupon[0]->coupon_apply == 'everyMonth') {
                            if($Coupon[0]->coupon_type == 'flat') {
                                $CouponCodeAmount = floatval($Coupon[0]->coupon_rate);
                            } else {
                                $CouponCodeAmount = round(($Amount * floatval($Coupon[0]->coupon_rate)) / 100, 2);
                            }
                        }
                    }
                }
                $Amount -= $CouponCodeAmount;
                $TransactionComments = "Subscription Fee";

                /*Account Holding Check*/
                if($order->status == 3) {
                    /*If yes, use holding fee as amount instead of regular fee*/
                    $TransactionComments = "Holding Fee";
                    $Amount = $HoldingFee;
                }

                /*Late Payment Checker*/
                if(Carbon::now() >= $LatePaymentDate) {
                    $Amount += floatval($LatePaymentFee);
                    if($order->status == 3) {
                        $TransactionComments = "Holding and late Payment Fee";
                    } else {
                        $TransactionComments = "Subscription and late Payment Fee";
                    }
                }

                /*Tax Inclusion*/
                $Amount = round($Amount + ($Amount * floatval($order->tax)) / 100, 2);

                /*Processing Fee Inclusion*/
                $Amount = round($Amount + ($Amount * floatval($order->processing)) / 100, 2);
                try {
                    $CustomerId = $order->stripe_customer_id;
                    $PaymentMethodId = $this->GetStripeCustomerPaymentMethods($CustomerId);
                    $PaymentIntent = $stripe->paymentIntents->create([
                        'amount' => $Amount * 100,
                        'currency' => 'usd',
                        'customer' => $CustomerId
                    ]);
                    $PaymentStatus = $stripe->paymentIntents->confirm($PaymentIntent->id, ['payment_method' => $PaymentMethodId]);
                    if($PaymentStatus->status == 'succeeded') {
                        /*Payment Successful*/
                        OrderInvoices::create([
                            'order_id' => $order->id,
                            'invoice_id' => $this->generateRandomString(8),
                            'invoice_date' => Carbon::now(),
                            'invoice_expiry' => Carbon::now()->endOfMonth(),
                            'tax' => $order->tax,
                            'processing' => $order->processing,
                            'amount' => $Amount,
                            'status' => 1,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                        /*Update Created Invoices count in orders*/
                        DB::table('orders')
                            ->where('id', '=', $order->id)
                            ->update([
                                'created_invoices' => $order->created_invoices + 1
                            ]);
                        /*Transactions Table Entry*/
                        Transaction::create([
                            'type' => 2,
                            'bill_to' => $this->GetUserFromLeadConversion($order->id),
                            'order_id' => $order->id,
                            'total_amount' => $Amount,
                            'amount_paid' => $Amount,
                            'status' => 2,
                            'paid_date' => Carbon::now(),
                            'comments' => $TransactionComments,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                        /*return response(['status' => true, 'message' => "Payment Successful"]);*/
                    } else {
                        /*Payment unsuccessful*/
                        /*Transactions Table Entry*/
                        Transaction::create([
                            'type' => 2,
                            'bill_to' => $this->GetUserFromLeadConversion($order->id),
                            'order_id' => $order->id,
                            'total_amount' => $Amount,
                            'amount_paid' => $Amount,
                            'status' => 3,
                            'paid_date' => Carbon::now(),
                            'comments' => $TransactionComments,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                        /*return response(['status' => false, 'message' => $PaymentStatus->cancellation_reason]);*/
                    }
                } catch (\Exception $exception) {
                    /*Payment unsuccessful*/
                    /*Transactions Table Entry*/
                    Transaction::create([
                        'type' => 2,
                        'bill_to' => $this->GetUserFromLeadConversion($order->id),
                        'order_id' => $order->id,
                        'total_amount' => $Amount,
                        'amount_paid' => $Amount,
                        'status' => 3,
                        'paid_date' => Carbon::now(),
                        'comments' => $TransactionComments,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                    echo response(['status' => false, 'message' => $exception->getMessage()]);
                }
            }
        }
        DB::table('orders')
            ->whereRaw('total_invoices = created_invoices')
            ->update([
                'status' => 4,
                'updated_at' => Carbon::now()
            ]);
        DB::commit();

        $this->ManageAccountSuspendedCronJob();
        $this->ManageAccountCancelCronJob();
    }

    public function ManageAccountReactivateCronJob()
    {
        /*Get Holding Deadline Days*/
        $MagicNumbers = DB::table('magic_numbers')
            ->where('id', '=', 1)
            ->get();
        $HoldingDeadlineDays = intval($MagicNumbers[0]->holding_deadline);
        if($HoldingDeadlineDays == '') {
            $HoldingDeadlineDays = 0;
        }

        DB::beginTransaction();
        $Orders = DB::table('orders')
            ->whereRaw('total_invoices <> created_invoices')
            ->whereIn('orders.status', array(3))
            ->get();
        foreach ($Orders as $index => $order) {
            if($order->holding_date != '') {
                $HoldingDate = Carbon::parse($order->holding_date)->addDays($HoldingDeadlineDays + 1);
                if(Carbon::now() > $HoldingDate) {
                    /*Account Re Activate*/
                    DB::table('orders')
                        ->where('id', '=', $order->id)
                        ->update([
                            'status' => 1,
                            'holding_date' => null,
                            'updated_at' => Carbon::now()
                        ]);
                }
            }
        }
        DB::commit();
    }

    public function ManageAccountSuspendedCronJob()
    {
        /*Get Payment Deadline Days*/
        $MagicNumbers = DB::table('magic_numbers')
            ->where('id', '=', 1)
            ->get();
        $Days = intval($MagicNumbers[0]->payment_deadline);
        if($Days == '') {
            $Days = 0;
        }

        DB::beginTransaction();
        $Orders = DB::table('orders')
            ->whereIn('orders.status', array(1))
            ->get();
        foreach ($Orders as $index => $order) {
            /*Get Last Paid Transaction*/
            $LastTransaction = DB::table('transactions')
                ->where('order_id', '=', $order->id)
                ->where('status', '=', 2)
                ->orderBy('id', 'DESC')
                ->get();
            if(sizeof($LastTransaction) == 0) {
                continue;
            }
            $PaidDate = Carbon::parse($LastTransaction[0]->paid_date);
            $PaidDateNextMonthStartDate = $PaidDate->addMonths(1)->startOfMonth();
            $PaymentDeadlineDate = $PaidDateNextMonthStartDate->addDays($Days);
            /*echo $PaymentDeadlineDate;
            echo '<br><br>';
            continue;*/
            if(Carbon::now() > $PaymentDeadlineDate) {
                /*Account Suspend after payment deadline*/
                DB::table('orders')
                    ->where('id', '=', $order->id)
                    ->update([
                        'status' => 2,
                        'updated_at' => Carbon::now()
                    ]);
            }
        }
        DB::commit();
    }

    public function ManageAccountCancelCronJob()
    {
        /*Get Suspended Account Deadline Days*/
        $MagicNumbers = DB::table('magic_numbers')
            ->where('id', '=', 1)
            ->get();
        $Days = intval($MagicNumbers[0]->suspended_account);
        if($Days == '') {
            $Days = 0;
        }

        DB::beginTransaction();
        $Orders = DB::table('orders')
            ->whereIn('orders.status', array(2))
            ->get();
        foreach ($Orders as $index => $order) {
            $OrderStartDate = Carbon::parse($order->created_at);
            $OrderEndDate = null;
            if($order->package_type == 'monthly') {
                $OrderEndDate = $OrderStartDate->addMonths(11)->endOfMonth();
            } elseif($order->package_type == 'semi') {
                $OrderEndDate = $OrderStartDate->addMonths(5)->endOfMonth();
            }
            $SuspendedDate = Carbon::parse($order->suspended_date)->addDays($Days);
            if(Carbon::now() > $SuspendedDate) {
                /*Account Cancelled after Suspended Deadline*/
                if(Carbon::now() > $OrderEndDate) {
                    /*Termination on Time*/
                    DB::table('orders')
                        ->where('id', '=', $order->id)
                        ->update([
                            'status' => 5,
                            'holding_date' => null,
                            'suspended_reason' => null,
                            'suspended_date' => null,
                            'cancel_reason' => 'Membership cancelled after suspended deadline',
                            'cancel_date' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                } else {
                    /*Early Termination*/
                    // *** Charge Customer ***
                    // ***** Early Termination Fee for Package *****
                    $PackageFeeStructure = DB::table('package_fee_structures')
                        ->where('package', '=', $order->package_id)
                        ->where('fee_Type', '=', $order->package_type == 'semi'? 'semi-annual' : $order->package_type)
                        ->get();
                    $Amount = 0;
                    if(sizeof($PackageFeeStructure) > 0) {
                        $Amount = $PackageFeeStructure[0]->termination_fee;
                    } else {
                        continue;
                    }
                    $PaymentStatus = $this->StripeChargeCustomer($order->id, $order->stripe_customer_id, $Amount, 'Early termination fee after membership cancelled');
                    if($PaymentStatus) {
                        DB::table('orders')
                            ->where('id', '=', $order->id)
                            ->update([
                                'status' => 5,
                                'holding_date' => null,
                                'suspended_reason' => null,
                                'suspended_date' => null,
                                'cancel_reason' => 'Membership cancelled after suspended deadline',
                                'cancel_date' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]);
                    }
                }
            }
        }
        DB::commit();
    }

    function GetUserFromLeadConversion($OrderId)
    {
        $LeadConversion = DB::table('lead_conversions')
            ->where('order_id', '=', $OrderId)
            ->get();
        if(sizeof($LeadConversion) > 0) {
            return $LeadConversion[0]->parent_id;
        } else {
            /*Check for User Id in orders Table*/
            $Order = DB::table('orders')
                ->where('order_id', '=', $OrderId)
                ->get();
            try {
                if($Order[0]->user_id != '') {
                    return $Order[0]->user_id;
                } else {
                    return 0;
                }
            } catch (\Exception $exception) {
                return 0;
            }
        }
    }

    function StripeChargeCustomer($OrderId, $CustomerId, $Amount, $Comments)
    {
        Stripe::setApiKey('sk_test_4eC39HqLyjWDarjtT1zdp7dc');
        $stripe = new StripeClient(Config::get('services.stripe.secret'));
        try {
            $PaymentMethodId = $this->GetStripeCustomerPaymentMethods($CustomerId);
            $PaymentIntent = $stripe->paymentIntents->create([
                'amount' => $Amount * 100,
                'currency' => 'usd',
                'customer' => $CustomerId
            ]);
            $PaymentStatus = $stripe->paymentIntents->confirm($PaymentIntent->id, ['payment_method' => $PaymentMethodId]);
            if($PaymentStatus->status == 'succeeded') {
                /*Payment Successful*/
                /*Transactions Table Entry*/
                Transaction::create([
                    'type' => 2,
                    'bill_to' => $this->GetUserFromLeadConversion($OrderId),
                    'order_id' => $OrderId,
                    'total_amount' => $Amount,
                    'amount_paid' => $Amount,
                    'status' => 2,
                    'paid_date' => Carbon::now(),
                    'comments' => $Comments,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                return true;
            } else {
                /*Payment unsuccessful*/
                /*Transactions Table Entry*/
                Transaction::create([
                    'type' => 2,
                    'bill_to' => $this->GetUserFromLeadConversion($OrderId),
                    'order_id' => $OrderId,
                    'total_amount' => $Amount,
                    'amount_paid' => $Amount,
                    'status' => 3,
                    'paid_date' => Carbon::now(),
                    'comments' => 'Early Termination Fee',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                return false;
            }
        } catch (\Exception $exception) {
            /*Payment unsuccessful*/
            /*Transactions Table Entry*/
            Transaction::create([
                'type' => 2,
                'bill_to' => $this->GetUserFromLeadConversion($OrderId),
                'order_id' => $OrderId,
                'total_amount' => $Amount,
                'amount_paid' => $Amount,
                'status' => 3,
                'paid_date' => Carbon::now(),
                'comments' => 'Early Termination Fee',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            return false;
        }
    }

    /* ### Invoice Payment Link ### */
    public function AdminInvoiceEmail($InvoiceId) {
        $item_title = array();
        $item_price = array();
        $item_quantity = array();
        $SubTotal = 0;
        $DueDate = "";
        $ParentName = "";
        $ParentAddress = "";
        $InvoiceDetails = DB::table('invoices')
            ->where('id', $InvoiceId)
            ->get();

        $InvoiceItems = DB::table('invoice_items')
            ->where('invoice', $InvoiceId)
            ->get();

        foreach ($InvoiceItems as $key => $value) {
            array_push($item_title, $value->item);
            array_push($item_price, $value->price);
            array_push($item_quantity, $value->quantity);
            $TotalItemPrice = 0;
            $TotalItemPrice = $value->price * $value->quantity;
            $SubTotal = $SubTotal + $TotalItemPrice;
        }

        $Email = '';
        if ($InvoiceDetails[0]->bill_to == -1) {
            $ParentName = $InvoiceDetails[0]->fullName;
            $Email = $_ENV['MAIL_FROM_ADDRESS']; /*To be changed in future*/

            if ($InvoiceDetails[0]->street != "") {
                $ParentAddress .= $InvoiceDetails[0]->street . ",";
            }
            if ($InvoiceDetails[0]->city != "") {
                $ParentAddress .= " " . $InvoiceDetails[0]->city . ",";
            }
            if ($InvoiceDetails[0]->state != "") {
                $ParentAddress .= " " . $InvoiceDetails[0]->state . " ";
            }
            if ($InvoiceDetails[0]->zipcode != "") {
                $ParentAddress .= $InvoiceDetails[0]->zipcode;
            }
        } else {
            $ParentDetails = SiteHelper::GetUserDetails($InvoiceDetails[0]->bill_to);
            $Email = $ParentDetails[0]->email;

            if ($ParentDetails[0]->middleName != "") {
                $ParentName = $ParentDetails[0]->firstName . " " . $ParentDetails[0]->middleName . " " . $ParentDetails[0]->lastName;
            } else {
                $ParentName = $ParentDetails[0]->firstName . " " . $ParentDetails[0]->lastName;
            }

            if ($ParentDetails[0]->street != "") {
                $ParentAddress .= $ParentDetails[0]->street . ",";
            }
            if ($ParentDetails[0]->city != "") {
                $ParentAddress .= " " . $ParentDetails[0]->city . ",";
            }
            if ($ParentDetails[0]->state != "") {
                $ParentAddress .= " " . $ParentDetails[0]->state . " ";
            }
            if ($ParentDetails[0]->zipcode != "") {
                $ParentAddress .= $ParentDetails[0]->zipcode;
            }
        }

        if ($InvoiceDetails[0]->due_date != "") {
            $DueDate = Carbon::parse($InvoiceDetails[0]->due_date)->format('m-d-Y');
        }

        $data = array(
            'invoice_id' => $InvoiceId,
            'parent_name' => $ParentName,
            'parent_address' => $ParentAddress,
            'invoice_no' => $InvoiceDetails[0]->invoice_no,
            'invoice_date' => Carbon::parse($InvoiceDetails[0]->send_date)->format('m-d-Y'),
            'due_date' => $DueDate,
            'item_title' => json_encode($item_title),
            'item_price' => json_encode($item_price),
            'item_quantity' => json_encode($item_quantity),
            'subtotal' => $SubTotal,
            'discount_percentage' => $InvoiceDetails[0]->discount,
            'discount_price' => $InvoiceDetails[0]->discount_price,
            'processing_fee_percentage' => $InvoiceDetails[0]->processing_fee,
            'processing_fee_price' => $InvoiceDetails[0]->processing_fee_price,
            'tax_rate_percentage' => $InvoiceDetails[0]->tax_rate,
            'tax_rate_price' => $InvoiceDetails[0]->tax_rate_price,
            'total_bill' => $InvoiceDetails[0]->total_bill,
        );

        Mail::send('dashboard.billing.invoices.invoice-email', $data, function ($message) use ($Email) {
            $message->to($Email, 'MSA')->subject('New Invoice pending for payment');
            $message->from($_ENV['MAIL_FROM_ADDRESS'], 'MSA');
        });
    }

    public function InvoicePayment($InvoiceId)
    {
        $InvoiceId = base64_decode($InvoiceId);
        $SubTotal = 0;
        $DueDateStatus = false;
        $ParentName = "";
        $ParentPhone = "";
        $ParentEmail = "";
        $ParentState = " ";
        $ParentCity = " ";
        $ParentZipCode = " ";
        $ParentAddress = "";
        $InvoiceDetails = DB::table('invoices')
            ->where('id', $InvoiceId)
            ->get();

        if(sizeof($InvoiceDetails) == 0) {
            abort(404);
            exit();
        }

        if($InvoiceDetails[0]->invoice_status == 2) {
            /*Already Paid*/
            return redirect()->route('billing.invoices.payment-page.complete', array('success'))->with('payment-error', 'Invoice already paid!');
        }

        $InvoiceItems = DB::table('invoice_items')
            ->where('invoice', $InvoiceId)
            ->get();

        foreach ($InvoiceItems as $key => $value) {
            $TotalItemPrice = $value->price * $value->quantity;
            $SubTotal = $SubTotal + $TotalItemPrice;
        }

        if ($InvoiceDetails[0]->bill_to == -1) {
            $ParentName = $InvoiceDetails[0]->fullName;
            $ParentPhone = "1231231234"; /*TO be changed ib future*/
            $ParentEmail = $_ENV['MAIL_FROM_ADDRESS']; /*To be changed in future*/
            if ($InvoiceDetails[0]->street != "") {
                $ParentAddress .= $InvoiceDetails[0]->street . ",";
            }
            if ($InvoiceDetails[0]->city != "") {
                $ParentAddress .= " " . $InvoiceDetails[0]->city . ",";
                $ParentCity = $InvoiceDetails[0]->city;
            }
            if ($InvoiceDetails[0]->state != "") {
                $ParentAddress .= " " . $InvoiceDetails[0]->state . " ";
                $ParentState = $InvoiceDetails[0]->state;
            }
            if ($InvoiceDetails[0]->zipcode != "") {
                $ParentAddress .= $InvoiceDetails[0]->zipcode;
                $ParentZipCode = $InvoiceDetails[0]->zipcode;
            }
        } else {
            $ParentDetails = SiteHelper::GetUserDetails($InvoiceDetails[0]->bill_to);
            $ParentPhone = $ParentDetails[0]->phone1;
            $ParentEmail = $ParentDetails[0]->email;
            if ($ParentDetails[0]->middleName != "") {
                $ParentName = $ParentDetails[0]->firstName . " " . $ParentDetails[0]->middleName . " " . $ParentDetails[0]->lastName;
            } else {
                $ParentName = $ParentDetails[0]->firstName . " " . $ParentDetails[0]->lastName;
            }
            if ($ParentDetails[0]->street != "") {
                $ParentAddress .= $ParentDetails[0]->street . ",";
            }
            if ($ParentDetails[0]->city != "") {
                $ParentAddress .= " " . $ParentDetails[0]->city . ",";
                $ParentCity = $ParentDetails[0]->city;
            }
            if ($ParentDetails[0]->state != "") {
                $ParentAddress .= " " . $ParentDetails[0]->state . " ";
                $ParentState = $ParentDetails[0]->state;
            }
            if ($ParentDetails[0]->zipcode != "") {
                $ParentAddress .= $ParentDetails[0]->zipcode;
                $ParentZipCode = $ParentDetails[0]->zipcode;
            }
        }
        if ($InvoiceDetails[0]->due_date != "") {
            if(Carbon::now() > Carbon::parse($InvoiceDetails[0]->due_date)) {
                $DueDateStatus = true;
            }
        }

        return view('invoice-payment', compact('InvoiceId', 'DueDateStatus', 'InvoiceDetails', 'SubTotal', 'ParentName', 'ParentAddress', 'ParentPhone', 'ParentEmail', 'ParentState', 'ParentCity', 'ParentZipCode'));
    }

    public function InvoiceStripeSetup(Request $request)
    {
        $Price = round(floatval($request['Price']), 2);
        $ParentName = $request['Name'];
        $ParentPhone = $request['Phone'];

        try {
            Stripe::setApiKey(Config::get('services.stripe.secret'));
            $stripe = new StripeClient(Config::get('services.stripe.secret'));
            /*Search For Stripe Customers*/
            $Customer = null;
            if($ParentName != "" && $ParentPhone != "") {
                $Customer = Customer::search([
                    'query' => "name:\"" . $ParentName . "\" AND phone:\"" . $ParentPhone . "\""
                ]);
            }
            $StripeCustomerId = "";
            if(isset($Customer->data)) {
                if(sizeof($Customer->data) > 0) {
                    $StripeCustomerId = $Customer->data[0]->id;
                }
            }
            if($StripeCustomerId == "") {
                $StripeCustomerId = Customer::create([
                    'name' => $ParentName,
                    'phone' => $ParentPhone
                ])->id;
            }
            $PaymentIntent = $stripe->paymentIntents->create([
                'amount' => $Price * 100,
                'currency' => 'usd',
                'customer' => $StripeCustomerId
            ]);
            $PaymentIntentId = $PaymentIntent->id;
            $ClientSecret = $PaymentIntent->client_secret;

            return response(['status' => true, 'payment_intent' => $PaymentIntentId, 'client_secret' => $ClientSecret, 'customer_id' => $StripeCustomerId]);
        } catch (\Exception $exception) {
            return response(['status' => false, 'message' => 'Stripe Error!']);
        }
    }

    public function InvoiceStripeCreate(Request $request)
    {
        $InvoiceCheck = DB::table('invoices')
            ->where('id', '=', $request['InvoiceId'])
            ->update([
                'payment_intent_id' => $request->post('PaymentIntentId'),
                'client_secret_id' => $request->post('ClientSecret'),
                'stripe_customer_id' => $request->post('StripeCustomerId')
            ]);
    }

    public function InvoicePaymentProcess()
    {
        $PaymentIntent = isset($_GET['payment_intent'])? $_GET['payment_intent'] : '';
        $ClientSecret = isset($_GET['payment_intent_client_secret'])? $_GET['payment_intent_client_secret'] : '';
        $RedirectionStatus = isset($_GET['redirect_status'])? $_GET['redirect_status'] : '';
        $InvoicesPage = '';
        if(isset($_GET['true'])) {
            $InvoicesPage = 'true';
        }
        $ParentGender = null;
        if ($RedirectionStatus == 'succeeded') {
            /*Payment Successful*/
            DB::beginTransaction();
            $InvoiceData = DB::table('invoices')
                ->where('payment_intent_id', '=', $PaymentIntent)
                ->where('client_secret_id', '=', $ClientSecret)
                ->get();
            if(sizeof($InvoiceData) > 0) {
                /*Success*/
                /*Update Invoice Status*/
                DB::table('invoices')
                    ->where('id', '=', $InvoiceData[0]->id)
                    ->update([
                        'invoice_status' => 2,
                        'updated_at' => Carbon::now()
                    ]);

                Transaction::create([
                    'type' => 1,
                    'bill_to' => $InvoiceData[0]->bill_to,
                    'invoice_id' => $InvoiceData[0]->id,
                    'total_amount' => $InvoiceData[0]->total_bill,
                    'amount_paid' => $InvoiceData[0]->total_bill,
                    'status' => 2,
                    'comments' => 'Invoice Payment - ' . $InvoiceData[0]->invoice_no,
                    'paid_date' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                DB::commit();
                if($InvoicesPage != '') {
                    return redirect()->route('billing.invoices')->with('success', 'Payment has been processed and invoice has been paid.');
                } else {
                    return redirect()->route('billing.invoices.payment-page.complete', array('success'));
                }
            } else {
                /*Invalid Response*/
                Transaction::create([
                    'type' => 1,
                    'bill_to' => $InvoiceData[0]->bill_to,
                    'invoice_id' => $InvoiceData[0]->id,
                    'total_amount' => $InvoiceData[0]->total_bill,
                    'amount_paid' => $InvoiceData[0]->total_bill,
                    'status' => 3,
                    'comments' => 'Invoice Payment - ' . $InvoiceData[0]->invoice_no,
                    'paid_date' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                if($InvoicesPage != '') {
                    return redirect()->route('billing.invoices')->with('error', 'Payment cannot be processed.');
                } else {
                    return redirect()->route('billing.invoices.payment-page.complete', array('error'));
                }
            }
        } else {
            /*Payment Unsuccessful*/
            if($InvoicesPage != '') {
                return redirect()->route('billing.invoices')->with('error', 'Payment cannot be processed.');
            } else {
                return redirect()->route('billing.invoices.payment-page.complete', array('error'));
            }
        }
    }

    public function InvoicePaymentFinish($Status)
    {
        if($Status == '') {
            abort(404);
            exit();
        }
        return view('invoice-payment-complete', compact('Status'));
    }

    public function SendInvoiceCronJob()
    {
        $Invoices = DB::table('invoices')
            ->where('send_date', '=', date('Y-m-d'))
            ->where('invoice_status', '=', 1)
            ->where('due_type', '=', 'on a specific date')
            ->where('deleted_at', '=', null)
            ->get();
        /*echo '<pre>';
        echo print_r($Invoices);
        echo '</pre>';
        exit();*/
        foreach ($Invoices as $index => $invoice) {
            $this->AdminInvoiceEmail($invoice->id);
        }
    }
    /* ### Invoice Payment Link ### */
}
