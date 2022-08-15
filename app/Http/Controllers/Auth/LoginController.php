<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\UserActivity;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if (isset($user)) {
            if ($user->status != 1) {
                Auth::logout();
                return redirect('/login')->with('error', 'Your account has been deactivated. Please contact one-0-one admin.');
            } else {
                Session::put('user_role', $user->role_id);
                // Update user logged in datetime
                $user->last_logged_in = Carbon::now();
                $user->online_status = 1;
                $user->save();

                // Add an entry in user activity to record login time
                $affected = UserActivity::create([
                    'user_id' => $user->id,
                    'sender_id' => $user->id,
                    'message' => "This user is logged in at " . Carbon::now()->format('m/d/Y g:i a'),
                    'created_at' => Carbon::now(),
                ]);

                /*Check for Player Documents for Parents only*/
                if($user->role_id == 5) {
                    $PlayerUser = DB::table('users')
                        ->where('parent_id', '=', Auth::id())
                        ->where('role_id', '=', 6)
                        ->where('status', '=', 1)
                        ->where('deleted_at', '=', null)
                        ->get();
                    if(sizeof($PlayerUser) > 0) {
                        /*Only Condition if player exists*/
                        $PlayerDocuments = DB::table('user_documents')
                            ->where('user_id', '=', $PlayerUser[0]->id)
                            ->where(function ($query) {
                                $query->orWhere('user_documents.document_type', '=', 'State ID');
                                $query->orWhere('user_documents.document_type', '=', 'Birth Certificate');
                            })
                            ->get();
                        if(sizeof($PlayerDocuments) == 0) {
                            DB::table('users')
                                ->where('id', '=', Auth::id())
                                ->update([
                                    'player_document_status' => 1
                                ]);
                            Session::put('user_player', $PlayerUser[0]->id);
                        }
                    }
                }
                return redirect()->route('dashboard');
            }
        }

        return redirect('/login');
    }

    public function logout()
    {
        // Add an entry in user activity to record login time
        if(isset(auth()->user()->id)){
            DB::beginTransaction();
            $affected = UserActivity::create([
                'user_id' => auth()->user()->id,
                'sender_id' => auth()->user()->id,
                'message' => "This user is logout in at " . Carbon::now()->format('m/d/Y g:i a'),
                'created_at' => Carbon::now(),
            ]);

            $affected1 = DB::table('users')
                ->where('id', auth()->user()->id)
                ->update([
                  'online_status' => 0,
                  'updated_at' => Carbon::now(),
            ]);
            if ($affected && $affected1) {
              DB::commit();
            }
            else {
              DB::rollback();
            }
        }
        Auth::logout();
        return redirect()->route('HomeRoute');
    }
}
