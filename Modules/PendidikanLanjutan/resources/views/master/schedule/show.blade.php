@extends('admin.master_layout')
@section('title')
    <title>
        {{ __('Berkas Report Detail') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Berkas Report Detail') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.vacancies.master.file.index') }}">{{ __('Berkas Report') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Berkas Report Detail') }}</div>
                </div>
            </div>

            <div id="detail" class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">{{ __('Berkas Report Detail') }}</div>
                                <hr>
                                <form method="POST"
                                    action="{{ route('admin.vacancies.master.file.update', ['id' => $report->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama
                                            Berkas</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $report->name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Deskripsi
                                            Berkas</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" class="form-control" name="description"
                                                value="{{ $report->description }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status
                                            Berkas</label>
                                        <div class="col-sm-12 col-md-7">
                                            <select name="is_active" class="form-control">
                                                <option value=1 {{ $report->is_active == 1 ? 'selected' : '' }}>
                                                    Aktif</option>
                                                <option value=0 {{ $report->is_active == 0 ? 'selected' : '' }}>
                                                    Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                        <div class="col-sm-12 col-md-7">
                                            <a href="{{ route('admin.vacancies.master.file.index') }}"
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
