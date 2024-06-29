@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- <div class="col-md-12 py-2">

                <div class="card">
                    <div class="card-header h5">
                        Totle Users
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $users }}</h5>
                    </div>
                </div>
            </div> --}}
            <div class="col-md-12 ">
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
                                            <a href="{{ route('leave.approve', $leave->id) }}"
                                                class="btn btn-primary">Approve</a>

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
            <div class="col-md-12 py-5">
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
            <div class="col-md-4"></div>
        </div>
    </div>
@endsection
