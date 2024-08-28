@extends('layouts.app')
@section('title', 'Users')
@section('content')

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header py-3 d-flex justify-content-between h5">
            <div class="pt-3">
                Users
            </div>
            @if (auth()->user()->can('user-create'))
                <div class="">
                    <a class="btn btn-secondary mb-2" href="{{ route('users.create') }}"><i class="fa fa-plus"></i> Create
                        New
                        User</a>
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Birthdate</th>
                        <th>Roles</th>
                        <th width="280px">Action</th>
                    </tr>
                    @php $no = 1; @endphp
                    @foreach ($data as $key => $user)
                        @if (auth()->user()->roles[0]->name == 'Admin' || auth()->user()->id == $user->id)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->birthdate }}</td>
                                <td>
                                    @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $v)
                                            <label class="badge bg-success text-white">{{ $v }}</label>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('users.show', $user->id) }}"><i
                                            class="fas fa-eye"></i> Show</a>
                                    @can('user-edit')
                                        <a class="btn btn-primary btn-sm" href="{{ route('users.edit', $user->id) }}"><i
                                                class="fas fa-edit"></i> Edit</a>
                                    @endcan
                                    @can('user-delete')
                                        @if (!$user->hasRole('Admin'))
                                            <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                                style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    {!! $data->links('pagination::bootstrap-5') !!}
@endsection
