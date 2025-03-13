@extends('admin.master_layout')
@section('title')
<title>{{ __('Edit Admin') }}</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route('admin.admin.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>{{ __('Edit Admin') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active"><a
                        href="{{ route('admin.admin.index') }}">{{ __('Manage Admin') }}</a>
                </div>
                <div class="breadcrumb-item">{{ __('Edit Admin') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3>{{ __('Edit Admin') }}</h3>
                            <div>
                                @adminCan('admin.view')
                                <a href="{{ route('admin.admin.index') }}" class="btn btn-primary"><i
                                        class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                                @endadminCan
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <form action="{{ route('admin.admin.update', $admin->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="name" class="form-control" name="name"
                                                    value="{{ $admin->name }}">
                                            </div>

                                            <!-- instansi -->
                                            <div class="form-group col-12">
                                                <label for="instansi">{{ __('Instansi') }} <span
                                                        class="text-danger">*</span></label>
                                                <select name="instansi" id="instansi"
                                                    class="form-control select2 @error('instansi') is-invalid @enderror">
                                                    <option value="" disabled>{{ __('Select Instansi') }}</option>
                                                    @foreach ($instansis as $instansi)
                                                    <option
                                                        @selected($admin->instansi_id == $instansi->id)
                                                        value="{{ $instansi->id }}">{{ $instansi->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- /instansi -->

                                            <!-- email -->
                                            <div class="form-group col-12">
                                                <label>{{ __('Email') }} <span class="text-danger">*</span></label>
                                                <input type="email" id="slug" class="form-control" name="email"
                                                    value="{{ $admin->email }}">
                                            </div>

                                            <!-- username -->
                                            <div class="form-group col-12">
                                                <label>{{ __('Username') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="username" class="form-control" name="username"
                                                    value="{{ $admin->username }}">
                                            </div>

                                            <div class="form-group col-12">
                                                <label>{{ __('Status') }} <span class="text-danger">*</span></label>
                                                <select name="status" class="form-control">
                                                    <option value="active" @selected($admin->status == 'active')>
                                                        {{ __('Active') }}
                                                    </option>
                                                    <option value="inactive" @selected($admin->status == 'inactive')>
                                                        {{ __('Inactive') }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-12">
                                                <label for="role">{{ __('Assign Role') }} <span
                                                        class="text-danger">*</span></label>
                                                <select name="role[]" id="role"
                                                    class="form-control select2 @error('role') is-invalid @enderror"
                                                    multiple>
                                                    <option value="" disabled>{{ __('Select Role') }}</option>
                                                    @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        @selected($admin->hasRole($role->name))>{{ $role->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="text-center col-md-8 offset-md-2">
                                                <x-admin.update-button :text="__('Update')"></x-admin.update-button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection