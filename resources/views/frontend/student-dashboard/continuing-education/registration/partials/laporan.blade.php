<div class="tab-pane fade" id="itemSeven-tab-pane" role="tabpanel" aria-labelledby="itemSeven-tab" tabindex="0">
    <div class="instructor__profile-form-wrap">
        <form action="{{ route('student.continuing-education.registration.report', $vacancy->id) }}" method="POST"
            enctype="multipart/form-data" class="instructor__profile-form">
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Pilih Semester</label>
                        <select name="name" id="name" class="form-select">
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
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
