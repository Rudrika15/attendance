@extends('layouts.app')

@section('content')


    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif


    <div class="card">
        <div class="card-header py-3 d-flex justify-content-between h5">
            <div class=" pt-3">
                Create Notificatiions
            </div>

        </div>
        <div class="card-body">


            <form method="POST" action="{{ route('add.notification') }}">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Title:</strong>
                            <input type="text" name="title" placeholder="Add title here" class="form-control">
                            @error('title')
                                <span class="text-danger mt-1 mb-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Details:</strong>
                            <textarea name="detail" placeholder="Add details here" class="form-control"></textarea>
                            @error('detail')
                                <span class="text-danger mt-1 mb-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-success btn-sm mt-2 mb-3"><i
                                class="fa-solid fa-floppy-disk"></i>
                            Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-header py-3 d-flex justify-content-between h5">
            <div class=" pt-3">
                All Notifications
            </div>

        </div>
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Title</th>
                            <th scope="col">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notifications as $notification)
                            <tr>
                                <td class="align-middle">
                                    <a href="{{ route('delete.notification', $notification->id) }}"
                                        class="btn btn-danger btn-sm">
                                        Delete
                                    </a>
                                </td>

                                <td class="align-middle">{{ $notification->title }}</td>
                                <td>
                                    <div class="text-muted text-end">
                                        <small>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    {{ $notification->detail }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
