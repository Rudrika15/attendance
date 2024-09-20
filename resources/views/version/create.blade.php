@extends('layouts.app')

@section('content')


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    

    <div class="card">
        <div class="card-header py-3 d-flex justify-content-between h5">
            <div class=" pt-3">
                Create Version
            </div>
                <div class="">
                    <a class="btn btn-secondary mb-2" href="{{ route('version.index') }}">
                        Back</a>
                </div>
        </div>
        <div class="card-body">


            <form method="POST" action="{{ route('version.store') }}">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Version:</strong>
                            <input type="text" name="version" placeholder="Add Version" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-success btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i>
                            Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
