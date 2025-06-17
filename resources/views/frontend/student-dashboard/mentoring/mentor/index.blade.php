@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Mentor') }}</h4>
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
                        @forelse ($mentorTopics as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->mentor->name }}</td>
                                <td class="text-center">{{ $item->total_session }}</td>
                                <td class="text-center"><div class="badge bg-{{ $item->stat['color'] }}">{{ $item->stat['label'] }}</div></td>
                                <td class="text-nowrap">
                                    <div class="dashboard__mentee-action d-inline-flex align-items-center gap-2">
                                        <a href="{{ route('student.mentor.show', $item->id) }}" class="btn-action-primary" title="Lihat Detail">
                                            <i class="fa fa-eye"></i> &nbsp;Lihat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('Pendaftaran Program Pendidikan Lanjutan belum dibuka') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $mentorTopics->links() }}
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
