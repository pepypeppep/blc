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
                                <th>{{ __('Main Issue') }}</th>
                                <th>{{ __('Total Session') }}</th>
                                <th class="text-center">{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Coaching 1</td>
                                <td>Coaching 1</td>
                                <td class="text-center">3</td>
                                <td class="text-center"><span class="badge bg-success">Bergabung</span></td>
                                <td class="text-center"><a href="{{ route('student.coachee.show', 1) }}"
                                        class="btn-action-primary" title="Lihat Detail">
                                        <i class="fa fa-eye"></i> &nbsp;Lihat
                                    </a></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Coaching 1</td>
                                <td>Coaching 1</td>
                                <td class="text-center">2</td>
                                <td class="text-center"><span class="badge bg-warning">Konsesus</span></td>
                                <td class="text-center"><a href="{{ route('student.coachee.show', 1) }}"
                                        class="btn-action-primary" title="Lihat Detail">
                                        <i class="fa fa-eye"></i> &nbsp;Lihat
                                    </a></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Coaching 1</td>
                                <td>Coaching 1</td>
                                <td class="text-center">5</td>
                                <td class="text-center"><span class="badge bg-secondary">Draft</span></td>
                                <td class="text-center"><a href="{{ route('student.coachee.show', 1) }}"
                                        class="btn-action-primary" title="Lihat Detail">
                                        <i class="fa fa-eye"></i> &nbsp;Lihat
                                    </a></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Coaching 1</td>
                                <td>Coaching 1</td>
                                <td class="text-center">1</td>
                                <td class="text-center"><span class="badge bg-danger">Tolak</span></td>
                                <td class="text-center"><a href="{{ route('student.coachee.show', 1) }}"
                                        class="btn-action-primary" title="Lihat Detail">
                                        <i class="fa fa-eye"></i> &nbsp;Lihat
                                    </a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
