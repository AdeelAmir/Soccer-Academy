<?php

namespace App\Http\Controllers;

use App\Helpers\SiteHelper;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserDocuments;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page = "users";
        $UserRole = Session::get("user_role");
        $Roles = array();
        if ($UserRole == 1) {
            $Roles = DB::table('roles')
                ->where('id', '<>', 1)
                ->where('deleted_at', null)
                ->get();
        } elseif ($UserRole == 2) {
            $Roles = DB::table('roles')
                ->whereNotIn('id', array(1, 2))
                ->where('deleted_at', null)
                ->get();
        } elseif ($UserRole == 3) {
            $Roles = DB::table('roles')
                ->whereNotIn('id', array(1, 2, 3))
                ->where('deleted_at', null)
                ->get();
        } elseif ($UserRole == 5) {
            $Roles = DB::table('roles')
                ->whereNotIn('id', array(1, 2, 3, 4, 7, 8))
                ->where('deleted_at', null)
                ->get();
        }
        $States = DB::table('states')->get();
        $Locations = DB::table('player_locations')
            ->select('player_locations.*')
            ->get();
        return view('dashboard.users.index', compact('page', 'UserRole', 'Roles', 'States', 'Locations'));
    }

    public function add($RoleId)
    {
        $page = "users";
        $UserRole = Session::get('user_role');
        $UserDetails = DB::table("user_details")->where('user_id', Auth::id())->get();
        $RoleDetials = DB::table('roles')->where('id', $RoleId)->get();

        $States = DB::table('states')
            ->get();
        $Locations = DB::table('player_locations')
            ->where('status', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();
        $Levels = DB::table('levels')
            ->where('deleted_at', '=', null)
            ->get();
        $Categories = DB::table('categories')
            ->where('deleted_at', '=', null)
            ->get();
        $Parents = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 5)
            ->where('users.status', '=', 1)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();
        $PlayerPositions = DB::table('player_positions')
            ->where('deleted_at', null)
            ->get();
        return view('dashboard.users.add', compact('page', 'UserRole', 'UserDetails', 'RoleId', 'RoleDetials', 'States', 'Locations', 'Levels', 'Categories', 'Parents', 'PlayerPositions'));
    }

    public function store(Request $request)
    {
        $Role = $request['role'];
        $FirstName = ucwords(strtolower($request['firstName']));
        $MiddleName = ucwords(strtolower($request['middleName']));
        $LastName = ucwords(strtolower($request['lastName']));
        $Dob = $request['dob'];
        $Gender = $request['gender'];
        $ManagerLocations = $request['managerLocations'];
        $CoachLevels = $request['coachLevels'];
        $CoachCategories = $request['coachCategories'];
        $CoachLocations = $request['coachLocations'];
        $ParentProfession = $request['parentProfession'];
        $AthletesParent = $request['athletesParent'];
        $AthletesLevel = $request['athletesLevel'];
        $AthletesCategory = $request['athletesCategory'];
        $AthletesTrainingDays = null;
        if ($request['athletesTrainingDays'] != "") {
            $AthletesTrainingDays = implode(",", $request['athletesTrainingDays']);
        }
        $AthletesDoctorName = $request['athletesDoctorName'];
        $AthletesDoctorPhoneNumber = $request['athletesDoctorPhoneNumber'];
        $AthletesInsuranceName = $request['athletesInsuranceName'];
        $AthletesPolicyNumber = $request['athletesPolicyNumber'];
        $AthletesHeightFt = $request['athletesHeightFt'];
        $AthletesHeightInches = $request['athletesHeightInches'];
        $AthletesWeight = $request['athletesWeight'];
        $AthletesAllergies = $request['athletesAllergies'];
        $AthletesRelationship = $request['athletesRelationship'];
        $AthletesPosition = $request['athletesPosition'];
        $Email = $request['email'];
        $Phone1 = $request['phone1'];
        $Phone2 = $request['phone2'];
        $SocialMedia = $request['socialMedia'];
        $SocialMedia2 = $request['socialMedia2'];
        $SocialMedia3 = $request['socialMedia3'];
        $Street = $request['street'];
        $City = $request['city'];
        $State = $request['state'];
        $ZipCode = $request['zipcode'];
        $ProfilePic = null;
        /*Password Work - Start*/
        $Password = "";
        $UserBirthdayMonth = Carbon::parse($Dob)->format('M');
        $UserBirthday = explode("/", $Dob);
        $UserBirthdayYear = $UserBirthday[2];
        $Password .= $UserBirthdayMonth . "!" . $UserBirthdayYear;
        /*Password Work - End*/
        $UserId = substr($FirstName, 0, 1) . substr($LastName, 0, 1) . mt_rand(10000, 99999);
        $DocumentTypes = array();
        $DocumentNames = array();
        $DocumentExpirationDate = array();
        $DocumentNumbers = array();
        $FileNames = array();

        if (isset($request['profile_pic'])) {
            $CurrentFile = $request['profile_pic'];
            $FileStoragePath = '/public/user-profiles/';
            $Extension = $CurrentFile->extension();
            $file = $CurrentFile->getClientOriginalName();
            $FileName = pathinfo($file, PATHINFO_FILENAME);
            $FileName = $FileName . '-' . date('Y-m-d') . mt_rand(100, 1000) . '.' . $Extension;
            $result = $CurrentFile->storeAs($FileStoragePath, $FileName);
            $ProfilePic = $FileName;
        }

        if ($request->has('documents')) {
            foreach ($request->post('documents') as $index => $document) {
                if (isset($request['documents'][$index]['documentFile'])) {
                    $CurrentFile = $request['documents'][$index]['documentFile'];
                    $FileStoragePath = '/public/user-documents/';
                    $Extension = $CurrentFile->extension();
                    $file = $CurrentFile->getClientOriginalName();
                    $FileName = pathinfo($file, PATHINFO_FILENAME);
                    $FileName = $FileName . '-' . date('Y-m-d') . mt_rand(100, 1000) . '.' . $Extension;
                    $result = $CurrentFile->storeAs($FileStoragePath, $FileName);
                    $FileNames[] = $FileName;
                    $DocumentTypes[] = $document['documentName'];
                    $DocumentNames[] = $document['documentNameOthers'];
                    $DocumentExpirationDate[] = $document['documentExpirationDate'];
                    $DocumentNumbers[] = $document['documentNumbers'];
                }
            }
        }

        DB::beginTransaction();
        $Affected1 = User::create([
            'userId' => $UserId,
            'parent_id' => Auth::id(),
            'email' => $Email,
            'password' => bcrypt($Password),
            'role_id' => $Role,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $NewUserId = $Affected1->id;

        $Affected2 = UserDetails::create([
            'user_id' => $NewUserId,
            'parent_id' => Auth::id(),
            'firstName' => $FirstName,
            'middleName' => $MiddleName,
            'lastName' => $LastName,
            'dob' => $Dob,
            'gender' => $Gender,
            'managerLocations' => $ManagerLocations != null ? implode(',', $ManagerLocations) : null,
            'coachLevels' => $CoachLevels != null ? implode(',', $CoachLevels) : null,
            'coachCategories' => $CoachCategories != null ? implode(',', $CoachCategories) : null,
            'coachLocations' => $CoachLocations != null ? implode(',', $CoachLocations) : null,
            'parent_profession' => $ParentProfession,
            'athletesParent' => $AthletesParent,
            'athletesLevel' => $AthletesLevel,
            'athletesCategory' => $AthletesCategory,
            'athletesTrainingDays' => $AthletesTrainingDays,
            'athletesDoctorName' => $AthletesDoctorName,
            'athletesDoctorPhoneNumber' => $AthletesDoctorPhoneNumber,
            'athletesInsuranceName' => $AthletesInsuranceName,
            'athletesPolicyNumber' => $AthletesPolicyNumber,
            'athletesHeightFt' => $AthletesHeightFt,
            'athletesHeightInches' => $AthletesHeightInches,
            'athletesWeight' => $AthletesWeight,
            'athletesAllergies' => $AthletesAllergies,
            'athletesRelationship' => $AthletesRelationship,
            'athletesPosition' => $AthletesPosition,
            'phone1' => $Phone1,
            'phone2' => $Phone2,
            'socialMedia' => $SocialMedia,
            'socialMedia2' => $SocialMedia2,
            'socialMedia3' => $SocialMedia3,
            'street' => $Street,
            'city' => $City,
            'state' => $State,
            'zipcode' => $ZipCode,
            'profile_pic' => $ProfilePic,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $Affected3 = null;
        foreach ($FileNames as $index => $fileName) {
            $DocumentName = null;
            $ExpirationDate = null;
            if ($DocumentTypes[$index] == 'State ID' || $DocumentTypes[$index] == 'Passport') {
                $ExpirationDate = $DocumentExpirationDate[$index];
                if ($ExpirationDate != "") {
                    $ExpirationDate = Carbon::createFromFormat('m/d/Y', $ExpirationDate)->format('Y-m-d');
                }
            } elseif ($DocumentTypes[$index] == 'Others') {
                $DocumentName = $DocumentNames[$index];
            }
            $Affected3 = UserDocuments::create([
                'user_id' => $NewUserId,
                'document_type' => $DocumentTypes[$index],
                'document_name' => $DocumentName,
                'expiration_date' => $ExpirationDate,
                'document_number' => $DocumentNumbers[$index],
                'document' => $fileName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        /*User Power Entry*/
        $Affected4 = SiteHelper::InsertUserPower($NewUserId, 0, 0, 0);

        // Add an entry in user activity how create this user
        $GetUserDetails = DB::table('users')
            ->where('users.deleted_at', '=', null)
            ->where('users.id', '=', Auth::id())
            ->join('user_details', 'user_details.user_id', '=', 'users.id')
            ->select('user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();

        // Get user first name and last name of person who create this user
        $UserFullName = "";
        if ($GetUserDetails[0]->middleName != "") {
            $UserFullName = $GetUserDetails[0]->firstName . " " . $GetUserDetails[0]->lastName;
        } else {
            $UserFullName = $GetUserDetails[0]->firstName . " " . $GetUserDetails[0]->middleName . " " . $GetUserDetails[0]->lastName;
        }

        $Affected5 = null;
        $Affected5 = UserActivity::create([
            'user_id' => $NewUserId,
            'sender_id' => $NewUserId,
            'message' => $UserFullName . " created the user",
            'created_at' => Carbon::now(),
        ]);

        if ($Role != 1 && $Role != 2) {
            // TRAINING ROOM ASSIGNMENT WORK
            SiteHelper::InsertTrainingRoom($Role, $NewUserId);
        }

        if ($Affected1 && $Affected2 && $Affected4 && $Affected5) {
            DB::commit();
            return redirect()->route('users')->with('success', 'User has been registered successfully');
        } else {
            DB::rollback();
            return redirect()->route('users')->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function load(Request $request)
    {
        $Role = Session::get("user_role");
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];

        $FullName = $request->post('Name');
        $Phone = $request->post('Phone');
        if ($Phone != "") {
            $Phone = str_replace("-", "", $Phone);
        }
        $State = $request->post('State');
        $City = $request->post('City');
        $ZipCode = $request->post('ZipCode');
        $UserRole = $request->post('UserRole');
        $Status = $request->post('Status');
        $Location = $request->post('location');

        $columnIndex = $request->post('order')[0]['column']; // Column index
        $columnName = $request->post('columns')[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->post('order')[0]['dir']; // asc or desc

        if ($columnName == 'created_at') {
            $columnName = 'users.created_at';
        }

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;

        if ($Role == 1) {
            if ($searchTerm == '') {
                $fetch_data = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->where(function ($query) use ($FullName) {
                        if ($FullName != "") {
                            $FullName = (explode(" ", $FullName));
                            if (isset($FullName[0])) {
                                $query->orWhere('user_details.firstName', 'LIKE', '%' . $FullName[0] . '%');
                            } elseif (isset($FullName[1])) {
                                $query->orWhere('user_details.middleName', 'LIKE', '%' . $FullName[1] . '%');
                            } elseif (isset($FullName[2])) {
                                $query->orWhere('user_details.lastName', 'LIKE', '%' . $FullName[2] . '%');
                            }
                        }
                    })
                    ->where(function ($query) use ($Phone) {
                        if ($Phone != "") {
                            $query->orWhere('user_details.phone', '=', $Phone);
                            $query->orWhere('user_details.phone2', '=', $Phone);
                        }
                    })
                    ->where(function ($query) use ($State, $City, $ZipCode, $UserRole, $Status) {
                        if ($State != "0") {
                            $query->where('user_details.state', '=', $State);
                            if ($City != "") {
                                $query->where('user_details.city', '=', $City);
                            }
                        }
                        if ($ZipCode != "") {
                            $query->where('user_details.zipcode', '=', $ZipCode);
                        }
                        if ($UserRole != "") {
                            $query->where('users.role_id', '=', $UserRole);
                        }
                        if ($Status != "") {
                            $query->where('users.status', '=', $Status);
                        }
                    })
                    ->where(function ($query) use ($Location) {
                        if ($Location != "") {
                            $query->orWhereRaw('FIND_IN_SET(?, user_details.managerLocations) > 0', array($Location));
                            $query->orWhereRaw('FIND_IN_SET(?, user_details.coachLocations) > 0', array($Location));
                        }
                    })
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->where(function ($query) use ($FullName) {
                        if ($FullName != "") {
                            $FullName = (explode(" ", $FullName));
                            if (isset($FullName[0])) {
                                $query->orWhere('user_details.firstName', 'LIKE', '%' . $FullName[0] . '%');
                            } elseif (isset($FullName[1])) {
                                $query->orWhere('user_details.middleName', 'LIKE', '%' . $FullName[1] . '%');
                            } elseif (isset($FullName[2])) {
                                $query->orWhere('user_details.lastName', 'LIKE', '%' . $FullName[2] . '%');
                            }
                        }
                    })
                    ->where(function ($query) use ($Phone) {
                        if ($Phone != "") {
                            $query->orWhere('user_details.phone', '=', $Phone);
                            $query->orWhere('user_details.phone2', '=', $Phone);
                        }
                    })
                    ->where(function ($query) use ($State, $City, $ZipCode, $UserRole, $Status) {
                        if ($State != "0") {
                            $query->where('user_details.state', '=', $State);
                            if ($City != "") {
                                $query->where('user_details.city', '=', $City);
                            }
                        }
                        if ($ZipCode != "") {
                            $query->where('user_details.zipcode', '=', $ZipCode);
                        }
                        if ($UserRole != "") {
                            $query->where('users.role_id', '=', $UserRole);
                        }
                        if ($Status != "") {
                            $query->where('users.status', '=', $Status);
                        }
                    })
                    ->where(function ($query) use ($Location) {
                        if ($Location != "") {
                            $query->orWhereRaw('FIND_IN_SET(?, user_details.managerLocations) > 0', array($Location));
                            $query->orWhereRaw('FIND_IN_SET(?, user_details.coachLocations) > 0', array($Location));
                        }
                    })
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.userId', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('users.email', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.phone1', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.state', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->where(function ($query) use ($FullName) {
                        if ($FullName != "") {
                            $FullName = (explode(" ", $FullName));
                            if (isset($FullName[0])) {
                                $query->orWhere('user_details.firstName', 'LIKE', '%' . $FullName[0] . '%');
                            } elseif (isset($FullName[1])) {
                                $query->orWhere('user_details.middleName', 'LIKE', '%' . $FullName[1] . '%');
                            } elseif (isset($FullName[2])) {
                                $query->orWhere('user_details.lastName', 'LIKE', '%' . $FullName[2] . '%');
                            }
                        }
                    })
                    ->where(function ($query) use ($Phone) {
                        if ($Phone != "") {
                            $query->orWhere('user_details.phone', '=', $Phone);
                            $query->orWhere('user_details.phone2', '=', $Phone);
                        }
                    })
                    ->where(function ($query) use ($State, $City, $ZipCode, $UserRole, $Status) {
                        if ($State != "0") {
                            $query->where('user_details.state', '=', $State);
                            if ($City != "") {
                                $query->where('user_details.city', '=', $City);
                            }
                        }
                        if ($ZipCode != "") {
                            $query->where('user_details.zipcode', '=', $ZipCode);
                        }
                        if ($UserRole != "") {
                            $query->where('users.role_id', '=', $UserRole);
                        }
                        if ($Status != "") {
                            $query->where('users.status', '=', $Status);
                        }
                    })
                    ->where(function ($query) use ($Location) {
                        if ($Location != "") {
                            $query->orWhereRaw('FIND_IN_SET(?, user_details.managerLocations) > 0', array($Location));
                            $query->orWhereRaw('FIND_IN_SET(?, user_details.coachLocations) > 0', array($Location));
                        }
                    })
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.userId', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('users.email', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.phone1', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.state', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->where(function ($query) use ($FullName) {
                        if ($FullName != "") {
                            $FullName = (explode(" ", $FullName));
                            if (isset($FullName[0])) {
                                $query->orWhere('user_details.firstName', 'LIKE', '%' . $FullName[0] . '%');
                            } elseif (isset($FullName[1])) {
                                $query->orWhere('user_details.middleName', 'LIKE', '%' . $FullName[1] . '%');
                            } elseif (isset($FullName[2])) {
                                $query->orWhere('user_details.lastName', 'LIKE', '%' . $FullName[2] . '%');
                            }
                        }
                    })
                    ->where(function ($query) use ($Phone) {
                        if ($Phone != "") {
                            $query->orWhere('user_details.phone', '=', $Phone);
                            $query->orWhere('user_details.phone2', '=', $Phone);
                        }
                    })
                    ->where(function ($query) use ($State, $City, $ZipCode, $UserRole, $Status) {
                        if ($State != "0") {
                            $query->where('user_details.state', '=', $State);
                            if ($City != "") {
                                $query->where('user_details.city', '=', $City);
                            }
                        }
                        if ($ZipCode != "") {
                            $query->where('user_details.zipcode', '=', $ZipCode);
                        }
                        if ($UserRole != "") {
                            $query->where('users.role_id', '=', $UserRole);
                        }
                        if ($Status != "") {
                            $query->where('users.status', '=', $Status);
                        }
                    })
                    ->where(function ($query) use ($Location) {
                        if ($Location != "") {
                            $query->orWhereRaw('FIND_IN_SET(?, user_details.managerLocations) > 0', array($Location));
                            $query->orWhereRaw('FIND_IN_SET(?, user_details.coachLocations) > 0', array($Location));
                        }
                    })
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        } elseif ($Role == 2) {
            $RoleNotIncluded = array(1, 2);
            if ($searchTerm == '') {
                $fetch_data = DB::table('users')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('users')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.userId', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('users.email', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.phone1', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.state', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.userId', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('users.email', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.phone1', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.state', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        } elseif ($Role == 3) {
            $RoleNotIncluded = array(1, 2, 3);
            if ($searchTerm == '') {
                $fetch_data = DB::table('users')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('users')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.userId', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('users.email', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.phone1', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.state', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.userId', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('users.email', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.phone1', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.state', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        } elseif ($Role == 5) {
            $RoleNotIncluded = array(1, 2, 3, 4, 7, 8);
            if ($searchTerm == '') {
                $fetch_data = DB::table('users')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->where('users.deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.parent_id', '=', Auth::id());
                        $query->orWhere('user_details.athletesParent', '=', Auth::id());
                    })
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('users')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->where('users.deleted_at', '=', null)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.parent_id', '=', Auth::id());
                        $query->orWhere('user_details.athletesParent', '=', Auth::id());
                    })
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            } else {
                $fetch_data = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.parent_id', '=', Auth::id());
                        $query->orWhere('user_details.athletesParent', '=', Auth::id());
                    })
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.userId', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('users.email', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.phone1', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.state', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->offset($start)
                    ->limit($limit)
                    ->get();
                $recordsTotal = sizeof($fetch_data);
                $recordsFiltered = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->whereNotIn('users.id', array(1, Auth::id()))
                    ->where('users.deleted_at', '=', null)
                    ->whereNotIn('users.role_id', $RoleNotIncluded)
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.parent_id', '=', Auth::id());
                        $query->orWhere('user_details.athletesParent', '=', Auth::id());
                    })
                    ->where(function ($query) use ($searchTerm) {
                        $query->orWhere('users.userId', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('users.email', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('roles.title', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.phone1', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.city', 'LIKE', '%' . $searchTerm . '%');
                        $query->orWhere('user_details.state', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->select('users.id', 'users.userId', 'users.email', 'users.status', 'users.created_at', 'roles.title AS RoleTitle', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName', 'user_details.phone1', 'user_details.city', 'user_details.state')
                    ->orderBy($columnName, $columnSortOrder)
                    ->count();
            }
        }

        $data = array();
        $SrNo = $start + 1;
        foreach ($fetch_data as $row => $item) {
            $UserInfo = '<span style="color: #000 !important;">' . wordwrap("<strong>" . $item->firstName . " " . $item->lastName . "</strong><br>" . $item->userId . "<br>" . $item->RoleTitle, 50, '<br>') . '</span>';
            $ContactInfo = "";
            if ($item->phone1 != "") {
                $ContactInfo .= "<b><a href='tel: " . SiteHelper::ConvertPhoneNumberFormat($item->phone1) . "' style='color: black;'>" . SiteHelper::ConvertPhoneNumberFormat($item->phone1) . "</a></b><br>";
            }
            $ContactInfo .= "<a href='mailto:" . $item->email . "' style='color: black;'>" . $item->email . "</a><br>";
            if ($item->city != '') {
                $ContactInfo .= "<span class='text-black'>" . $item->city . ", " . "</span>";
            }
            if ($item->state != '') {
                $ContactInfo .= "<span class='text-black'>" . $item->state . "</span>";
            }
            $Action = "";
            $Status = "";
            $BanActiveBtn = "";
            if ($item->status == 1) {
                $Status = '<span class="badge badge-success cursor-pointer" id="ban_' . $item->id . '" data-toggle="tooltip" title="Ban User" onclick="banUser(this.id);">Active</span>';
            } else {
                $Status = '<span class="badge badge-danger cursor-pointer" id="active_' . $item->id . '" data-toggle="tooltip" title="Active User" onclick="activeUser(this.id);">Ban</span>';
            }
            $sub_array = array();
            $sub_array['created_at'] = $item->created_at;
            $sub_array['checkbox'] = '<input type="checkbox" class="checkAllBox allUsersCheckBox" name="checkAllBox[]" value="' . $item->id . '" onchange="CheckIndividualUserCheckbox();" />';
            $sub_array['id'] = $SrNo;
            $sub_array['name'] = $UserInfo;
            $sub_array['contact_info'] = $ContactInfo;
            $sub_array['status'] = $Status;
            $Action .= $BanActiveBtn;
            $Action .= "<span>";
            $Action .= '<button type="button" class="btn btn-primary btn-sm" id="changePassword||' . $item->id . '" onclick="ChangePassword(this.id);" data-toggle="tooltip" title="Change Password"><i class="fas fa-user-lock"></i></button>';
            $Action .= '<button type="button" class="btn btn-primary btn-sm" id="edit||' . $item->id . '" onclick="EditUser(this.id);" data-toggle="tooltip" title="View User"><i class="fas fa-eye"></i></button>';
            if ($Role != 5) {
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="userActivity||' . $item->id . '" onclick="MakeUserActivityTable(this.id);" data-toggle="tooltip" title="User Activities"><i class="fas fa-user-cog"></i></button>';
            }
            if ($Role == 1 || $Role == 2 || $Role == 3) {
                $Action .= '<button type="button" class="btn btn-primary btn-sm" id="power||' . $item->id . '" onclick="PowerUser(this.id);" data-toggle="tooltip" title="Power"><i class="fa fa-key"></i></button>';
            }
            $Action .= "<span>";
            $sub_array['action'] = $Action;
            $data[] = $sub_array;
            $SrNo++;
        }

        $json_data = array(
            "draw" => intval($request->post('draw')),
            "iTotalRecords" => $recordsTotal,
            "iTotalDisplayRecords" => $recordsFiltered,
            "aaData" => $data
        );

        echo json_encode($json_data);
    }

    // User Activities
    public function userActivitiesAll(Request $request)
    {
        $UserId = $request->post('UserId');
        $limit = $request->post('length');
        $start = $request->post('start');
        $searchTerm = $request->post('search')['value'];

        $columnIndex = $request->post('order')[0]['column']; // Column index
        $columnName = $request->post('columns')[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->post('order')[0]['dir']; // asc or desc

        $fetch_data = null;
        $recordsTotal = null;
        $recordsFiltered = null;
        if ($searchTerm == '') {
            $fetch_data = DB::table('user_activities')
                ->where('user_activities.user_id', '=', $UserId)
                ->join('user_details', 'user_details.user_id', '=', 'user_activities.user_id')
                ->select('user_activities.*', 'user_details.firstName AS firstname', 'user_details.lastName AS lastname')
                ->orderBy('user_activities.id', 'DESC')
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('user_activities')
                ->where('user_activities.user_id', '=', $UserId)
                ->join('user_details', 'user_details.user_id', '=', 'user_activities.user_id')
                ->select('user_activities.*', 'user_details.firstName AS firstname', 'user_details.lastName AS lastname')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        } else {
            $fetch_data = DB::table('user_activities')
                ->where('user_activities.user_id', '=', $UserId)
                ->join('user_details', 'user_details.user_id', '=', 'user_activities.user_id')
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('user_activities.message', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('user_activities.*', 'user_details.firstName AS firstname', 'user_details.lastName AS lastname')
                ->orderBy($columnName, $columnSortOrder)
                ->offset($start)
                ->limit($limit)
                ->get();
            $recordsTotal = sizeof($fetch_data);
            $recordsFiltered = DB::table('user_activities')
                ->where('user_activities.user_id', '=', $UserId)
                ->join('user_details', 'user_details.user_id', '=', 'user_activities.user_id')
                ->where(function ($query) use ($searchTerm) {
                    $query->orWhere('user_activities.message', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.firstName', 'LIKE', '%' . $searchTerm . '%');
                    $query->orWhere('user_details.lastName', 'LIKE', '%' . $searchTerm . '%');
                })
                ->select('user_activities.*', 'user_details.firstName AS firstname', 'user_details.lastName AS lastname')
                ->orderBy($columnName, $columnSortOrder)
                ->count();
        }

        $data = array();
        $SrNo = $start + 1;
        $status = "";
        $active_ban = "";
        foreach ($fetch_data as $row => $item) {
            $sender_name = $this->getUserName($item->sender_id);
            $sub_array = array();
            $sub_array['id'] = $SrNo;
            $sub_array['user'] = '<span>' . wordwrap($sender_name, 30, '<br>') . '<br><br>' . Carbon::parse($item->created_at)->format('m/d/Y') . '<br><br>' . Carbon::parse($item->created_at)->format('g:i a') . '</span>';
            $sub_array['message'] = '<span>' . $item->message . '</span>';
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

    public function getUserName($user_id)
    {
        $name = "";
        $UserDetails = DB::table("user_details")
            ->where("user_id", $user_id)
            ->get();

        if ($UserDetails[0]->middleName != "") {
            $name = $UserDetails[0]->firstName . " " . $UserDetails[0]->middleName . " " . $UserDetails[0]->lastName;
        } else {
            $name = $UserDetails[0]->firstName . " " . $UserDetails[0]->lastName;
        }

        return $name;
    }

    public function delete(Request $request)
    {
        $Users = $request->post('checkAllBox');
        DB::beginTransaction();
        $Affected = null;
        foreach ($Users as $key => $user_id) {
            $Affected = DB::table('users')
                ->where('id', $user_id)
                ->update([
                    'updated_at' => Carbon::now(),
                    'deleted_at' => Carbon::now()
                ]);
        }
        if ($Affected) {
            DB::commit();
            return redirect()->route('users')->with('success', 'User has been deleted successfully');
        } else {
            DB::rollback();
            return redirect()->route('users')->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function ban(Request $request)
    {
        $Users = $request->post('checkAllBox');
        $Reason = $request['ban_reason'];
        DB::beginTransaction();
        $Affected = null;
        $Affected2 = null;
        foreach ($Users as $key => $user_id) {
            $Affected = DB::table('users')
                ->where('id', $user_id)
                ->update([
                    'status' => 0,
                    'updated_at' => Carbon::now()
                ]);

            $Affected1 = UserActivity::create([
                'user_id' => $user_id,
                'sender_id' => Auth::id(),
                'message' => $Reason,
                'created_at' => Carbon::now(),
            ]);
        }

        if ($Affected && $Affected1) {
            DB::commit();
            return redirect()->route('users')->with('success', 'User has been banned successfully');
        } else {
            DB::rollback();
            return redirect()->route('users')->with('error', 'An unhandled error occurred');
        }
    }

    public function active(Request $request)
    {
        $Users = $request->post('checkAllBox');
        DB::beginTransaction();
        $Affected = null;
        foreach ($Users as $key => $user_id) {
            $Affected = DB::table('users')
                ->where('id', $user_id)
                ->update([
                    'status' => 1,
                    'updated_at' => Carbon::now()
                ]);
        }
        if ($Affected) {
            DB::commit();
            return redirect()->route('users')->with('success', 'User has been activated successfully');
        } else {
            DB::rollback();
            return redirect()->route('users')->with('error', 'An unhandled error occurred');
        }
    }

    public function BanActive(Request $request)
    {
        $UserId = $request->post('UserId');
        $Type = $request->post('Type');
        $Reason = $request->post('Reason');

        DB::beginTransaction();
        DB::table('users')
            ->where('id', $UserId)
            ->update([
                'status' => $Type == 'ban'? 0 : 1,
                'updated_at' => Carbon::now()
            ]);
        if($Type == 'ban') {
            UserActivity::create([
                'user_id' => $UserId,
                'sender_id' => Auth::id(),
                'message' => $Reason,
                'created_at' => Carbon::now(),
            ]);
        }
        DB::commit();
        return response(['status' => true]);
    }

    public function edit($Id)
    {
        $page = "users";
        $UserRole = Session::get('user_role');
        $Id = base64_decode($Id);
        $Roles = array();
        if ($UserRole == 1) {
            $Roles = DB::table('roles')
                ->where('id', '<>', 1)
                ->get();
        } elseif ($UserRole == 2) {
            $Roles = DB::table('roles')
                ->whereNotIn('id', array(1, 2))
                ->get();
        } elseif ($UserRole == 3) {
            $Roles = DB::table('roles')
                ->whereNotIn('id', array(1, 2, 3))
                ->get();
        } elseif ($UserRole == 5) {
            $Roles = DB::table('roles')
                ->whereNotIn('id', array(1, 2, 3, 4, 7, 8))
                ->get();
        }
        $States = DB::table('states')
            ->get();
        $Locations = DB::table('player_locations')
            ->where('status', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();
        $Levels = DB::table('levels')
            ->where('deleted_at', '=', null)
            ->get();
        $Categories = DB::table('categories')
            ->where('deleted_at', '=', null)
            ->get();
        $Parents = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.deleted_at', '=', null)
            ->where('users.role_id', '=', 5)
            ->where('users.status', '=', 1)
            ->select('users.id', 'user_details.firstName', 'user_details.middleName', 'user_details.lastName')
            ->get();
        $User = DB::table('users')
            ->where('id', '=', $Id)
            ->get();
        $UserDetails = DB::table('user_details')
            ->where('user_id', '=', $Id)
            ->get();
        $UserDocuments = DB::table('user_documents')
            ->where('user_id', '=', $Id)
            ->get();
        $cities = DB::table('locations')
            ->where('state_name', '=', $UserDetails[0]->state)
            ->orderBy("city", "ASC")
            ->get()
            ->unique("city");
        $PlayerPositions = DB::table('player_positions')
            ->where('deleted_at', null)
            ->get();
        return view('dashboard.users.edit', compact('page', 'UserRole', 'Roles', 'States', 'Locations', 'Levels', 'Categories', 'Parents', 'User', 'UserDetails', 'UserDocuments', 'cities', 'PlayerPositions'));
    }

    public function update(Request $request)
    {
        $UserRole = Session::get("user_role");
        $Role = $request['role'];
        $FirstName = ucwords(strtolower($request['firstName']));
        $MiddleName = ucwords(strtolower($request['middleName']));
        $LastName = ucwords(strtolower($request['lastName']));
        $Dob = $request['dob'];
        $Gender = $request['gender'];
        $ManagerLocations = $request['managerLocations'];
        $CoachLevels = $request['coachLevels'];
        $CoachCategories = $request['coachCategories'];
        $CoachLocations = $request['coachLocations'];
        $ParentProfession = $request['parentProfession'];
        $AthletesParent = $request['athletesParent'];
        $AthletesLevel = "";
        $AthletesCategory = "";
        $AthletesPosition = "";
        if ($UserRole != 5) {
            $AthletesLevel = $request['athletesLevel'];
            $AthletesCategory = $request['athletesCategory'];
            $AthletesPosition = $request['athletesPosition'];
        }
        $AthletesTrainingDays = null;
        if ($request['athletesTrainingDays'] != "") {
            $AthletesTrainingDays = implode(",", $request['athletesTrainingDays']);
        }
        $AthletesDoctorName = $request['athletesDoctorName'];
        $AthletesDoctorPhoneNumber = $request['athletesDoctorPhoneNumber'];
        $AthletesInsuranceName = $request['athletesInsuranceName'];
        $AthletesPolicyNumber = $request['athletesPolicyNumber'];
        $AthletesHeightFt = $request['athletesHeightFt'];
        $AthletesHeightInches = $request['athletesHeightInches'];
        $AthletesWeight = $request['athletesWeight'];
        $AthletesAllergies = $request['athletesAllergies'];
        $AthletesRelationship = $request['athletesRelationship'];
        $Email = $request['email'];
        $Phone1 = $request['phone1'];
        $Phone2 = $request['phone2'];
        $SocialMedia = $request['socialMedia'];
        $SocialMedia2 = $request['socialMedia2'];
        $SocialMedia3 = $request['socialMedia3'];
        $Street = $request['street'];
        $City = $request['city'];
        $State = $request['state'];
        $ZipCode = $request['zipcode'];
        $ProfilePic = $request['old_profile_pic'];
        $DocumentTypes = array();
        $DocumentNames = array();
        $DocumentNumbers = array();
        $FileNames = array();
        $DeletedDocuments = "";

        DB::beginTransaction();
        if (isset($request['profile_pic'])) {
            if ($ProfilePic != "") {
                // Remove old profile pic
                $Path = public_path('storage/user-profiles') . '/' . $ProfilePic;
                if (file_exists($Path)) {
                    unlink($Path);
                }
            }
            // Upload profile pic
            $CurrentFile = $request['profile_pic'];
            $FileStoragePath = '/public/user-profiles/';
            $Extension = $CurrentFile->extension();
            $file = $CurrentFile->getClientOriginalName();
            $FileName = pathinfo($file, PATHINFO_FILENAME);
            $FileName = $FileName . '-' . date('Y-m-d') . mt_rand(100, 1000) . '.' . $Extension;
            $result = $CurrentFile->storeAs($FileStoragePath, $FileName);
            $ProfilePic = $FileName;
        }

        if ($request->post('documentsDeleted') != "") {
            $DeletedDocuments = json_decode($request->post('documentsDeleted'));
            foreach ($DeletedDocuments as $deletedDocument) {
                if (file_exists(storage_path('app/public/user-documents/' . $deletedDocument))) {
                    $Result = unlink(storage_path('app/public/user-documents/' . $deletedDocument));
                    DB::table('user_documents')
                        ->where('user_id', '=', $request->post('id'))
                        ->where('document', '=', $deletedDocument)
                        ->delete();
                }
            }
        }

        if ($request->has('documents')) {
            foreach ($request->post('documents') as $index => $document) {
                if (isset($request['documents'][$index]['documentFile'])) {
                    $CurrentFile = $request['documents'][$index]['documentFile'];
                    $FileStoragePath = '/public/user-documents/';
                    $Extension = $CurrentFile->extension();
                    $file = $CurrentFile->getClientOriginalName();
                    $FileName = pathinfo($file, PATHINFO_FILENAME);
                    $FileName = $FileName . '-' . date('Y-m-d') . mt_rand(100, 1000) . '.' . $Extension;
                    $result = $CurrentFile->storeAs($FileStoragePath, $FileName);
                    $FileNames[] = $FileName;
                    $DocumentTypes[] = $document['documentName'];
                    $DocumentNames[] = $document['documentNameOthers'];
                    $DocumentNumbers[] = $document['documentNumbers'];
                }
            }
        }

        $Affected1 = DB::table('users')
            ->where('id', '=', $request->post('id'))
            ->update([
                'email' => $Email,
                'role_id' => $Role,
                'updated_at' => Carbon::now()
            ]);

        $Affected2 = null;
        if ($UserRole == 5) {
            $Affected2 = DB::table('user_details')
                ->where('user_id', '=', $request->post('id'))
                ->update([
                    'firstName' => $FirstName,
                    'middleName' => $MiddleName,
                    'lastName' => $LastName,
                    'dob' => $Dob,
                    'gender' => $Gender,
                    'managerLocations' => $Role == 3 ? ($ManagerLocations != null ? implode(',', $ManagerLocations) : null) : null,
                    'coachLevels' => $Role == 4 ? ($CoachLevels != null ? implode(',', $CoachLevels) : null) : null,
                    'coachCategories' => $Role == 4 ? ($CoachCategories != null ? implode(',', $CoachCategories) : null) : null,
                    'coachLocations' => $Role == 4 ? ($CoachLocations != null ? implode(',', $CoachLocations) : null) : null,
                    'athletesParent' => $Role == 6 ? $AthletesParent : null,
                    'parent_profession' => $Role == 5 ? $ParentProfession : null,
                    'athletesTrainingDays' => $Role == 6 ? $AthletesTrainingDays : null,
                    'athletesDoctorName' => $Role == 6 ? $AthletesDoctorName : null,
                    'athletesDoctorPhoneNumber' => $Role == 6 ? $AthletesDoctorPhoneNumber : null,
                    'athletesInsuranceName' => $Role == 6 ? $AthletesInsuranceName : null,
                    'athletesPolicyNumber' => $Role == 6 ? $AthletesPolicyNumber : null,
                    'athletesHeightFt' => $Role == 6 ? $AthletesHeightFt : null,
                    'athletesHeightInches' => $Role == 6 ? $AthletesHeightInches : null,
                    'athletesWeight' => $Role == 6 ? $AthletesWeight : null,
                    'athletesAllergies' => $Role == 6 ? $AthletesAllergies : null,
                    'athletesRelationship' => $Role == 6 ? $AthletesRelationship : null,
                    'phone1' => $Phone1,
                    'phone2' => $Phone2,
                    'socialMedia' => $SocialMedia,
                    'socialMedia2' => $SocialMedia2,
                    'socialMedia3' => $SocialMedia3,
                    'street' => $Street,
                    'city' => $City,
                    'state' => $State,
                    'zipcode' => $ZipCode,
                    'profile_pic' => $ProfilePic,
                    'updated_at' => Carbon::now()
                ]);
        } else {
            $Affected2 = DB::table('user_details')
                ->where('user_id', '=', $request->post('id'))
                ->update([
                    'firstName' => $FirstName,
                    'middleName' => $MiddleName,
                    'lastName' => $LastName,
                    'dob' => $Dob,
                    'gender' => $Gender,
                    'managerLocations' => $Role == 3 ? ($ManagerLocations != null ? implode(',', $ManagerLocations) : null) : null,
                    'coachLevels' => $Role == 4 ? ($CoachLevels != null ? implode(',', $CoachLevels) : null) : null,
                    'coachCategories' => $Role == 4 ? ($CoachCategories != null ? implode(',', $CoachCategories) : null) : null,
                    'coachLocations' => $Role == 4 ? ($CoachLocations != null ? implode(',', $CoachLocations) : null) : null,
                    'athletesParent' => $Role == 6 ? $AthletesParent : null,
                    'athletesLevel' => $Role == 6 ? $AthletesLevel : null,
                    'athletesCategory' => $Role == 6 ? $AthletesCategory : null,
                    'parent_profession' => $Role == 5 ? $ParentProfession : null,
                    'athletesTrainingDays' => $Role == 6 ? $AthletesTrainingDays : null,
                    'athletesDoctorName' => $Role == 6 ? $AthletesDoctorName : null,
                    'athletesDoctorPhoneNumber' => $Role == 6 ? $AthletesDoctorPhoneNumber : null,
                    'athletesInsuranceName' => $Role == 6 ? $AthletesInsuranceName : null,
                    'athletesPolicyNumber' => $Role == 6 ? $AthletesPolicyNumber : null,
                    'athletesHeightFt' => $Role == 6 ? $AthletesHeightFt : null,
                    'athletesHeightInches' => $Role == 6 ? $AthletesHeightInches : null,
                    'athletesWeight' => $Role == 6 ? $AthletesWeight : null,
                    'athletesAllergies' => $Role == 6 ? $AthletesAllergies : null,
                    'athletesRelationship' => $Role == 6 ? $AthletesRelationship : null,
                    'athletesPosition' => $Role == 6 ? $AthletesPosition : null,
                    'phone1' => $Phone1,
                    'phone2' => $Phone2,
                    'socialMedia' => $SocialMedia,
                    'socialMedia2' => $SocialMedia2,
                    'socialMedia3' => $SocialMedia3,
                    'street' => $Street,
                    'city' => $City,
                    'state' => $State,
                    'zipcode' => $ZipCode,
                    'profile_pic' => $ProfilePic,
                    'updated_at' => Carbon::now()
                ]);
        }

        $Affected3 = null;
        foreach ($FileNames as $index => $fileName) {
            $Affected3 = UserDocuments::create([
                'user_id' => $request->post('id'),
                'document_type' => $DocumentTypes[$index],
                'document_name' => $DocumentNames[$index],
                'document_number' => $DocumentNumbers[$index],
                'document' => $fileName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        if ($Affected1 && $Affected2) {
            DB::commit();
            return redirect()->route('users')->with('success', 'User has been updated successfully');
        } else {
            DB::rollback();
            return redirect()->route('users')->with('error', 'Error! An unhandled exception occurred');
        }
    }

    function fetchCategory(Request $request)
    {
        $Dob = Carbon::parse($request->post('Dob'))->age;
        $Category = DB::table('categories')
            ->where('deleted_at', '=', null)
            ->whereRaw(DB::raw('start_age <= ? AND end_age >= ?'), array($Dob, $Dob))
            ->get();
        if (sizeof($Category) > 0) {
            echo $Category[0]->id;
        } else {
            echo 0;
        }
        exit();
    }

    function fetchParentAddressInfo(Request $request)
    {
        $ParentId = $request['ParentId'];
        $UserDetails = DB::table('user_details')
            ->where('user_id', $ParentId)
            ->get();

        $Phone1 = $UserDetails[0]->phone1;
        $State = $UserDetails[0]->state;
        $City = $UserDetails[0]->city;
        $Street = $UserDetails[0]->street;
        $ZipCode = $UserDetails[0]->zipcode;

        $States = DB::table('states')->get();
        $Cities = DB::table('locations')
            ->where('state_name', '=', $State)
            ->orderBy("city", "ASC")
            ->get()
            ->unique("city");

        $state_options = '<option value="">Select</option>';
        foreach ($States as $key => $value) {
            if ($State == $value->name) {
                $state_options .= '<option value="' . $value->name . '" selected>' . $value->name . '</option>';
            } else {
                $state_options .= '<option value="' . $value->name . '">' . $value->name . '</option>';
            }
        }

        $city_options = '<option value="">Select</option>';
        foreach ($Cities as $key => $value) {
            if ($City == $value->city) {
                $city_options .= '<option value="' . $value->city . '" selected>' . $value->city . '</option>';
            } else {
                $state_options .= '<option value="' . $value->city . '">' . $value->city . '</option>';
            }
        }

        $data = array();
        $data['phone1'] = $Phone1;
        $data['state'] = json_encode($state_options);
        $data['city'] = json_encode($city_options);
        $data['street'] = $Street;
        $data['zipcode'] = $ZipCode;
        echo json_encode($data);
    }


    function updatePassword(Request $request)
    {

        $UserId = $request->post('id');
        $Password = $request->post('newPassword');
        DB::beginTransaction();
        DB::table('users')->where('id', '=', $UserId)->update([
            'password' => Hash::make($Password),
            'default_pass_status' => 1,
            'updated_at' => Carbon::now()
        ]);
        DB::commit();
        return redirect()->back()->with('success', 'User password has been updated successfully');
    }

    // Power - Start
    public function powerType($UserId, $PowerType)
    {
        $page = "users";
        $UserRole = Session::get("user_role");
        $Categories = DB::table('categories')
            ->where('deleted_at', '=', null)
            ->get();
        $Levels = DB::table('levels')
            ->where('deleted_at', '=', null)
            ->get();
        $Locations = DB::table('player_locations')
            ->where('status', '=', 1)
            ->where('deleted_at', '=', null)
            ->get();
        $UserPowers = DB::table('user_powers')
            ->where('user_id', '=', $UserId)
            ->get();

        if ($PowerType == 1) {
            return view('dashboard.users.power.user', compact('page', 'UserRole', 'UserId', 'Categories', 'Levels', 'Locations', 'UserPowers'));
        } elseif ($PowerType == 2) {
            return view('dashboard.users.power.feature', compact('page', 'UserRole', 'UserId', 'Categories', 'Levels', 'Locations', 'UserPowers'));
        }
    }

    public function userPowerUserUpdate(Request $request)
    {
        $UserId = $request['user_id'];
        $ParentLocation = implode(",", $request['parent_location']);
        $ParentLevel = implode(",", $request['parent_level']);
        $ParentCategory = implode(",", $request['parent_category']);
        $PlayerLocation = implode(",", $request['player_location']);
        $PlayerLevel = implode(",", $request['player_level']);
        $PlayerCategory = implode(",", $request['player_category']);
        $CoachLocation = implode(",", $request['coach_location']);
        $CoachLevel = implode(",", $request['coach_level']);
        $CoachCategory = implode(",", $request['coach_category']);

        DB::beginTransaction();
        $Affected1 = DB::table('user_powers')
            ->where('user_id', '=', $UserId)
            ->update([
                'parent_location' => $ParentLocation,
                'parent_level' => $ParentLevel,
                'parent_category' => $ParentCategory,
                'player_location' => $PlayerLocation,
                'player_level' => $PlayerLevel,
                'player_category' => $PlayerCategory,
                'coach_location' => $CoachLocation,
                'coach_level' => $CoachLevel,
                'coach_category' => $CoachCategory,
                'updated_at' => Carbon::now()
            ]);

        if ($Affected1) {
            DB::commit();
            return redirect()->route('users')->with('success', 'User power has been updated successfully');
        } else {
            DB::rollback();
            return redirect()->route('users')->with('error', 'Error! An unhandled exception occurred');
        }
    }

    public function userPowerFeatureUpdate(Request $request)
    {
        $UserId = $request['UserId'];
        $Feature = $request['feature'];
        $Status = 1;
        if ($request['status'] == "false") {
            $Status = 0;
        }

        DB::beginTransaction();
        $Affected1 = DB::table('user_powers')
            ->where('user_id', '=', $UserId)
            ->update([
                $Feature => $Status,
                'updated_at' => Carbon::now()
            ]);

        if ($Affected1) {
            DB::commit();
            echo "Success";
        } else {
            DB::rollback();
            echo "Failed";
        }
    }

    // Uset Document Verification
    public function updateDocumentVerificationStatus(Request $request)
    {
        $DocumentId = $request['DocumentId'];
        $Status = $request['Status'];
        $Comment = $request['Comment'];
        $UserId = $request['UserId'];

        // Document Details
        $DocumentDetails = DB::table('user_documents')
            ->where('id', $DocumentId)
            ->get();

        DB::beginTransaction();
        $Affected = DB::table('user_documents')
            ->where('id', '=', $DocumentId)
            ->update([
                'status' => $Status,
                'updated_at' => Carbon::now()
            ]);
        $message = "";
        if ($Status == 1) {
            $message = $DocumentDetails[0]->document_type . " document has been approved";
        } elseif ($Status == 2) {
            $message = $DocumentDetails[0]->document_type . " document has been rejected";
        }

        // document verification status
        $Affected1 = UserActivity::create([
            'user_id' => $UserId,
            'sender_id' => Auth::id(),
            'message' => $message,
            'created_at' => Carbon::now(),
        ]);

        // document verification comment
        $Affected2 = UserActivity::create([
            'user_id' => $UserId,
            'sender_id' => Auth::id(),
            'message' => $Comment,
            'created_at' => Carbon::now(),
        ]);

        if ($Affected && $Affected1 && $Affected2) {
            DB::commit();
            echo "Success";
        } else {
            DB::rollback();
            echo "Failed";
        }
    }
}
