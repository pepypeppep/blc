@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Detail Rencana Tindak Lanjut') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Kursus</td>
                                <td>
                                    {{ $item->course->title }}
                                </td>
                            </tr>
                            <tr>
                                <td>Ringkasan </td>
                                <td>
                                    {!! $item->summary !!}
                                </td>

                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="card shadow-sm border-0  mt-4">
                    <div class="card-header bg-primary text-white ">
                        <h5 class="mb-0 text-white">
                            <i class="fa fa-file"></i> File Rencana Tindak Lanjut</h5>
                    </div>
                    <div class="card-body">
                        <iframe src="{{ route('student.follow-up-action.files', $item->file_path) }}"
                            class="w-100 border" style="height: 600px;"></iframe>
                    </div>
                </div>

            </div>
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
