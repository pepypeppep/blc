@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Notifications') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table class="table table-borderless table-hover">
                        <tbody>
                            @forelse ($notifications as $index => $notif)
                            <tr onclick="window.location.href='{{ $notif->link ?? 'javascript:void(0);' }}'" style="cursor: pointer">
                                <td style="width: 10%">
                                    @if ($notif->status == 'rejected')
                                        <div class="notification-icon bg-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                    @elseif ($notif->status == 'approved' || $notif->status == 'accepted')
                                        <div class="notification-icon bg-success">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    @else
                                        <div class="notification-icon bg-primary">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                    @endif
                                </td>
                                <td style="width: 70%"><span class="notification-title">{{ $notif->title }} <span style="font-weight: normal;">{{ $notif->message }}</span></span></td>
                                <td style="width: 20%">
                                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">{{ __('No data found!') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@endsection
