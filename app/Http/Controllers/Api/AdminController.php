<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    public function dailyAttendance(Request $request)
    {
        // Validate the date parameter to ensure it's in a correct format (YYYY-MM-DD)
        $validated = $request->validate([
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        // Use the provided date or default to today
        $date = $validated['date'] ?? Carbon::today()->toDateString();

        // Fetch attendance records for the specified date
        $dailyAttendance = Attendance::with('user')->whereDate('date', $date)->get();

        $userAttendance = [];

        foreach ($dailyAttendance as $attendance) {
            $userId = $attendance->user_id;

            if (!isset($userAttendance[$userId])) {
                $userAttendance[$userId] = [
                    'user' => $attendance->user,
                    'attendanceData' => [],
                    'totalBreakMinutes' => 0,
                    'totalWorkingMinutes' => 0,
                ];
            }

            $checkin = Carbon::parse($attendance->checkin);
            $checkout = Carbon::parse($attendance->checkout);
            $onBreak = Carbon::parse($attendance->on_break);
            $offBreak = Carbon::parse($attendance->off_break);

            // Adjust for cases where offBreak or checkout is on the next day
            if ($offBreak->lt($onBreak)) {
                $onBreak = $onBreak->copy()->addDay();
            }

            if ($checkout->lt($checkin)) {
                $checkout = $checkout->copy()->addDay();
            }

            // Calculate break time in minutes
            $breakDuration = $onBreak->diffInMinutes($offBreak);
            $userAttendance[$userId]['totalBreakMinutes'] += $breakDuration;

            // Calculate total working time in minutes
            $workingDuration = $checkin->diffInMinutes($checkout) - $breakDuration;
            $userAttendance[$userId]['totalWorkingMinutes'] += $workingDuration;

            // Include the current attendance data
            $userAttendance[$userId]['attendanceData'][] = [
                'id' => $attendance->id,
                'date' => $attendance->date,
                'checkin' => $attendance->checkin,
                'checkout' => $attendance->checkout,
                'on_break' => $attendance->on_break,
                'off_break' => $attendance->off_break,
                'total_hours' => $attendance->total_hours,
            ];
        }

        // Format output for each user
        foreach ($userAttendance as &$attendance) {
            $breakMinutes = $attendance['totalBreakMinutes'];
            $workingMinutes = $attendance['totalWorkingMinutes'];

            $breakHours = intdiv($breakMinutes, 60);
            $breakMinutes = $breakMinutes % 60;
            $workingHours = intdiv($workingMinutes, 60);
            $workingMinutes = $workingMinutes % 60;

            $attendance['totalBreakTime'] = $breakHours . ' hours and ' . $breakMinutes . ' min';
            $attendance['totalWorkingHours'] = $workingHours . ' hours and ' . $workingMinutes . ' min';

            // Remove the totalBreakMinutes and totalWorkingMinutes keys
            unset($attendance['totalBreakMinutes']);
            unset($attendance['totalWorkingMinutes']);
        }

        // Return the formatted response
        return response()->json([
            'status' => true,
            'message' => 'Daily attendance fetched successfully',
            'data' => array_values($userAttendance),  // Reset keys to sequential
        ], 200);
    }

    public function leaveApplication(Request $request)
    {
        $leaveApplications = Leave::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json([
            'status' => true,
            'message' => 'Leave applications fetched successfully',
            'data' => $leaveApplications,
        ], 200);
    }

    public function approveLeave(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Approved,Rejected',
        ], [
            'status.in' => 'status must be Approved or Rejected',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors()
            ], 200);
        }
        $leave = Leave::find($id);
        $leave->status = $request->status;
        $leave->save();

        // Send firebase the notification
        $name = $leave->user->name;
        $token = $leave->user->token;
        $this->firebaseService->sendNotification(
            $token,
            'Leave Application Status Update',
            'Dear ' . $name . ', your leave application has been ' . $request->status . ' by HR. Thank you for your patience.'
        );

        return response()->json([
            'status' => true,
            'message' => 'Leave application approved successfully',
            'data' => $leave,
        ], 200);
    }

    public function cancelLeave(Request $request, $id)
    {
        $findAuthAdmin = User::whereHas('roles', function ($q) {
            $q->where('name', 'Admin');
        })->where('id', Auth::user()->id)->first();
        if (!$findAuthAdmin) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to perform this action.',
            ], 200);
        } else {
            $leave = Leave::find($id);
            $leave->delete();

            return response()->json([
                'status' => true,
                'message' => 'Leave application canceled successfully',
                'data' => $leave,
            ], 200);
        }
    }
}
