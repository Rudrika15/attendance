<?php


namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Models\Attendance;
use App\Models\Leave;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        $userId = Auth::user()->id;

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', now()->toDateString())
            ->first();

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

                    $total_working_seconds = $checkout_seconds - $checkin_seconds - ($offbreak_seconds - $onbreak_seconds);

                    $total_hours = gmdate('H:i:s', $total_working_seconds);

                    $attendance->total_hours = $total_hours;

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
        }

        return response()->json([
            'message' => 'Attendance recorded successfully',
            'attendance' => $attendance
        ], 201);
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
      
        // Send firebase the notification
        foreach ($admins as $users) {
            $this->firebaseService->sendNotification($users->token, 'Leave Request', Auth::user()->name . ' has requested for leave.');
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
}
