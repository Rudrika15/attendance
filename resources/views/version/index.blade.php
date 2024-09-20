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
                Versions
            </div>
            <div class="">
                <a class="btn btn-secondary mb-2" href="{{ route('version.create') }}"><i class="fa fa-plus"></i> Create
                    Version</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Version</th>
                        <th width="280px">Action</th>
                    </tr>
                    @foreach ($version as $data)
                        <tr>
                            <td>{{ $data->version }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('version.edit', $data->id) }}"><i
                                        class="fas fa-eye"></i> Edit</a>
                                <a class="btn btn-danger btn-sm" href="{{ route('version.delete', $data->id) }}"><i
                                        class="fas fa-edit"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
