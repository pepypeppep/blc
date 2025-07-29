@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Coachee List') }}</h4>
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
                                <th class="text-center">{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Status Bergabung') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($coachings as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->coaching->title }}</td>
                                    <td>{!! truncate(strip_tags($item->coaching->goal)) !!}</td>
                                    <td class="text-center">{{ $item->coaching->total_session }}</td>
                                    <td class="text-center">
                                        <div class="badge bg-{{ $item->coaching->stat['color'] }}">
                                            {{ $item->coaching->stat['label'] }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->is_joined == 1)
                                            <div class="badge bg-success">{{ __('Bergabung') }}</div>
                                        @elseif($item->is_joined == 0 && $item->note != null)
                                            <div class="badge bg-danger">{{ __('Menolak Bergabung') }}</div>
                                        @else
                                            <div class="badge bg-danger">{{ __('Belum Bergabung') }}</div>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="dashboard__action d-inline-flex align-items-center gap-2">
                                            <a href="{{ route('student.coachee.show', $item->coaching->id) }}"
                                                class="btn-action-primary" title="Lihat Detail">
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
                </div>
            </div>
        </div>
    </div>
@endsection
