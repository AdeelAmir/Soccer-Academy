<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MagicNumbersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $page = "configuration";
        $MagicNumbers = DB::table('magic_numbers')
            ->where('id', '=', 1)
            ->get();
        return view('dashboard.configuration.magic-numbers.index', compact('page', 'MagicNumbers'));
    }

    function update(Request $request){

        $holding = $request['holding_deadline'];
        $account = $request['suspended_account'];

//        if ($holding > $account ){
//            return redirect()->route('configuration.magic-numbers')->with('error', 'Holding deadline is greater then suspended account');
//        }

        DB::beginTransaction();
        $Data = array();
        foreach ($request->all() as $key => $value){
            if($key != '_token' && $key != 'id' && $key != 'submitEditMagicNumberForm'){
                $Data[$key] = $value;
            }
        }
        $Affected = DB::table('magic_numbers')
            ->where('id', '=', $request->post('id'))
            ->update($Data);
        if ($Affected) {
            DB::commit();
            return redirect()->route('configuration.magic-numbers')->with('success', 'Magic Numbers updated successfully!');
        } else {
            DB::rollBack();
            return redirect()->route('configuration.magic-numbers')->with('error', 'An unhandled error occurred');
        }
    }
}
