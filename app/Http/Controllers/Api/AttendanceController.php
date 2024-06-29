<?php


namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller

{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'timekey' => 'required|in:checkin,checkout,on_break,off_break',
            'time' => 'required|date_format:H:i:s',
        ], [
            'timekey.required' => 'The timekey field is required.',
            'timekey.in' => 'The timekey must be one of the following values: checkin, checkout, on_break, off_break.',
            'time.required' => 'The time field is required.',
            'time.date_format' => 'The time must be in the format HH:MM:SS.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors()
            ], 422);
        }

        $timekey = $request->timekey;
        $time = $request->time;
        $userId = auth()->user()->id;

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
                    $checkin_seconds = strtotime($attendance->checkin ?? "00:00:00");
                    $onbreak_seconds = strtotime($attendance->on_break ?? "00:00:00");
                    $offbreak_seconds = strtotime($attendance->off_break ?? "00:00:00");

                    $checkinout_duration = $checkout_seconds - $checkin_seconds;
                    $onoffbreak_duration = $offbreak_seconds - $onbreak_seconds;

                    $total_hours = ($checkinout_duration - $onoffbreak_duration) / 3600;

                    $total_formatted = gmdate('H:i:s', $total_hours * 3600);

                    $attendance->total_hours = $total_formatted;

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


    public function index(Request $request )
    {
         $userId = Auth::user()->id;
        //  return $attendance = Attendance::all();
        $data = Attendance::where( 'user_id' , '=' ,$userId   )->get();

    //    return $data->checkin;
       return response()->json([
        'message' => 'Get Attendance recorded successfully',
        'User' => $data
    ], 201);
    }
}
