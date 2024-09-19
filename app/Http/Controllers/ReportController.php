<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function display(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $user = $request->query('search_user');

        $query = User::query();

        if ($start_date && $end_date) {
            $query->whereHas('attendances', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            })->with(['attendances' => function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            }]);
        } else {
            $query->with('attendances');
        }

        if ($user) {
            $query->where('name', 'like', '%' . $user . '%');
        }

        $data = $query->get()->map(function($user) {
            $user->total_hours = $user->attendances->sum(function($attendance) {
                return (float) $attendance->total_hours;
            });
            return $user;
        });
          $leave = Leave::where('status', 'Approved')->get();


        return view('report.report', compact('data','leave'));
    }
}
