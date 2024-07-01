<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'Admin');
        })->count();

        $leaveApplications = Leave::where('status', 'Pending')->paginate(10);
        // return Carbon::today()->toDateString();
        $todayOnLeave = Leave::where('status', 'Approved')
            ->whereDate('startDate', '<=', Carbon::today()->toDateString())
            ->whereDate('endDate', '>=', Carbon::today()->toDateString())
            ->get();

        return view('home', compact('users', 'leaveApplications', 'todayOnLeave'));
    }

    public function leaveApproved($id)
    {
        $leave = Leave::where('id', $id)->update(['status' => 'Approved']);
        return redirect()->back();
    }
}
