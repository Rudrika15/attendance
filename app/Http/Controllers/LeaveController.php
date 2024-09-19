<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        // Get the input from the form
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchUser = $request->input('search_user');
    
        // Query the users and filter by username if provided
        $users = User::when($searchUser, function ($query, $searchUser) {
            return $query->where('name', 'like', '%' . $searchUser . '%');
        })->whereHas('roles',function($q){
            $q->where('name','!=','Admin');
        })->get();
    
        // Query the leaves and apply filters for start and end date if provided
        $leaves = Leave::where('status', 'Approved')
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('startDate', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('endDate', '<=', $endDate);
            })
            ->get();
    
        // Calculate total leaves per user
        $userLeaveCounts = [];
    
        foreach ($users as $user) {
            $userLeaves = $leaves->where('userId', $user->id);
            $totalLeave = 0;
    
            foreach ($userLeaves as $leave) {
                if ($leave->startDate == $leave->endDate) {
                    $totalLeave += 1;
                } else {
                    $totalLeave += Carbon::parse($leave->startDate)->diffInDays(Carbon::parse($leave->endDate)) + 1;
                }
            }
    
            $userLeaveCounts[$user->id] = $totalLeave;
        }
    
        // Pass users and user-wise total leave counts to the view
        return view('report.leave', compact('users', 'userLeaveCounts'));
    }
    



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        //
    }
}
