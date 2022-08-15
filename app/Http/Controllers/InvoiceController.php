<?php

namespace App\Http\Controllers;

use App\Helpers\SiteHelper;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use PDF;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function AdminAllInvoices()
    {
        $page = "billing";
        $Role = Session::get('user_role');
        return view('dashboard.billing.invoices.index', compact('page', 'Role'));
    }

    public function LoadAdminAllInvoices(Request $request)
    {
        $Role = Session::get('user_role');
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];

        $columnIndex = $request->post('order')[0]['column']; // Column index
        $columnName = $request->post('columns')[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->post('order')[0]['dir']; // asc or desc

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;

        if ($Role == 1 || $Role == 2 || $Role == 3) {
            if ($searchTerm == '') {
                $fetch_data = DB::table('invoices')
                    ->leftJoin('user_details', 'invoices.bill_to', '=', 'user_details.user_id')
                    ->where('invoices.deleted_at', '=', null)
                    ->select('invoices.*', 'user_details.firstName as first_name', 'user_details.middleName as middle_name', 'user_details.lastName as last_name')
                    ->orderBy('invoices.id', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('invoices')
                    ->leftJoin('user_details', 'invoices.bill_to', '=', 'user_details.user_id')
                    ->where('invoices.deleted_at', '=', null)
                    ->select('invoices.*', 'user_details.firstName as first_name', 'user_details.middleName as middle_name', 'user_details.lastName as last_name')
                    ->orderBy('invoices.id', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('invoices')
                    ->leftJoin('user_details', 'invoices.bill_to', '=', 'user_details.user_id')
                    ->where('invoices.deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('invoices.invoice_no', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('invoices.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('invoices.due_type', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('invoices.send_date', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('invoices.total_bill', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('invoices.*', 'user_details.firstName as first_name', 'user_details.middleName as middle_name', 'user_details.lastName as last_name')
                    ->orderBy('invoices.id', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('invoices')
                    ->leftJoin('user_details', 'invoices.bill_to', '=', 'user_details.user_id')
                    ->where('invoices.deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('invoices.invoice_no', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('invoices.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.middleName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('invoices.due_type', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('invoices.send_date', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('invoices.total_bill', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('invoices.*', 'user_details.firstName as first_name', 'user_details.middleName as middle_name', 'user_details.lastName as last_name')
                    ->orderBy('invoices.id', 'desc')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $Status = "";
            $ParentName = "";
            $ParentEmail = " ";
            $ParentPhone = " ";
            $ParentState = " ";
            $ParentCity = " ";
            $ParentZipCode = " ";
            if ($item->bill_to == -1) {
                $ParentName = $item->fullName;
                $ParentEmail = $_ENV['MAIL_FROM_ADDRESS']; /*To be changed in future*/
                $ParentState = $item->state != ""? $item->state : " ";
                $ParentCity = $item->city != ""? $item->city : " ";
                $ParentZipCode = $item->zipcode != ""? $item->zipcode : " ";
            } else {
                $ParentDetails = SiteHelper::GetUserDetails($item->bill_to);
                $ParentName = $ParentDetails[0]->firstName . " " . $ParentDetails[0]->lastName;
                $ParentPhone = $ParentDetails[0]->phone1;
                $ParentEmail = $ParentDetails[0]->email;
                $ParentState = $ParentDetails[0]->state != ""? $ParentDetails[0]->state : " ";
                $ParentCity = $ParentDetails[0]->city != ""? $ParentDetails[0]->city : " ";
                $ParentZipCode = $ParentDetails[0]->zipcode != ""? $ParentDetails[0]->zipcode : " ";
            }

            if ($item->invoice_status == 1) {
                $Status = '<span class="badge badge-pill badge-warning">Pending</span>';
            } elseif ($item->invoice_status == 2) {
                $Status = '<span class="badge badge-pill badge-success">Paid</span>';
            } elseif ($item->invoice_status == 3) {
                $Status = '<span class="badge badge-pill badge-danger">Failed</span>';
            }

            $sub_array = array();
            $sub_array['id'] = $SrNo;
            $sub_array['invoice_no'] = $item->invoice_no;
            if (strlen($item->title) > 15) {
                $sub_array['title'] = substr($item->title, 0, 15) . '...';
            } else {
                $sub_array['title'] = $item->title;
            }
            $sub_array['bill_to'] = $ParentName;
            $sub_array['due'] = ucfirst(strtolower($item->due_type));
            $sub_array['send_date'] = Carbon::parse($item->send_date)->format('m/d/Y');
            $sub_array['total_bill'] = "$" . number_format((float)$item->total_bill, 2, '.', '');
            $sub_array['pdf'] = "<a href='" . route('billing.invoices.pdf', [$item->id]) . "' target='_blank'><i class='fa fa-download' aria-hidden='true'></i></a>";
            $sub_array['status'] = $Status;
            $Action = "";
            if ($Role == 1) {
                $Action = "<span>";
                if($item->invoice_status == 1) {
                    $Action .= '<button class="btn btn-primary btn-sm" id="payNow_' . $item->id . '" data-id="' . $item->id . '" data-amount="' . round($item->total_bill, 2) . '" data-parent-name="' . $ParentName . '" data-parent-phone="' . $ParentPhone . '" data-parent-email="' . $ParentEmail . '" data-parent-state="' . $ParentState . '" data-parent-city="' . $ParentCity . '" data-parent-zip="' . $ParentZipCode . '" data-toggle="tooltip" title="Pay Now" onclick="PayNow(this.id);"><i class="fas fa-credit-card"></i></button>';
                }
                $Action .= '<button class="btn btn-primary btn-sm" id="edit_' . $item->id . '" onclick="EditInvoice(this.id);" data-toggle="tooltip" title="View Invoice"><i class="fas fa-eye"></i></button>';
                $Action .= '<button class="btn btn-danger btn-sm" id="delete_' . $item->id . '" onclick="DeleteInvoice(this.id);" data-toggle="tooltip" title="Delete Invoice"><i class="fas fa-trash"></i></button>';
                $Action .= "<span>";
            } elseif ($Role == 2 || $Role == 3) {
                $Action = "<span>";
                if($item->invoice_status == 1) {
                    $Action .= '<button class="btn btn-primary btn-sm" id="payNow_' . $item->id . '" data-id="' . $item->id . '" data-amount="' . round($item->total_bill, 2) . '" data-parent-name="' . $ParentName . '" data-parent-phone="' . $ParentPhone . '" data-parent-email="' . $ParentEmail . '" data-parent-state="' . $ParentState . '" data-parent-city="' . $ParentCity . '" data-parent-zip="' . $ParentZipCode . '" data-toggle="tooltip" title="Pay Now" onclick="PayNow(this.id);"><i class="fas fa-credit-card"></i></button>';
                }
                $Action .= '<button class="btn btn-primary btn-sm" id="edit_' . $item->id . '" onclick="EditInvoice(this.id);" data-toggle="tooltip" title="View Invoice"><i class="fas fa-eye"></i></button>';
                $Action .= "<span>";
            }
            $sub_array['action'] = $Action;
            $SrNo++;
            $data[] = $sub_array;
        }

        $json_data = array(
            "draw" => intval($request->post('draw')),
            "iTotalRecords" => $recordsTotal,
            "iTotalDisplayRecords" => $recordsFiltered,
            "aaData" => $data
        );

        echo json_encode($json_data);
    }

    public function AdminAddNewInvoice()
    {
        $page = "billing";
        $Role = Session::get('user_role');
        $invoice_no = "00001";
        $Parents = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 5)
            ->where('users.status', '=', 1)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();
        $Players = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 6)
            ->where('users.status', '=', 1)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();
        $States = DB::table('states')
            ->get();
        $Configuration = DB::table('magic_numbers')->get();
        $Invoices = DB::table('invoices')->orderBy("id", "DESC")->limit(1)->get();
        if (count($Invoices) > 0) {
            $invoice_no = $Invoices[0]->invoice_no;
            $invoice_no = str_pad($invoice_no + 1, 5, 0, STR_PAD_LEFT);
        }
        return view('dashboard.billing.invoices.add', compact('page', 'Role', 'Parents', 'Players', 'Configuration', 'invoice_no', 'States'));
    }

    public function AdminInvoiceStore(Request $request)
    {
        $InvoicePDFName = "MSA_invoice_" . $request->post('invoice_no') . ".pdf";
        $TotalBill = 0;
        $SubTotal = 0;
        $SubTotalBeforeDiscount = 0;
        $SendDate = date('Y-m-d');
        $DueDate = null;
        $item_title = array();
        $item_price = array();
        $item_quantity = array();

        if ($request->post('due_type') == "pay now") {
            $DueDate = date('Y-m-d');
        } elseif ($request->post('due_type') == "on a specific date") {
            $SendDate = Carbon::parse($request->post('sendDate'))->format('Y-m-d');
            $DueDate = Carbon::parse($request->post('dueDate'))->format('Y-m-d');
        }
        DB::beginTransaction();
        // Invoice
        $Affected = Invoice::create([
            'invoice_no' => $request->post('invoice_no'),
            'title' => $request->post('title'),
            'bill_to' => $request->post('bill_to'),
            'fullName' => $request->post('fullName'),
            'state' => $request->post('state'),
            'city' => $request->post('city'),
            'street' => $request->post('street'),
            'zipcode' => $request->post('zipcode'),
            'player' => $request->post('player'),
            'due_type' => $request->post('due_type'),
            'send_date' => $SendDate,
            'due_date' => $DueDate,
            'discount' => $request->post('discount'),
            'discount_price' => 0,
            'processing_fee' => $request->post('processing_fee'),
            'processing_fee_price' => 0,
            'tax_rate' => $request->post('tax_rate'),
            'tax_rate_price' => 0,
            'total_bill' => 0,
            'message' => $request->post('message'),
            'invoice_pdf' => $InvoicePDFName,
            'created_by' => Auth::id(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $InvoiceId = $Affected->id;

        if ($request['item'] != "") {
            foreach ($request['item'] as $index => $item) {
                if ($item['item'] != "" && $item['price'] != "" && $item['quantity'] != "") {
                    $ItemTitle = $item['item'];
                    $ItemQuantity = $item['quantity'];
                    $ItemPrice = $item['price'];
                    $TotalItemPrice = $ItemPrice * $ItemQuantity;
                    $SubTotal = $SubTotal + $TotalItemPrice;

                    array_push($item_title, $ItemTitle);
                    array_push($item_price, $ItemPrice);
                    array_push($item_quantity, $ItemQuantity);

                    $Affected2 = InvoiceItem::create([
                        'invoice' => $InvoiceId,
                        'item' => $ItemTitle,
                        'price' => $ItemPrice,
                        'quantity' => $ItemQuantity,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }

        $SubTotalBeforeDiscount = $SubTotal;

        // Calculate Total Bill
        $DiscountPrice = 0;
        if ($request->post('discount') > 0) {
            $DiscountPrice = (($SubTotal / 100) * $request->post('discount'));
            $SubTotal = $SubTotal - $DiscountPrice;
        }
        $ProcessingFeePrice = (($SubTotal / 100) * $request->post('processing_fee'));
        $TaxRatePrice = (($SubTotal / 100) * $request->post('tax_rate'));
        $TotalBill = round($SubTotal + $ProcessingFeePrice + $TaxRatePrice, 2);

        $Affected1 = null;
        $Affected2 = null;
        /*// Transaction
        $Affected1 = Transaction::create([
            'type' => 1,
            'bill_to' => $request->post('bill_to'),
            'invoice_id' => $InvoiceId,
            'total_amount' => $TotalBill,
            'amount_paid' => 0,
            'status' => 1,
            'paid_date' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);*/

        $Affected2 = DB::table('invoices')
            ->where('id', '=', $InvoiceId)
            ->update([
                'discount_price' => $DiscountPrice,
                'processing_fee_price' => $ProcessingFeePrice,
                'tax_rate_price' => $TaxRatePrice,
                'total_bill' => $TotalBill,
                /*'transaction_id' => $Affected1->id,*/
                'updated_at' => Carbon::now(),
            ]);

        if ($Affected && $Affected2) {
            DB::commit();
            // Send Payment Link
            if($request['due_type'] == 'on receipt') {
                $HomeController = new HomeController();
                $HomeController->AdminInvoiceEmail($InvoiceId);
            }

            // GENERATE PDF
            // $ParentName = "";
            // $ParentAddress = "";
            //
            // if ($request->post('bill_to') == -1) {
            //   $ParentName = $request['fullName'];
            //
            //   if ($request['street'] != "") {
            //     $ParentAddress .= $request['street'] . ",";
            //   }
            //   if ($request['city'] != "") {
            //     $ParentAddress .= " " . $request['city'] . ",";
            //   }
            //   if ($request['state'] != "") {
            //     $ParentAddress .= " " . $request['state'] . " ";
            //   }
            //   if ($request['zipcode'] != "") {
            //     $ParentAddress .= $request['zipcode'];
            //   }
            // } else {
            //   $ParentDetails = $this->GetParentDetails($request['bill_to']);
            //
            //   if ($ParentDetails[0]->middleName != "") {
            //     $ParentName = $ParentDetails[0]->firstName . " " . $ParentDetails[0]->middleName . " " . $ParentDetails[0]->lastName;
            //   } else {
            //     $ParentName = $ParentDetails[0]->firstName . " " . $ParentDetails[0]->lastName;
            //   }
            //
            //   if ($ParentDetails[0]->street != "") {
            //     $ParentAddress .= $ParentDetails[0]->street . ",";
            //   }
            //   if ($ParentDetails[0]->city != "") {
            //     $ParentAddress .= " " . $ParentDetails[0]->city . ",";
            //   }
            //   if ($ParentDetails[0]->state != "") {
            //     $ParentAddress .= " " . $ParentDetails[0]->state . " ";
            //   }
            //   if ($ParentDetails[0]->zipcode != "") {
            //     $ParentAddress .= $ParentDetails[0]->zipcode;
            //   }
            // }
            //
            // if ($DueDate != "") {
            //     $DueDate = Carbon::parse($DueDate)->format('m-d-Y');
            // }
            //
            // $data = array(
            //   'parent_name' => $ParentName,
            //   'parent_address' => $ParentAddress,
            //   'invoice_no' => $request->post('invoice_no'),
            //   'invoice_date' => Carbon::parse($SendDate)->format('m-d-Y'),
            //   'due_date' => $DueDate,
            //   'item_title' => json_encode($item_title),
            //   'item_price' => json_encode($item_price),
            //   'item_quantity' => json_encode($item_quantity),
            //   'subtotal' => $SubTotalBeforeDiscount,
            //   'discount_percentage' => $request->post('discount'),
            //   'discount_price' => $DiscountPrice,
            //   'processing_fee_percentage' => $request->post('processing_fee'),
            //   'processing_fee_price' => $ProcessingFeePrice,
            //   'tax_rate_percentage' => $request->post('tax_rate'),
            //   'tax_rate_price' => $TaxRatePrice,
            //   'total_bill' => $TotalBill,
            // );
            //
            // $this->GenerateInvoicePDF($data, $InvoicePDFName);
            return redirect()->route('billing.invoices')->with('success', 'Invoice created successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('billing.invoices')->with('error', 'An unhandled error occurred');
        }
    }

    public function AdminInvoicePDF($InvoiceId)
    {
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

        $InvoicePDFName = "MSA_invoice_" . $InvoiceDetails[0]->invoice_no . ".pdf";

        foreach ($InvoiceItems as $key => $value) {
            array_push($item_title, $value->item);
            array_push($item_price, $value->price);
            array_push($item_quantity, $value->quantity);
            $TotalItemPrice = 0;
            $TotalItemPrice = $value->price * $value->quantity;
            $SubTotal = $SubTotal + $TotalItemPrice;
        }

        if ($InvoiceDetails[0]->bill_to == -1) {
            $ParentName = $InvoiceDetails[0]->fullName;

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
            $ParentDetails = $this->GetParentDetails($InvoiceDetails[0]->bill_to);

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

        return $this->GenerateInvoicePDF($data, $InvoicePDFName);
    }

    public function GenerateInvoicePDF($data, $invoice_name)
    {
        $pdf = PDF::loadView('dashboard.billing.invoices.invoice-pdf', $data);
        return $pdf->download($invoice_name);
    }

    public function GetParentDetails($parent_id)
    {
        $ParentDetails = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 5)
            ->where('users.status', '=', 1)
            ->where('users.id', $parent_id)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.street', 'user_details.city', 'user_details.state', 'user_details.zipcode')
            ->get();

        return $ParentDetails;
    }

    public function AdminDeleteInvoice(Request $request)
    {
        $InvoiceId = $request['id'];
        DB::beginTransaction();
        $affected = DB::table('invoices')
            ->where('id', $InvoiceId)
            ->update([
                'updated_at' => Carbon::now(),
                'deleted_at' => Carbon::now()
            ]);
        if ($affected) {
            DB::commit();
            return redirect()->route('billing.invoices')->with('success', 'Invoice has been deleted successfully');
        } else {
            DB::rollBack();
            return redirect()->route('billing.invoices')->with('error', 'An unhandled error occurred');
        }
    }

    public function AdminEditInvoice($id)
    {
        $page = "billing";
        $Role = Session::get('user_role');
        $invoice_id = base64_decode($id);
        $InvoiceDetails = DB::table('invoices')->where("id", $invoice_id)->get();
        $InvoiceItems = DB::table('invoice_items')->where("invoice", $invoice_id)->get();
        $Parents = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 5)
            ->where('users.status', '=', 1)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();
        $Players = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 6)
            ->where('users.status', '=', 1)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();
        $States = DB::table('states')
            ->get();
        $Cities = DB::table('locations')
            ->where('state_name', '=', $InvoiceDetails[0]->state)
            ->orderBy("city", "ASC")
            ->get()
            ->unique("city");
        $Configuration = DB::table('magic_numbers')->get();
        $Invoices = DB::table('invoices')->orderBy("id", "DESC")->limit(1)->get();

        return view('dashboard.billing.invoices.edit', compact('page', 'Role', 'Parents', 'Players', 'States', 'Cities', 'Configuration', 'invoice_id', 'InvoiceDetails', 'InvoiceItems'));
    }

    public function AdminUpdateInvoice(Request $request)
    {
        $InvoiceId = $request['invoice_id'];
        $TotalBill = 0;
        $SubTotal = 0;
        $SubTotalBeforeDiscount = 0;
        $DueType = $request['old_due_type'];
        $SendDate = $request['old_send_date'];
        $DueDate = $request['old_due_date'];

        if ($request->post('due_type') != $DueType) {
            if ($request->post('due_type') == "on receipt") {
                $DueType = $request->post('due_type');
                $SendDate = date('Y-m-d');
                $DueDate = null;
            } elseif ($request->post('due_type') == "pay now") {
                $DueType = $request->post('due_type');
                $SendDate = date('Y-m-d');
                $DueDate = date('Y-m-d');
            } elseif ($request->post('due_type') == "on a specific date") {
                $DueType = $request->post('due_type');
                $SendDate = date('Y-m-d');
                $DueDate = Carbon::parse($request->post('dueDate'))->format('Y-m-d');
            }
        }

        DB::beginTransaction();
        $Affected = null;

        // Delete old invoice items
        DB::table('invoice_items')->where('invoice', $InvoiceId)->delete();

        if ($request['item'] != "") {
            foreach ($request['item'] as $index => $item) {
                if ($item['item'] != '' && $item['price'] != '') {
                    $ItemTitle = $item['item'];
                    $ItemQuantity = $item['quantity'];
                    $ItemPrice = $item['price'];
                    $TotalItemPrice = $ItemQuantity * $ItemPrice;
                    $SubTotal = $SubTotal + $TotalItemPrice;

                    $Affected = InvoiceItem::create([
                        'invoice' => $InvoiceId,
                        'item' => $ItemTitle,
                        'price' => $ItemPrice,
                        'quantity' => $ItemQuantity,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }

        $SubTotalBeforeDiscount = $SubTotal;

        // Calculate Total Bill
        $DiscountPrice = 0;
        if ($request->post('discount') > 0) {
            $DiscountPrice = (($SubTotal / 100) * $request->post('discount'));
            $SubTotal = $SubTotal - $DiscountPrice;
        }
        $ProcessingFeePrice = (($SubTotal / 100) * $request->post('processing_fee'));
        $TaxRatePrice = (($SubTotal / 100) * $request->post('tax_rate'));
        $TotalBill = $SubTotal + $ProcessingFeePrice + $TaxRatePrice;

        $Affected2 = null;
        $Affected2 = DB::table('invoices')
            ->where('id', '=', $InvoiceId)
            ->update([
                'title' => $request->post('title'),
                'bill_to' => $request->post('bill_to'),
                'fullName' => $request->post('fullName'),
                'state' => $request->post('state'),
                'city' => $request->post('city'),
                'street' => $request->post('street'),
                'zipcode' => $request->post('zipcode'),
                'player' => $request->post('player'),
                'due_type' => $DueType,
                'send_date' => $SendDate,
                'due_date' => $DueDate,
                'discount' => $request->post('discount'),
                'discount_price' => $DiscountPrice,
                'processing_fee' => $request->post('processing_fee'),
                'processing_fee_price' => $ProcessingFeePrice,
                'tax_rate' => $request->post('tax_rate'),
                'tax_rate_price' => $TaxRatePrice,
                'total_bill' => $TotalBill,
                'message' => $request->post('message'),
                'updated_at' => Carbon::now(),
            ]);

        if ($Affected) {
            DB::commit();
            return redirect()->route('billing.invoices')->with('success', 'Invoice updated successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('billing.invoices')->with('error', 'An unhandled error occurred');
        }
    }
}
