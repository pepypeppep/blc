@extends('admin.master_layout')
@section('title')
    <title>{{ __('Coaching Detail') }}</title>
@endsection

@php
    use Modules\Coaching\app\Models\Coaching;
@endphp

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Detail Tema Coaching') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Certificate Recognition') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ $coaching->title }}</h4>
                                <div class="badge badge-{{ $coaching->stat['color'] }}">{{ $coaching->stat['label'] }}
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Goal') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->goal !!}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Reality') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->reality !!}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Option') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->option !!}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Way Forward') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->way_forward !!}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Success Indicator') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->success_indicator !!}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Sumber Belajar') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{!! $coaching->learning_resources !!}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-4">
                                        <div
                                            class="mb-3 d-flex flex-wrap justify-content-between align-items-start border-top pt-3 mt-4 gap-3">
                                            <div class="flex-grow-1 me-3" style="min-width: 250px; max-width: 80%;">
                                                <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                    {{ __('List Coachee') }}
                                                    <span
                                                        title="Jumlah coachee yang telah merespon">({{ $coaching->respondedCoachees()->count() }}/{{ $coaching->coachees()->count() }})</span>

                                                </p>
                                            </div>
                                        </div>

                                        <div class="">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="border px-4 py-2">Nama</th>
                                                        <th class="border px-4 py-2">Status</th>
                                                        <th class="border px-4 py-2">Keterangan Konsensus</th>
                                                        <th class="border px-4 py-2">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($coaching->coachees as $coachee)
                                                        <tr>
                                                            <td class="border px-4 py-2">{{ $coachee->name }}</td>
                                                            <td class="border px-4 py-2">
                                                                @if (is_null($coachee->pivot->is_joined))
                                                                    <span class="badge bg-secondary text-white  ">Belum
                                                                        merespons</span>
                                                                @elseif ($coachee->pivot->is_joined)
                                                                    <span
                                                                        class="badge bg-success text-white  ">Bergabung</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-danger text-white  ">Menolak</span>
                                                                @endif
                                                            </td>
                                                            <td class="border px-4 py-2">
                                                                @if ($coachee->pivot->is_joined && $coachee->pivot->joined_at)
                                                                    <small>Dibuat :
                                                                        {{ \Carbon\Carbon::parse($coachee->pivot->joined_at)->translatedFormat('d F Y H:i') }}</small>
                                                                @else
                                                                    {{ $coachee->pivot->notes ? truncate(strip_tags($coachee->pivot->notes)) : '-' }}<br />
                                                                    <small>Dibuat :
                                                                        {{ \Carbon\Carbon::parse($coachee->pivot->updated_at)->translatedFormat('d F Y H:i') }}</small>
                                                                @endif
                                                            </td>
                                                            <td class="border px-4 py-2">
                                                                <div
                                                                    class="dashboard__action d-inline-flex align-items-center gap-2">
                                                                    @if ($coachee->pivot->final_report && $coachee->pivot->is_joined)
                                                                        <a href="{{ route('admin.coaching.view.report', $coachee->pivot->id) }}"
                                                                            class="btn btn-primary btn-sm mr-2"
                                                                            title="Lihat Laporan Akhir" target="_blank">
                                                                            <i class="fa fa-eye"></i>
                                                                            &nbsp;{{ __('Laporan Akhir') }}
                                                                        </a>
                                                                        {{-- <a class="btn btn-warningbtn-sm "
                                                                            href="{{ route('admin.coaching.penilaian', [$coaching->id, $coachee->id]) }}">
                                                                            <i class="fa fa-check-circle"></i>
                                                                            &nbsp;{{ __('Penilaian') }}
                                                                        </a> --}}
                                                                    @else
                                                                        <a href="javascript:void(0)"
                                                                            class="btn btn-primary btn-sm disabled  mr-2"
                                                                            title="Laporan akhir belum tersedia"
                                                                            onclick="return false;"
                                                                            style="pointer-events: none; opacity: 0.5;">
                                                                            <i class="fa fa-eye"></i>
                                                                            &nbsp;{{ __('Laporan Akhir') }}
                                                                        </a>
                                                                        {{-- <a href="javascript:void(0)"
                                                                            class="btn btn-warning btn-sm disabled"
                                                                            title="Laporan akhir belum tersedia"
                                                                            onclick="return false;"
                                                                            style="pointer-events: none; opacity: 0.5;">
                                                                            <i class="fa fa-eye"></i>
                                                                            &nbsp;{{ __('Penilaian') }}
                                                                        </a> --}}
                                                                    @endif

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="border px-4 py-2 text-center">Belum
                                                                ada coachee</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                Pelaksanaan Pertemuan ({{ count($coaching->coachingSessions) }})
                                            </p>

                                            <div class="accordion card mb-2" id="coachingAccordion">
                                                @foreach ($coaching->coachingSessions as $index => $session)
                                                    @php
                                                        $totalJoinedCoachees = $coaching->joinedCoachees()->count();
                                                        $filledReports = $session->details
                                                            ->whereNotNull('activity')
                                                            ->count();
                                                        $filledReviews = $session->details
                                                            ->whereNotNull('coaching_note')
                                                            ->count();
                                                        $detailsByUserId = $session->details->keyBy('coaching_user_id');
                                                    @endphp

                                                    <div class="accordion-item border border-secondary-subtle mb-2">
                                                        <h2 class="accordion-header">
                                                            <div
                                                                class="accordion_header_content d-flex justify-content-between align-items-center">
                                                                <button class="accordion-button course-quiz-btn collapsed"
                                                                    type="button" data-toggle="collapse"
                                                                    data-target="#coaching-collapse{{ $index }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="coaching-collapse{{ $index }}"
                                                                    style="width: 100%;">
                                                                    <div class="text-start">
                                                                        <div class="d-flex align-items-center mb-1">
                                                                            <span class="fw-semibold text-dark"
                                                                                style="font-size: 0.95rem;">
                                                                                Pertemuan {{ $index + 1 }}
                                                                            </span>
                                                                            <span
                                                                                class="badge bg-info text-white ml-2 px-2 py-1"
                                                                                style="font-size: 0.7rem; font-weight: 700;">
                                                                                Terisi
                                                                                ({{ $filledReports }}/{{ $totalJoinedCoachees }})
                                                                            </span>
                                                                            <span
                                                                                class="badge bg-primary text-white ml-2 px-2 py-1"
                                                                                style="font-size: 0.7rem; font-weight: 700;">
                                                                                Direviu
                                                                                ({{ $filledReviews }}/{{ $filledReports }})
                                                                            </span>
                                                                        </div>
                                                                        <div class="text-muted text-left"
                                                                            style="font-size: 0.85rem;">
                                                                            {{ \Carbon\Carbon::parse($session->coaching_date)->translatedFormat('l, d F Y H:i') }}
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </h2>

                                                        <div id="coaching-collapse{{ $index }}"
                                                            class="accordion-collapse collapse"
                                                            data-bs-parent="#coachingAccordion">

                                                            <div class="accordion-body">
                                                                @if (!empty($session->activity))
                                                                    <strong class="d-block">Deskripsi Kegiatan:</strong>
                                                                    <div class="text-body">{!! $session->activity ?: '<em>Tidak ada deskripsi kegiatan.</em>' !!}</div>

                                                                    <div class="mb-2">
                                                                        <strong class="d-block">Hambatan:</strong>
                                                                        <div class="text-body">{!! $session->description ?: '<em>Tidak ada hambatan dicatat.</em>' !!}
                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-2">
                                                                        <strong class="d-block">Dokumentasi:</strong>
                                                                        @if ($session->image && Storage::disk('private')->exists($session->image))
                                                                            <a href="{{ route('admin.coaching.view.img', $session->id) }}"
                                                                                target="_blank">
                                                                                <img src="{{ route('admin.coaching.view.img', $session->id) }}"
                                                                                    alt="img"
                                                                                    class="img-thumbnail mt-2"
                                                                                    style="max-width: 200px;">
                                                                            </a>
                                                                        @else
                                                                            <p class="text-muted"><em>Belum ada dokumentasi
                                                                                    gambar.</em></p>
                                                                        @endif
                                                                    </div>

                                                                    @if ($session->status == 'reviewed')
                                                                        <form>
                                                                            <div class="form-group mt-4">
                                                                                <label
                                                                                    for="coaching_date">{{ __('Coaching Date') }}</label>
                                                                                <input type="text" name="coaching_date"
                                                                                    class="form-control datetimepicker"
                                                                                    value="{{ $session->coaching_date ?? '' }}"
                                                                                    disabled>
                                                                            </div>
                                                                            <div class="form-group mt-4">
                                                                                <strong class="d-block">Catatan:</strong>
                                                                                <div class="text-body">
                                                                                    {!! clean($session->coaching_note) !!}
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group mt-4">
                                                                                <strong class="d-block">Arahan:</strong>
                                                                                <div class="text-body">
                                                                                    {!! clean($session->coaching_instructions) !!}
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    @endif
                                                                @endif

                                                                @if ($coaching->isProcessOrDone())
                                                                    <hr class="my-4">
                                                                    <strong class="d-block mb-2">Laporan Coachee</strong>

                                                                    <div class="table-responsive rounded">
                                                                        <table class="table table-bordered mb-0">
                                                                            <thead class="table-light">
                                                                                <tr>
                                                                                    <th>Nama Coachee</th>
                                                                                    <th>Status</th>
                                                                                    <th>Kegiatan</th>
                                                                                    <th>Catatan Coach</th>
                                                                                    <th>Aksi</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @forelse ($coaching->joinedCoachees as $coachee)
                                                                                    @php
                                                                                        $coachingUserId =
                                                                                            $coachee->pivot->id;
                                                                                        $detail = $detailsByUserId->get(
                                                                                            $coachingUserId,
                                                                                        );
                                                                                        $imageUrl =
                                                                                            $detail &&
                                                                                            $detail->image &&
                                                                                            Storage::disk(
                                                                                                'private',
                                                                                            )->exists($detail->image)
                                                                                                ? route(
                                                                                                    'admin.coaching.view.img',
                                                                                                    $detail->id,
                                                                                                )
                                                                                                : null;

                                                                                        $modalData = [
                                                                                            'coachee_name' =>
                                                                                                $coachee->name,
                                                                                            'activity' =>
                                                                                                $detail->activity ?? '',
                                                                                            'obstacle' =>
                                                                                                $detail->description ??
                                                                                                '',
                                                                                            'image_url' => $imageUrl,
                                                                                            'note' =>
                                                                                                $detail->coaching_note ??
                                                                                                '',
                                                                                            'instructions' =>
                                                                                                $detail->coaching_instructions ??
                                                                                                '',
                                                                                        ];
                                                                                    @endphp

                                                                                    <tr>
                                                                                        <td>{{ $coachee->name }}</td>
                                                                                        <td>
                                                                                            @if ($detail)
                                                                                                <span
                                                                                                    class="badge bg-success text-white">Terisi</span>
                                                                                            @else
                                                                                                <span
                                                                                                    class="badge bg-secondary text-white">Belum
                                                                                                    Terisi</span>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td>
                                                                                            {!! $detail?->activity ? \Str::limit(strip_tags($detail->activity), 100) : '-' !!}
                                                                                            @if ($detail?->created_at)
                                                                                                <br>
                                                                                                <small class="text-muted">
                                                                                                    Dibuat:
                                                                                                    {{ $detail->created_at->translatedFormat('d F Y H:i') }}
                                                                                                </small>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td>
                                                                                            {!! $detail?->coaching_note ? \Str::limit(strip_tags($detail->coaching_note), 100) : '<em>Belum ditinjau</em>' !!}
                                                                                        </td>
                                                                                        <td>
                                                                                            <button
                                                                                                class="btn btn-sm btn-primary"
                                                                                                type="button"
                                                                                                data-toggle="collapse"
                                                                                                data-target="#review-{{ $coachingUserId }}"
                                                                                                aria-expanded="false"
                                                                                                aria-controls="review-{{ $coachingUserId }}"
                                                                                                title="Lihat Selengkapnya">
                                                                                                <i class="fa fa-eye"></i>
                                                                                            </button>
                                                                                        </td>
                                                                                    </tr>

                                                                                    <tr class="collapse"
                                                                                        id="review-{{ $coachingUserId }}"
                                                                                        style="transition: height 0.5s ease; ">
                                                                                        <td colspan="5">
                                                                                            <div
                                                                                                class="p-3 rounded mt-3 border">
                                                                                                {{-- style="background-color: #f4f4f4;" --}}
                                                                                                <div class="row mb-2">
                                                                                                    <div class="col-md-3 fw-bold"
                                                                                                        style="font-weight: 700 !important;">
                                                                                                        Nama Coachee</div>
                                                                                                    <div class="col-md-8">
                                                                                                        {{ $modalData['coachee_name'] }}
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row mb-2">
                                                                                                    <div class="col-md-3 fw-bold"
                                                                                                        style="font-weight: 700 !important;">
                                                                                                        Kegiatan</div>
                                                                                                    <div class="col-md-9">
                                                                                                        {!! $modalData['activity'] ?: '<em>-</em>' !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row mb-2">
                                                                                                    <div class="col-md-3 fw-bold"
                                                                                                        style="font-weight: 700 !important;">
                                                                                                        Hambatan</div>
                                                                                                    <div class="col-md-9">
                                                                                                        {!! $modalData['obstacle'] ?: '<em>-</em>' !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                <hr>
                                                                                                <div class="row mb-2">
                                                                                                    <div class="col-md-3 fw-bold"
                                                                                                        style="font-weight: 700 !important;">
                                                                                                        Catatan Coach</div>
                                                                                                    <div class="col-md-9">
                                                                                                        {!! $modalData['note'] ?: '<em>-</em>' !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row mb-2">
                                                                                                    <div class="col-md-3 fw-bold"
                                                                                                        style="font-weight: 700 !important;">
                                                                                                        Arahan</div>
                                                                                                    <div class="col-md-9">
                                                                                                        {!! $modalData['instructions'] ?: '<em>-</em>' !!}
                                                                                                    </div>
                                                                                                </div>
                                                                                                @if ($modalData['image_url'])
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-3 fw-bold"
                                                                                                            style="font-weight: 700 !important;">
                                                                                                            Gambar</div>
                                                                                                        <div
                                                                                                            class="col-md-9">
                                                                                                            <img src="{{ $modalData['image_url'] }}"
                                                                                                                alt="Lampiran"
                                                                                                                class="img-fluid rounded shadow-sm"
                                                                                                                style="max-height: 200px;">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                @empty
                                                                                    <tr>
                                                                                        <td colspan="5"
                                                                                            class="text-center text-muted">
                                                                                            Belum ada coachee yang bergabung
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforelse
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        @if ($coaching->coach)
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>Coach</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="https://asn.bantulkab.go.id/images/simpeg/fotopns/{{ $coaching->coach->nip }}.jpg"
                                            alt="{{ $coaching->coach->name }}" class="rounded-circle" width="50"
                                            height="50" style="object-fit: cover;">
                                        <div class=" ml-2">
                                            <strong>{{ $coaching->coach->name }}</strong><br>
                                            <small class="text-muted">{{ $coaching->coach->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- SPT Coaching --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('SPT Coaching') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group d-flex justify-content-center">
                                    @if ($coaching->spt)
                                        <a href="{{ route('admin.coaching.view.spt', $coaching->id) }}" target="_blank"
                                            class="btn btn-outline-primary d-flex align-items-center gap-2"
                                            style="font-size: 1.1rem;">
                                            <i class="fa fa-file-pdf fa-lg"></i> Lihat Surat
                                        </a>
                                    @else
                                        <p class="text-muted mb-0"><em>Tidak ada file</em></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- /SPT Coaching --}}
                        @include('coaching::certificate-card')
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection




@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endpush



@push('scripts')
    <script src="{{ asset('backend/js/default/courses.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    <script>
        $('#certificateModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            // Ambil string JSON dari attribute
            var jsonString = button.attr('data-detail');

            console.log("Raw JSON string:", jsonString); // debug

            try {
                var data = JSON.parse(jsonString);
                console.log("Parsed data:", data);

                $('#modal-coachee-name').html(data.coachee_name || '-');
                $('#modal-activity').html(data.activity || '-');
                $('#modal-obstacle').html(data.obstacle || '-');
                $('#modal-note').html(data.note || '-');
                $('#modal-instructions').html(data.instructions || '-');

            } catch (e) {
                console.error("Gagal parsing JSON:", e);
            }
        });
    </script>
@endpush
