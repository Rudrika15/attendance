@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">

            @if (auth()->user()->can('user-create'))
                <div class="pull-right">
                    <a class="btn btn-success mb-2" href="{{ route('users.create') }}"><i class="fa fa-plus"></i> Create New User</a>
                </div>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Roles</th>
            <th width="280px">Action</th>
        </tr>
        @php $no = 1; @endphp
        @foreach ($data as $key => $user)
            @if ( auth()->user()->roles[0]->name == 'Admin' || auth()->user()->id == $user->id)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if (!empty($user->getRoleNames()))
                            @foreach ($user->getRoleNames() as $v)
                                <label class="badge bg-success">{{ $v }}</label>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm" href="{{ route('users.show', $user->id) }}"><i class="fa-solid fa-list"></i> Show</a>
                       @can('user-edit')

                       <a class="btn btn-primary btn-sm" href="{{ route('users.edit', $user->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                       @endcan
                       @can('user-delete')
                            <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endif
        @endforeach
    </table>

    {!! $data->links('pagination::bootstrap-5') !!}
@endsection
