@extends('admin.master_layout')
@section('title')
    <title>{{ __('Course Term of Service') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">{{ __('Course Term of Service') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Course Term of Service') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="dashboard__content-wrap">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="instructor__profile-form-wrap mt-4">
                                        <form action="{{ route('admin.course-tos.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea name="description" class="text-editor form-control summernote">{!! clean(@$tos?->description) !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <button class="btn btn-info" type="button" data-toggle="modal" data-target="#termOfServiceModal">{{ __('Preview') }}</button>
                                                <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
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
        <div class="modal fade" id="termOfServiceModal" tabindex="-1" role="dialog" aria-labelledby="termOfServiceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="term-of-service-content p-4 text-dark" style="max-height: 70vh; overflow-y: auto;">
                            <div style="background: #f7f7f7;" class="p-3">
                                {!! clean(@$tos->description) !!}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between px-4 pb-4 pt-3" style="border-top: 1px solid #dee2e6;">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ __('Tolak') }}</button>
                        <i class="text-danger">PADA HALAMAN INI HANYA SEBAGAI PRATINJAU, TOMBOL TIDAK DIFUNGSIKAN</i>
                        <button type="button" class="btn btn-primary" id="acceptTermsBtn" data-dismiss="modal" disabled>{{ __('Setuju') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#termOfServiceModal').on('shown.bs.modal', function() {
            const $content = $('.term-of-service-content');
            const $acceptBtn = $('#acceptTermsBtn');

            // Smooth scroll to top when modal is shown
            $content.stop().animate({ scrollTop: 0 }, 400);

            // Reset accept button state when modal is shown
            $acceptBtn.prop('disabled', true);

            $content.on('scroll', function() {
                // Check if user has scrolled to the bottom
                const isBottom = $(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight - 10;

                if (isBottom) {
                    $acceptBtn.prop('disabled', false);
                }
            });
        });

        // Also check on initial load in case content is shorter than container
        $('#termOfServiceModal').on('scroll', '.term-of-service-content', function() {
            const $content = $(this);
            const $acceptBtn = $('#acceptTermsBtn');
            const isBottom = $content.scrollTop() + $content.innerHeight() >= $content[0].scrollHeight - 10;

            if (isBottom) {
                $acceptBtn.prop('disabled', false);
            }
        });
    });
    </script>
@endpush

@push('css')
    <style>
        .term-of-service-content p {
            margin-bottom: 0;
        }
    </style>
@endpush
