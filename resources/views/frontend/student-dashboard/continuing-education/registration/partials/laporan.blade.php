@php
    use Modules\PendidikanLanjutan\app\Models\VacancyUser;
@endphp

<div class="tab-pane fade" id="itemSeven-tab-pane" role="tabpanel" aria-labelledby="itemSeven-tab" tabindex="0">
    <div class="instructor__profile-form-wrap">
        @if($vacancy->status !== VacancyUser::STATUS_DONE)
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
        <div class="dashboard__review-table mt-4" style="overflow: hidden;">
            <h6 class="mb-3">Daftar Laporan per Semester</h6>
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
                            <td><span class="badge bg-success">{{ $report->status }}</span>
                            </td>
                            <td>
                                <a href="#" class="text-primary"><i class="fa fa-pencil-alt"></i></a>
                                <a href="#" class="text-danger delete-item"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
