<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\BirthdayWish;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB as FacadesDB;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $firebaseService;

    function __construct(FirebaseService $firebaseService)
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
        $this->firebaseService = $firebaseService;
    }
    public function index(Request $request): View
    {
        $data = User::latest()->paginate(20);



        return view('users.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:users,phone|digits:10',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|digits:10',
            'email' => 'required|email|unique:users,email,' . $id,
            'birthdate' => 'required',
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
    public function wish(Request $request)
    {
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->format('m');
        $currentDay = $currentDate->format('d');

        // Fetch all users whose birthday matches today's date
        $usersWithBirthday = User::whereMonth('birthdate', $currentMonth)
            ->whereDay('birthdate', $currentDay)
            ->get();

        if ($usersWithBirthday->isEmpty()) {
            return response()->json([
                'message' => 'No birthday found for today',
                'data' => []
            ], 200);
        }

        // Collect tokens of users with birthdays
        $birthdayUserTokens = $usersWithBirthday->pluck('token')->toArray();

        $serviceAccountPath = storage_path('attendance.json');
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $messaging = $factory->createMessaging();
        $title = '🎉 It\'s a Birthday Celebration! 🎉';

        // Notify each user about their own birthday
        foreach ($usersWithBirthday as $userData) {
            if ($userData->token) {
                $body = "Today is your birthday, {$userData->name}! Wishing you a fantastic day filled with joy and happiness. 🎂🎁🥳";

                $message = CloudMessage::withTarget('token', $userData->token)
                    ->withNotification(Notification::create($title, $body));

                // Send the notification
                $messaging->send($message);
            }
        }

        // Fetch all users who are not the ones with birthdays
        $allUsers = User::whereNotNull('token')
            // ->whereNotIn('token', $birthdayUserTokens)
            ->get();

        // Notify all users about the birthdays of others
        foreach ($usersWithBirthday as $userData) {
            
            foreach ($allUsers as $otherUser) {
                if ($otherUser->token) {
                    
                    
                 $wish = "Today is {$userData->name}'s birthday! Let's join in the celebration and wish them a fantastic day filled with joy and happiness. 🎂🎁🥳";

                     $message = CloudMessage::withTarget('token', $otherUser->token)
                        ->withNotification(Notification::create($title, $wish));


                    $messaging->send($message);
                }
            }
        }


        return response()->json([
            'message' => 'Wish sent successfully',
            'data' => $usersWithBirthday
        ], 200);
    }


    public function privacyPolicy()
    {
        return view('extra.privacy-policy');
    }
}
