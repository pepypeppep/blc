@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Coaching List') }}</h4>
            <div>
                <button type="button" onclick="location.href='{{ route('student.coach.create') }}'"
                    class="btn">{{ __('Tambah Tema Coaching') }}</button>
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
                                <th>{{ __('Goal') }}</th>
                                <th>{{ __('Total Session') }}</th>
                                <th>{{ __('Total Coachee') }}
                                    <span class="text-muted small">(bergabung/total)</span>
                                </th>
                                <th class="text-center">{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($coachings as $coaching)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $coaching->title }}</td>
                                    <td>{!! truncate(strip_tags($coaching->goal)) !!}</td>
                                    <td class="text-center">{{ $coaching->total_session }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $coaching->joinedCoachees()->count() }}</span> /
                                        <span class="badge bg-secondary">{{ $coaching->coachees()->count() }}</span>
                                    </td>
                                    <td class="text-center"><div class="badge bg-{{ $coaching->stat['color'] }}">{{ $coaching->stat['label'] }}</div></td>
                                    <td class="text-nowrap">
                                        <div class="dashboard__action d-inline-flex align-items-center gap-2">
                                            @if ($coaching->status === 'Draft')
                                                <form action="{{ route('student.coach.set-consensus', $coaching->id) }}" method="POST" class="form-submit d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn-action-warning" title="Buat Konsensus">
                                                        <i class="fa fa-paper-plane"></i> &nbsp;Buat Konsensus
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('student.coach.show', $coaching->id) }}" class="btn-action-primary" title="Lihat Detail">
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
                        {{ $coachings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
   
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
                    text: "Apakah Anda yakin ingin menginisiasi konsensus coaching ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, mulai',
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
