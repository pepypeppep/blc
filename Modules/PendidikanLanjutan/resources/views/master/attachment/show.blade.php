@extends('admin.master_layout')
@section('title')
    <title>
        {{ __('Berkas Lampiran Detail') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Berkas Lampiran Detail') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.vacancies.master.attachment.index') }}">{{ __('Berkas Lampiran') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Berkas Lampiran Detail') }}</div>
                </div>
            </div>

            <div id="detail" class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">{{ __('Berkas Lampiran Detail') }}</div>
                                <hr>
                                <form method="POST"
                                    action="{{ route('admin.vacancies.master.attachment.update', ['id' => $attachment->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama
                                            Berkas</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $attachment->name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label
                                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Kategori</label>
                                        <div class="col-sm-12 col-md-7">
                                            <select name="category" class="form-control">
                                                <option value="syarat"
                                                    {{ $attachment->category == 'syarat' ? 'selected' : '' }}>
                                                    Syarat</option>
                                                <option value="lampiran"
                                                    {{ $attachment->category == 'lampiran' ? 'selected' : '' }}>
                                                    Lampiran</option>
                                                <option value="aktivasi"
                                                    {{ $attachment->category == 'aktivasi' ? 'selected' : '' }}>
                                                    Aktivasi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                        <div class="col-sm-12 col-md-7">
                                            <a href="{{ route('admin.vacancies.master.attachment.index') }}"
                                                class="btn btn-secondary">Kembali</a>
                                            <button class="btn btn-primary" type="submit">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Success", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Error", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif
@endpush
