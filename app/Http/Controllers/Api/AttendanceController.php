<?php


namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Notification;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class AttendanceController extends Controller

{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'timekey' => 'required|in:checkin,checkout,on_break,off_break',
        ], [
            'timekey.required' => 'The timekey field is required.',
            'timekey.in' => 'The timekey must be one of the following values: checkin, checkout, on_break, off_break.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors()
            ], 422);
        }

        $timekey = $request->timekey;
        $time = Carbon::now('Asia/Kolkata')->format('H:i:s');
        $user = Auth::user();
        $userId = $user->id;

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', now()->toDateString())
            ->first();

        $total_break_time = null;

        if ($attendance) {
            switch ($timekey) {
                case 'checkin':
                    if ($attendance->checkin) {
                        return response()->json([
                            'message' => 'You have already checked in.',
                        ], 422);
                    }
                    $attendance->checkin = $time;
                    break;
                case 'on_break':
                    if (!$attendance->checkin) {
                        return response()->json([
                            'message' => 'Cannot enter on_break without checkin first.',
                        ], 422);
                    }
                    if ($attendance->on_break) {
                        return response()->json([
                            'message' => 'You are already on break.',
                        ], 422);
                    }
                    $attendance->on_break = $time;
                    break;
                case 'off_break':
                    if (!$attendance->on_break) {
                        return response()->json([
                            'message' => 'Cannot enter off_break without on_break first.',
                        ], 422);
                    }
                    if ($attendance->off_break) {
                        return response()->json([
                            'message' => 'You are already off break.',
                        ], 422);
                    }
                    $attendance->off_break = $time;

                    // Calculate break time after `off_break`
                    $onbreak_seconds = strtotime($attendance->on_break);
                    $offbreak_seconds = strtotime($time);
                    $break_seconds = $offbreak_seconds - $onbreak_seconds;

                    // Format the break time in "x hr y min"
                    $total_break_time = $this->formatTime($break_seconds);
                    break;
                case 'checkout':
                    if (!$attendance->off_break) {
                        return response()->json([
                            'message' => 'Cannot enter checkout without off_break first.',
                        ], 422);
                    }
                    if ($attendance->checkout) {
                        return response()->json([
                            'message' => 'You have already checked out.',
                        ], 422);
                    }

                    $checkout_seconds = strtotime($time);
                    $checkin_seconds = strtotime($attendance->checkin);
                    $onbreak_seconds = strtotime($attendance->on_break);
                    $offbreak_seconds = strtotime($attendance->off_break);

                    // Calculate total working seconds and break time seconds
                    $total_working_seconds = $checkout_seconds - $checkin_seconds - ($offbreak_seconds - $onbreak_seconds);
                    $total_break_seconds = $offbreak_seconds - $onbreak_seconds;

                    // Format working hours and break time
                    $total_working_time = $this->formatTime($total_working_seconds);
                    $total_break_time = $this->formatTime($total_break_seconds);

                    $attendance->total_hours = gmdate('H:i:s', $total_working_seconds);
                    $attendance->checkout = $time;
                    break;
                default:
                    break;
            }

            $attendance->$timekey = $time;
            $attendance->save();
        } else {
            $attendanceData = [
                'date' => now()->toDateString(),
                'user_id' => $userId,
                'checkin' => $timekey === 'checkin' ? $time : null,
                'checkout' => $timekey === 'checkout' ? $time : null,
                'on_break' => $timekey === 'on_break' ? $time : null,
                'off_break' => $timekey === 'off_break' ? $time : null,
            ];

            $attendance = Attendance::create($attendanceData);
            $total_break_time = null;
        }

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'attendance' => $attendance,
            'working_hours' => $total_working_time ?? 'N/A',
            'break_time' => $total_break_time ?? 'N/A',
        ], 201);
    }

    /**
     * Helper function to format time in "x hr y min" format
     */
    private function formatTime($seconds)
{
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);

    $formatted = '';

    // Handle hours
    if ($hours > 0) {
        $formatted .= $hours === 1 ? '1 hour ' : "$hours hours ";
    }

    // Handle minutes
    if ($minutes > 0) {
        $formatted .= $minutes === 1 ? '1 minute' : "$minutes minutes";
    }

    // Return '0 minutes' if neither hours nor minutes exist
    return $formatted ? trim($formatted) : '0 minutes';
}






    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'timekey' => 'required|in:checkin,checkout,on_break,off_break',
    //     ], [
    //         'timekey.required' => 'The timekey field is required.',
    //         'timekey.in' => 'The timekey must be one of the following values: checkin, checkout, on_break, off_break.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Validation errors occurred.',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $timekey = $request->timekey;
    //     $time = Carbon::now('Asia/Kolkata')->format('H:i:s');
    //     $userId = auth()->user()->id;
    //     $userName = auth()->user()->name; // Get the user's name for the notification

    //     $attendance = Attendance::where('user_id', $userId)
    //         ->whereDate('date', now()->toDateString())
    //         ->first();

    //     // Initialize Firebase


    //     // Define dynamic notification message
    //     // $notificationBody = '';
    //     // switch ($timekey) {
    //     //     case 'checkin':
    //     //         $notificationBody = "$userName has just checked in";
    //     //         break;
    //     //     case 'on_break':
    //     //         $notificationBody = "$userName is taking a break";
    //     //         break;
    //     //     case 'off_break':
    //     //         $notificationBody = "$userName is back from break";
    //     //         break;
    //     //     case 'checkout':
    //     //         $notificationBody = "$userName has checked out";
    //     //         break;
    //     //     default:
    //     //         $notificationBody = "$userName has updated their status";
    //     //         break;
    //     // }

    //     // $users = User::all();
    //     // // Send the notification
    //     // $this->firebaseService->sendNotification($users->token, $notificationBody, $notificationBody);

    //     if ($attendance) {
    //         switch ($timekey) {
    //             case 'checkin':
    //                 if ($attendance->checkin) {
    //                     return response()->json([
    //                         'message' => 'You have already checked in.',
    //                     ], 422);
    //                 }
    //                 $attendance->checkin = $time;
    //                 break;
    //             case 'on_break':
    //                 if (!$attendance->checkin) {
    //                     return response()->json([
    //                         'message' => 'Cannot enter on_break without checkin first.',
    //                     ], 422);
    //                 }
    //                 if ($attendance->on_break) {
    //                     return response()->json([
    //                         'message' => 'You are already on break.',
    //                     ], 422);
    //                 }
    //                 $attendance->on_break = $time;
    //                 break;
    //             case 'off_break':
    //                 if (!$attendance->on_break) {
    //                     return response()->json([
    //                         'message' => 'Cannot enter off_break without on_break first.',
    //                     ], 422);
    //                 }
    //                 if ($attendance->off_break) {
    //                     return response()->json([
    //                         'message' => 'You are already off break.',
    //                     ], 422);
    //                 }
    //                 $attendance->off_break = $time;
    //                 break;
    //             case 'checkout':
    //                 if (!$attendance->off_break) {
    //                     return response()->json([
    //                         'message' => 'Cannot enter checkout without off_break first.',
    //                     ], 422);
    //                 }
    //                 if ($attendance->checkout) {
    //                     return response()->json([
    //                         'message' => 'You have already checked out.',
    //                     ], 422);
    //                 }

    //                 $checkout_seconds = strtotime($time);
    //                 $checkin_seconds = strtotime($attendance->checkin);
    //                 $onbreak_seconds = strtotime($attendance->on_break);
    //                 $offbreak_seconds = strtotime($attendance->off_break);

    //                 $total_working_seconds = $checkout_seconds - $checkin_seconds - ($offbreak_seconds - $onbreak_seconds);

    //                 $total_hours = gmdate('H:i:s', $total_working_seconds);

    //                 $attendance->total_hours = $total_hours;

    //                 $attendance->checkout = $time;
    //                 break;
    //             default:
    //                 break;
    //         }

    //         $attendance->$timekey = $time;
    //         $attendance->save();
    //     } else {
    //         $attendanceData = [
    //             'date' => now()->toDateString(),
    //             'user_id' => $userId,
    //             'checkin' => $timekey === 'checkin' ? $time : null,
    //             'checkout' => $timekey === 'checkout' ? $time : null,
    //             'on_break' => $timekey === 'on_break' ? $time : null,
    //             'off_break' => $timekey === 'off_break' ? $time : null,
    //         ];

    //         $attendance = Attendance::create($attendanceData);
    //     }

    //     // Send the notification
    //     try {
    //         $messaging->send($message);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'message' => 'Notification could not be sent.',
    //             'error' => $th->getMessage()
    //         ], 500);
    //     }

    //     return response()->json(
    //         [
    //             'message' => 'Attendance recorded successfully',
    //             'attendance' => $attendance
    //         ],
    //         201
    //     );
    // }




    public function todayattendance(Request $request)
    {
        $userId = Auth::user()->id;
        $currentDate = now()->toDateString();

        $data = Attendance::where('user_id', '=', $userId)
            ->whereDate('date', $currentDate)
            ->get();

        return response()->json([
            'message' => 'Get Attendance recorded successfully',
            'attendance' => $data
        ], 201);
    }


    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        //  return $attendance = Attendance::all();
        $data = Attendance::where('user_id', '=', $userId)->get();

        //    return $data->checkin;
        return response()->json([
            'message' => 'Get Attendance recorded successfully',
            'User' => $data
        ], 201);
    }

    public function deleteAttendance(Request $request)
    {

        $userId = Auth::user()->id;
        $date = $request->date;

        $attendance = Attendance::where('date', $date)->first();
        $attendance->delete();

        return response()->json([
            'message' => 'Deleted successfuly',
            'data' => $attendance
        ], 200);
    }

    public function leaveRequest(Request $request)
    {
        $rules = [
            'startDate' => 'required',
            'endDate' => 'required',
            'reason' => 'required',
            'leaveType' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }

        $userId = Auth::user()->id;
        $leave = new Leave();
        $leave->userId = $userId;
        $leave->startDate = $request->startDate;
        $leave->endDate = $request->endDate;
        $leave->reason = $request->reason;
        $leave->leaveType = $request->leaveType;
        $leave->save();

        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->get();

        // Firebase initialization
        $serviceAccountPath = storage_path('attendance.json');
        $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        $messaging = $factory->createMessaging();

        // Send Firebase notification directly in the function
        foreach ($admins as $admin) {
            $token = $admin->token;
            if (!$token) {
                // Log or return a message if token is missing
                Log::error("Admin {$admin->name} does not have a valid Firebase token.");
                continue;
            }

            try {
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification(FirebaseNotification::create('Leave Request', Auth::user()->name . ' has requested for leave.'));
                $messaging->send($message);
                // return "Notification sent successfully";
            } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                Log::error("Firebase token not found for admin: {$admin->name}, error: " . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Leave request sent successfully',
            'data' => $leave
        ], 201);
    }


    public function myLeaves()
    {
        $userId = Auth::user()->id;
        $leaves = Leave::where('userId', $userId)->get();
        return response()->json([
            'message' => 'Get Leaves successfully',
            'data' => $leaves
        ], 200);
    }


    public function getNotifications()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $notifications = Notification::where('created_at', '>=', $sevenDaysAgo)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Get Notifications successfully',
            'data' => $notifications
        ], 200);
    }
    public function addNotification(Request $request)
    {
        $rules = [
            'title' => 'required',
            'detail' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }

        $notification = new Notification();
        $notification->title = $request->title;
        $notification->detail = $request->detail;
        $notification->save();

        $users = User::where('token', '!=', Null)->get();

        // Send firebase the notification
        foreach ($users as $user) {
            $this->firebaseService->sendNotification($user->token, $request->title, $request->detail);
        }

        return response()->json([
            'message' => 'Get Notifications successfully',
            'data' => $notification
        ], 200);
    }
}
