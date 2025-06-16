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
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($mentorTopics as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->mentor->name }}</td>
                                <td>{{ $item->total_session }}</td>
                                <td><div class="badge bg-{{ $item->stat['color'] }}">{{ $item->stat['label'] }}</div></td>
                                <td class="text-center">
                                    <a href="{{ route('student.mentor.show', $item->id) }}"
                                       class="align-middle" data-bs-toggle="tooltip" title="Lihat selengkapnya">
                                        <i class="fas fa-eye"></i> {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('Pendaftaran Program Pendidikan Lanjutan belum dibuka') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $mentorTopics->links() }}
        </div>
    </div>
@endsection
