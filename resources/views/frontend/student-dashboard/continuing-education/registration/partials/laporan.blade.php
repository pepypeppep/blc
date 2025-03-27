@php
    use Modules\PendidikanLanjutan\app\Models\VacancyUser;
@endphp

<div class="tab-pane fade" id="itemSeven-tab-pane" role="tabpanel" aria-labelledby="itemSeven-tab" tabindex="0">
    <div class="instructor__profile-form-wrap">
        @if ($vacancy->status !== VacancyUser::STATUS_DONE)
            <form action="{{ route('student.continuing-education.registration.report', $vacancy->id) }}" method="POST"
                enctype="multipart/form-data" class="instructor__profile-form">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Pilih Semester</label>
                            <select name="name" id="name" class="form-select">
                                @foreach ($reportsFiles as $report)
                                    <option value={{ $report->id }}>{{ $report->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="file">File Laporan</label>
                            <input id="file" name="file" type="file" accept=".pdf" class="form-control"
                                value="">
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="submit-btn my-3">
                            <button type="submit" class="btn"><i class="fa fa-upload"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
        <!-- Tabel Pemantauan File -->
        <h6 class="mb-3 mt-4">Daftar Laporan per Semester</h6>
        <div class="dashboard__review-table mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Semester</th>
                        <th>Nama File</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ $report->name }}</td>
                            <td>{{ substr(strrchr($report->file, '/'), 1) }}</td>

                            <td>
                                {!! $report->getStatusLabel() !!}</td>
                            <td>
                                @if ($report->status !== 'accepted')
                                    <form class="d-inline" id="update-form-{{ $report->id }}"
                                        action="{{ route('student.continuing-education.registration.report.update', ['id' => $vacancy->id, 'report_id' => $report->id]) }}"
                                        method="POST" class="d-inline" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="file" accept=".pdf" id="file-input-{{ $report->id }}"
                                            onchange="document.getElementById('update-form-{{ $report->id }}').submit();"
                                            name="file" class="d-none">
                                    </form>
                                    <a href="javascript:void(0);"
                                        onclick="document.getElementById('file-input-{{ $report->id }}').click();"
                                        style="background: {{ $setting->primary_color }}"><i
                                            class="fa fa-pencil-alt text-white"></i></a>
                                    <a href="javascript:void(0);"
                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $report->id }}').submit();">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </a>
                                    <form id="delete-form-{{ $report->id }}"
                                        action="{{ route('student.continuing-education.registration.report.delete', ['id' => $vacancy->id, 'report_id' => $report->id]) }}"
                                        method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                                <a href="{{ route('student.continuing-education.registration.report.view', ['id' => $vacancy->id, 'report_id' => $report->id]) }}"
                                    class="text-success" target="_blank"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
