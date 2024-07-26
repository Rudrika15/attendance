<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'Admin');
        })->count();

        // Leave applications with status Pending
        $leaveApplications = Leave::where('status', 'Pending')->paginate(10);

        // Today's approved leave applications
        $todayOnLeave = Leave::where('status', 'Approved')
            ->whereDate('startDate', '<=', Carbon::today()->toDateString())
            ->whereDate('endDate', '>=', Carbon::today()->toDateString())
            ->get();

        // Daily attendance with optional date filtering
        $date = $request->input('date', Carbon::today()->toDateString());
        $dailyAttendance = Attendance::whereDate('date', $date)->get();
        return view('home', compact('users', 'leaveApplications', 'todayOnLeave', 'dailyAttendance'));
    }

    public function leaveApproved($id)
    {
        $leave = Leave::where('id', $id)->update(['status' => 'Approved']);
        return redirect()->back();
    }
}
