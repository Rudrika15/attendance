@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 pb-5">
                <div class="card">
                    <div class="card-header h5">
                        Daily Attendance
                        <form method="GET" action="{{ route('home') }}" class="float-right">
                            <div class="input-group">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Filter</button>
                                </div>
                                <div class="input-group-append">
                                    <a href="{{ route('home', ['date' => now()->subDay()->toDateString()]) }}" class="btn btn-secondary">Yesterday</a>
                                </div>
                                <div class="input-group-append">
                                    <a href="{{ route('home') }}" class="btn btn-light">Reset</a> <!-- Reset button -->
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Check In</th>
                                    <th scope="col">On Break</th>
                                    <th scope="col">Off Break</th>
                                    <th scope="col">Totle Break Time</th>
                                    <th scope="col">Check Out</th>
                                    <th scope="col">Totle Working Hours</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($dailyAttendance as $daily)
                                    <tr>
                                        <td>{{ $daily->user->name }}</td>
                                        <td>{{ $daily->checkin }}</td>
                                        <td>{{ $daily->on_break }}</td>
                                        <td>
                                            {{ $daily->off_break }} <!-- Display off break time or dash -->
                                        </td>
                                        <td>
                                            @php
                                                $onBreak = \Carbon\Carbon::parse($daily->on_break);
                                                $offBreak = $daily->off_break ? \Carbon\Carbon::parse($daily->off_break) : null;
                                                $totalBreakMinutes = $offBreak ? $onBreak->diffInMinutes($offBreak) : null;
                                                $totalBreakHours = $totalBreakMinutes ? floor($totalBreakMinutes / 60) : null;
                                                $remainingMinutes = $totalBreakMinutes ? $totalBreakMinutes % 60 : null;
                                            @endphp
                                            @if ($totalBreakMinutes === null)
                                                <span>-</span> <!-- Print dash if off break is not set -->
                                            @else
                                                @if ($totalBreakHours > 0)
                                                    <span class="text-danger">{{ $totalBreakHours }} hour{{ $totalBreakHours > 1 ? 's' : '' }}
                                                        @if ($remainingMinutes > 0)
                                                            and {{ $remainingMinutes }} minute{{ $remainingMinutes > 1 ? 's' : '' }}
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="text-success">{{ $remainingMinutes }} minute{{ $remainingMinutes > 1 ? 's' : '' }}</span>
                                                @endif
                                            @endif
                                        </td>

                                        <td>{{ $daily->checkout }}</td>
                                        <td>
                                            @php
                                                if (!$daily->checkout) {
                                                    // If checkout is not set
                                                    $totalWorkingTime = null;
                                                } else {
                                                    $checkin = \Carbon\Carbon::parse($daily->checkin);
                                                    $totalMinutes = $checkin->diffInMinutes($daily->checkout);
                                                    $workingMinutes = $totalMinutes - ($totalBreakMinutes ?? 0);
                                                    $workingHours = floor($workingMinutes / 60);
                                                    $remainingWorkingMinutes = $workingMinutes % 60;
                                                    $totalWorkingTime = $workingHours * 60 + $remainingWorkingMinutes; // Total working time in minutes
                                                }
                                            @endphp
                                            @if ($totalWorkingTime === null)
                                                <span>-</span> <!-- Print dash if checkout is not set -->
                                            @elseif ($totalWorkingTime < 480)
                                                <span class="text-danger">{{ $workingHours }} hour{{ $workingHours > 1 ? 's' : '' }}
                                                    @if ($remainingWorkingMinutes > 0)
                                                        and {{ $remainingWorkingMinutes }} minute{{ $remainingWorkingMinutes > 1 ? 's' : '' }}
                                                    @endif
                                                </span>
                                            @elseif ($totalWorkingTime == 480)
                                                <span class="text-primary">{{ $workingHours }} hour{{ $workingHours > 1 ? 's' : '' }}
                                                    @if ($remainingWorkingMinutes > 0)
                                                        and {{ $remainingWorkingMinutes }} minute{{ $remainingWorkingMinutes > 1 ? 's' : '' }}
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-success">{{ $workingHours }} hour{{ $workingHours > 1 ? 's' : '' }}
                                                    @if ($remainingWorkingMinutes > 0)
                                                        and {{ $remainingWorkingMinutes }} minute{{ $remainingWorkingMinutes > 1 ? 's' : '' }}
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pb-5">
                <div class="card">
                    <div class="card-header h5">
                        Pending Leave Requests
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">LeaveType</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaveApplications as $leave)
                                    <tr>
                                        <td>{{ $leave->user->name }}</td>
                                        <td>{{ $leave->leaveType }}</td>
                                        <td> {{ $leave->startDate }} </td>
                                        <td> {{ $leave->endDate }}</td>
                                        <td> {{ $leave->reason }}</td>
                                        <td class="text-primary"> {{ $leave->status }}</td>
                                        <td>
                                            <a href="{{ route('leave.approve', $leave->id) }}" class="btn btn-primary">Approve</a>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            {{ $leaveApplications->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pb-5">
                <div class="card">
                    <div class="card-header h5">
                        On leave today
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todayOnLeave as $onLeave)
                                    <tr>
                                        <td>{{ $onLeave->user->name }}</td>
                                        <td> {{ $onLeave->startDate }} </td>
                                        <td> {{ $onLeave->endDate }}</td>
                                        <td> {{ $onLeave->reason }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
