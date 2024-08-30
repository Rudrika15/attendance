<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Notification as ModelsNotification;
use App\Models\User;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class HomeController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    public function index(Request $request)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'Admin');
        })->count();

        // Leave applications with status Pending
        $leaveApplications = Leave::where('status', 'Pending')->orderBy('id', 'desc')->paginate(10);

        // Today's approved leave applications
        $todayOnLeave = Leave::where('status', 'Approved')
            ->whereDate('startDate', '<=', Carbon::today()->toDateString())
            ->whereDate('endDate', '>=', Carbon::today()->toDateString())
            ->get();

        // Daily attendance with optional date filtering
        $date = $request->input('date', Carbon::today()->toDateString());
        $dailyAttendance = Attendance::whereDate('date', $date)->get();

        // birthday reminders
        $today = Carbon::today()->toDateString();
        $birthday = User::whereDate('birthdate', '=', $today)->get();
        return view('home', compact('users', 'leaveApplications', 'todayOnLeave', 'dailyAttendance', 'birthday'));
    }

    public function leaveApproved($id)
    {
        $leave = Leave::where('id', $id)->update(['status' => 'Approved']);
        return redirect()->back();
    }


    public function notification()
    {
        $notifications = ModelsNotification::orderBy('id', 'desc')->get();
        return view('notification.create',compact('notifications'));
    }

    public function addNotification(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'detail' => 'required',
        ]);
        
        $notification = new ModelsNotification();
        $notification->title = $request->title;
        $notification->detail = $request->detail;
        $notification->save();
    
        $users = User::where('token', '!=', null)->get();
    
        $title = $request->title;
        $body = $request->detail;
    
        $serviceAccountPath = storage_path('attendance.json');
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $messaging = $factory->createMessaging();
    
        foreach ($users as $user) {
            if ($user->token) { 
                $message = CloudMessage::withTarget('token', $user->token)
                    ->withNotification(Notification::create($title, $body));
                
                try {
                    $messaging->send($message);
                } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                    Log::error('Token not found: ' . $user->token);
                } catch (\Kreait\Firebase\Exception\Messaging\InvalidArgument $e) {
                    Log::error('Invalid argument error with token: ' . $user->token);
                } catch (\Exception $e) {
                    Log::error('General error sending to token: ' . $user->token . '. Error: ' . $e->getMessage());
                }
            }
        }
    
        return redirect()->back()->with('success', 'Notification sent successfully!');
    }


    public function deleteNotification($id)
    {
        $notification = ModelsNotification::find($id);
        $notification->delete();
        return redirect()->back()->with('success', 'Notification deleted successfully!');
    }
    
}
