@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Role</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm mb-2" href="{{ route('roles.index') }}">
                <i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>

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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Role</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name"><strong>Name:</strong></label>
                                <input type="text" name="name" placeholder="Enter Role Name" class="form-control"
                                    id="name" value="{{ $role->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <strong>Permissions:</strong>
                                <br />
                                @foreach($permission as $value)
                                <div class="form-check">
                                    <input type="checkbox" name="permission[{{$value->id}}]" value="{{$value->id}}"
                                        class="form-check-input" id="permission{{$value->id}}"
                                        {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="permission{{$value->id}}">{{ $value->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm mb-3">
                                <i class="fa-solid fa-floppy-disk"></i> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection