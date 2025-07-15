@extends('frontend.student-dashboard.layouts.master')

@php
    use Modules\Mentoring\app\Models\Mentoring;
@endphp

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Mentoring List') }}</h4>
            <div>
                <button type="button" onclick="location.href='{{ route('student.mentee.create') }}'"
                    class="btn">{{ __('Tambah Tema Mentoring') }}</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Mentor') }}</th>
                                <th>{{ __('Total Session') }}</th>
                                <th class="text-center">{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mentorings as $mentoring)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $mentoring->title }}</td>
                                    <td>{{ $mentoring->mentor->name }}</td>
                                    <td class="text-center">{{ $mentoring->total_session }}</td>
                                    <td class="text-center"><div class="badge bg-{{ $mentoring->stat['color'] }}">{{ $mentoring->stat['label'] }}</div></td>
                                    <td class="text-nowrap">
                                        <div class="dashboard__mentee-action d-inline-flex align-items-center gap-2">
                                            @if ($mentoring->status === Mentoring::STATUS_DRAFT)
                                                <form action="{{ route('student.mentee.submit', $mentoring->id) }}" method="POST" class="form-submit d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn-action-warning" title="Ajukan Mentoring">
                                                        <i class="fa fa-paper-plane"></i> &nbsp;Ajukan
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('student.mentee.show', $mentoring->id) }}" class="btn-action-primary" title="Lihat Detail">
                                                <i class="fa fa-eye"></i> &nbsp;Lihat
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('Tidak ada riwayat') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $mentorings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .dashboard__mentee-action a,
    .dashboard__mentee-action button {
        font-size: 14px;
        height: 30px;
        line-height: 1;
        padding: 0 14px;
        background: rgba(15, 36, 222, 0.25);
        color: rgba(197, 165, 22, 0.13);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 30px;
        transition: all 0.2s ease;
        border: none;
        outline: none;
        box-shadow: none;
    }

    .dashboard__mentee-action a:hover,
    .dashboard__mentee-action button:hover{
        background: rgba(15, 36, 222, 0.25);
        color: var(--tg-common-color-white);
    }

    .dashboard__mentee-action .btn-action-warning {
        background: rgba(239, 172, 47, 1);
        color: var(--tg-common-color-white);
    }

    .dashboard__mentee-action .btn-action-warning:hover {
        background: rgba(239, 172, 47, 0.20);
        color: rgba(239, 172, 47, 1);
    }

    .dashboard__mentee-action .btn-action-primary {
        background: rgba(47, 87, 239, 1);
        color: var(--tg-common-color-white);
    }

    .dashboard__mentee-action .btn-action-primary:hover {
        background: rgba(47, 87, 239, 0.20);
        color: var(--tg-theme-primary);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const submitForms = document.querySelectorAll('.form-submit');
        submitForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Perhatian',
                    text: "Apakah Anda yakin mengajukan mentoring ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, ajukan',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
