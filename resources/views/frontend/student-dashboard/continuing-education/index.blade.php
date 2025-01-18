@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Daftar Program Pendidikan Lanjutan') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th  width="30%" >{{ __('Program Studi Pendidikan Lanjutan') }}</th>
                                <th>{{ __('Syarat Minimal Pangkat/Golongan') }}</th>
                                <th>{{ __('Tanggal mulai') }}</th>
                                <th>{{ __('Tanggal selesai') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vacancies as $key => $vacancy)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $vacancy->name }}</td>
                                    <td>
                                        @if ($vacancy->details->isNotEmpty())
                                            {{ $vacancy->details->first()->value_type ?? '-' }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td>{{ $vacancy->start_at ? \Carbon\Carbon::parse($vacancy->start_at)->format('d M Y') : '-' }}</td>
                                    <td>{{ $vacancy->end_at ? \Carbon\Carbon::parse($vacancy->end_at)->format('d M Y') : '-' }}</td>
                                    <td>
                                        @php
                                            $status = $vacancy->users->firstWhere('id', auth()->id())->pivot->status ?? 'inactive';
                                        @endphp
                                        <span class="badge {{ $status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('student.continuing-education.show', $vacancy->id) }}" class="align-middle ">
                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ __('No data found!') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $vacancies->links() }}
            </div>
        </div>
    </div>
@endsection
