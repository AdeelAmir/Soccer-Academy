<?php

namespace App\Http\Controllers;

use App\Helpers\SiteHelper;
use App\Models\Coupons;
use App\Models\OrderInvoices;
use App\Models\Orders;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MembershipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page = 'billing';
        $Role = Session::get("user_role");
        return view('dashboard.billing.subscriptions.index', compact('page', 'Role'));
    }

    function newMembership()
    {
        $page = "billing";
        $Role = Session::get("user_role");
        $ParentUserDetails = DB::table('user_details')
            ->where('user_id', '=', Auth::id())
            ->get();
        $States = DB::table('states')->get();
        $MagicNumbers = DB::table('magic_numbers')
            ->first();
        $Locations = DB::table('player_locations')
            ->where('status', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();
        return view('dashboard.billing.subscriptions.new.index', compact('page', 'Role', 'States', 'ParentUserDetails', 'MagicNumbers', 'Locations'));
    }

    function storeMembership(Request $request)
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
                    'player_data' => $request['PlayerData'],
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
                'player_data' => $request['PlayerData'],
                'payment_intent_id' => $request->post('PaymentIntentId'),
                'client_secret_id' => $request->post('ClientSecret'),
                'stripe_customer_id' => $request->post('StripeCustomerId'),
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
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }

    function finishMembership()
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
                ->get();
            if(sizeof($CheckoutData) > 0) {
                /*Success*/
                /*Player Registration*/
                $ParentUserDetails = DB::table('user_details')
                    ->where('user_id', '=', Auth::id())
                    ->get();
                $PlayerData = json_decode($CheckoutData[0]->player_data);
                $PUserId = substr($PlayerData->FirstName, 0, 1) . substr($PlayerData->LastName, 0, 1) . mt_rand(10000, 99999);
                $PlayerPassword = "";
                $UserBirthdayMonth = Carbon::parse($PlayerData->Dob)->format('M');
                $UserBirthdayYear = Carbon::parse($PlayerData->Dob)->format('Y');
                $PlayerPassword .= $UserBirthdayMonth . "!" . $UserBirthdayYear;
                $Affected3 = User::create([
                    'userId' => $PUserId,
                    'parent_id' => Auth::id(),
                    'email' => $PlayerData->Email,
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
                    'parent_id' => Auth::id(),
                    'firstName' => $PlayerData->FirstName,
                    'lastName' => $PlayerData->LastName,
                    'dob' => $PlayerData->Dob,
                    'gender' => $PlayerData->Gender,
                    'athletesParent' => Auth::id(),
                    'athletesRelationship' => $PlayerData->Relationship,
                    'street' => $ParentUserDetails[0]->street,
                    'city' => $ParentUserDetails[0]->city,
                    'state' => $ParentUserDetails[0]->state,
                    'zipcode' => $ParentUserDetails[0]->zipcode,
                    'phone1' => $ParentUserDetails[0]->phone1,
                    'phone2' => $ParentUserDetails[0]->phone2,
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
                        'user_id' => Auth::id(),
                        'player_id' => $PNewUserId,
                        'player_data' => null,
                        'status' => 1
                    ]);

                /*Order Invoices*/
                $HomeController = new HomeController();
                OrderInvoices::create([
                    'order_id' => $CheckoutData[0]->id,
                    'invoice_id' => $HomeController->generateRandomString(8),
                    'invoice_date' => Carbon::now(),
                    'invoice_expiry' => Carbon::now()->endOfMonth(),
                    'tax' => $CheckoutData[0]->tax,
                    'processing' => $CheckoutData[0]->processing,
                    'amount' => $CheckoutData[0]->sub_fee,
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                Transaction::create([
                    'type' => 2,
                    'bill_to' => Auth::id(),
                    'order_id' => $CheckoutData[0]->id,
                    'total_amount' => $CheckoutData[0]->amount,
                    'amount_paid' => $CheckoutData[0]->amount,
                    'status' => 2,
                    'paid_date' => Carbon::now(),
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

                DB::commit();
                return redirect()->route('dashboard.memberships')->with('success', 'Registration successful!');
            } else {
                /*Invalid Response*/
                return redirect()->route('dashboard.memberships')->with('error', 'Payment Error!');
            }
        } else {
            /*Payment Unsuccessful*/
            return redirect()->route('dashboard')->with('error', 'Payment unsuccessful!');
        }
    }
}
