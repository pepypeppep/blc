@extends('frontend.student-dashboard.layouts.master')

@php
    use Modules\Coaching\app\Models\Coaching;
@endphp

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between align-items-center">
            <h4 class="">{{ __('Detail Tema Coaching') }}</h4>
            <a href="{{ route('student.coach.index') }}" class="btn btn-secondary btn-sm">{{ __('Kembali') }}</a>
        </div>

        <div class="mt-3 border-top pt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">{{ $coaching->title }}</h5>
                <span class="badge fs-6
                    @php
                        $statusColors = [
                            'Draft' => 'bg-secondary',
                            'Konsensus' => 'bg-warning',
                            'Proses' => 'bg-info',
                            'Selesai' => 'bg-success',
                            'Tolak' => 'bg-danger',
                        ];
                        echo $statusColors[$coaching->status] ?? 'bg-light text-dark';
                    @endphp
                ">
                    {{ $coaching->status }}
                </span>
            </div>

            <div class="mb-3 border-top pt-3 mt-4">
                <h6 class="title">{{ __('Main Issue') }}</h6>
                <div>{!! $coaching->main_issue !!}</div>
            </div>

            <div class="mb-3">
                <h6 class="title">{{ __('Purpose') }}</h6>
                <div>{!! $coaching->purpose !!}</div>
            </div>

            <div class="mb-3">
                <h6 class="title">{{ __('Learning Resources') }}</h6>
                <div>{!! $coaching->learning_resources ?: '<em>Tidak ada sumber belajar.</em>' !!}</div>
            </div>

            <div class="mb-3">
                <h6 class="mb-1 title">{{ __('SPT Coaching') }}</h6>
                @if ($coaching->spt)
                    <a href="{{ route('student.coach.view.spt', $coaching->id) }}" target="_blank" class="btn-outline-primary btn-sm">
                        <i class="fa fa-file-pdf"></i> Lihat Surat
                    </a>
                @else
                    <p class="text-muted mb-0"><em>Tidak ada file</em></p>
                @endif
            </div>

            <div class="mb-3">
                <div class="mb-3 d-flex flex-wrap justify-content-between align-items-start border-top pt-3 mt-4 gap-3">
                    <div class="flex-grow-1 me-3" style="min-width: 250px; max-width: 100%;">
                        <h6 class="title">
                            {{ __('List Coachee') }}
                            <span title="Jumlah coachee yang telah merespon">({{ $coaching->respondedCoachees()->count() }}/{{ $coaching->coachees()->count() }})</span>
                        </h6>
                        <span class="text-muted small d-block">
                            Lakukan sesi coaching bersama dengan coachee yang telah dipilih dan bergabung.
                        </span>
                        @if ($coaching->status == Coaching::STATUS_DRAFT)
                        <span class="text-muted small d-block">
                            Klik <strong>Buat Konsensus</strong> agar coachee dapat melakukan konsensus (menyetujui/menolak).
                        </span>
                        @elseif ($coaching->status == Coaching::STATUS_CONSENSUS)
                        <span class="text-muted small d-block">
                            Klik <strong>Mulai Proses Coaching</strong> agar sesi pertemuan bisa dilakukan.
                        </span>
                        @elseif ($coaching->status == Coaching::STATUS_PROCESS)
                        <span class="text-muted small d-block">
                            Penilaian kepada coachee dapat dilakukan ketika coachee sudah menyelesaikan laporan penugasan dan unggah Laporan Akhir.
                        </span>
                        @endif
                    </div>

                    <div class="mt-2">
                        @if ($coaching->status == Coaching::STATUS_DRAFT)
                        <form action="{{ route('student.coach.set-consensus', $coaching->id) }}" method="POST" class="d-inline" id="init_consensus">
                            @csrf
                            @method('PUT')
                            <button type="button" class="btn btn-outline-primary" onclick="handleInitConsensus(event)">
                                {{ __('Buat Konsensus') }} <i class="fa fa-arrow-right"></i>
                            </button>
                        </form>
                        @elseif ($coaching->status == Coaching::STATUS_CONSENSUS)
                        <form action="{{ route('student.coach.process-coaching', $coaching->id) }}" method="POST" class="d-inline" id="process_coaching">
                            @csrf
                            @method('PUT')
                            <button type="button" class="btn btn-outline-success" onclick="handleStartCoaching(event)">
                                {{ __('Mulai Proses Coaching') }} <i class="fa fa-arrow-right"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <div class="w-full table-responsive rounded-lg mb-8">
                    <table class="table-auto w-full border">
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
                                        <span class="badge bg-secondary">Belum merespons</span>
                                    @elseif ($coachee->pivot->is_joined)
                                        <span class="badge bg-success">Bergabung</span>
                                    @else
                                        <span class="badge bg-danger">Menolak</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">
                                    @if ($coachee->pivot->is_joined && $coachee->pivot->joined_at)
                                        <small>Dibuat : {{ \Carbon\Carbon::parse($coachee->pivot->joined_at)->translatedFormat('d F Y H:i') }}</small>
                                    @else
                                        {{ $coachee->pivot->notes ? truncate(strip_tags($coachee->pivot->notes)) : '-' }}<br/>
                                        <small>Dibuat : {{ \Carbon\Carbon::parse($coachee->pivot->updated_at)->translatedFormat('d F Y H:i') }}</small>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">
                                    <div class="dashboard__action d-inline-flex align-items-center gap-2">
                                        @if ($coachee->pivot->final_report && $coachee->pivot->is_joined)
                                            <a href="{{ route('student.coach.view.report', $coachee->pivot->id) }}" class="btn-action-primary" title="Lihat Laporan Akhir" target="_blank">
                                                <i class="fa fa-eye"></i> &nbsp;{{ __('Laporan Akhir') }}
                                            </a>
                                            <a class="btn-action-warning" href="{{ route('student.coach.penilaian', [$coaching->id, $coachee->id]) }}">
                                                <i class="fa fa-check-circle"></i> &nbsp;{{ __('Penilaian') }}
                                            </a>
                                        @else
                                        <a href="javascript:void(0)"
                                            class="btn-action-primary disabled"
                                            title="Laporan akhir belum tersedia"
                                            onclick="return false;"
                                            style="pointer-events: none; opacity: 0.5;">
                                            <i class="fa fa-eye"></i> &nbsp;{{ __('Laporan Akhir') }}
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="btn-action-warning disabled"
                                            title="Laporan akhir belum tersedia"
                                            onclick="return false;"
                                            style="pointer-events: none; opacity: 0.5;">
                                            <i class="fa fa-eye"></i> &nbsp;{{ __('Penilaian') }}
                                        </a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="border px-4 py-2 text-center">Belum ada coachee</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-3 d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                <div>
                    <h6 class="title">{{ __('Session Datetime') }} <span title="Jumlah pertemuan">({{ $coaching->total_session }})</span></h6>
                    <span class="text-muted small">
                        Lakukan sesi coaching sesuai jadwal dan berikan catatan/arahan pada hasil penugasan.
                    </span>
                </div>
            </div>

            <div class="accordion" id="accordionPanelsStayOpenExample">
                @foreach($coaching->coachingSessions as $session)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-{{$loop->iteration}}" aria-expanded="true" aria-controls="panelsStayOpen-collapse-{{$loop->iteration}}">
                            <div class="d-block">
                                <div>
                                    @php
                                        $totalJoinedCoachees = $coaching->joinedCoachees()->count();
                                        $filledReports = $session->details->pluck('coaching_user_id')->unique()->count();
                                    @endphp
                                    <strong>Pertemuan {{ $loop->iteration }}</strong>
                                    (<span title="Jumlah coachee yang telah membuat laporan pertemuan">Terisi {{ $filledReports }}/{{ $totalJoinedCoachees }}</span>)
                                </div>
                                <div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($session->coaching_date)->translatedFormat('l, d F Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapse-{{$loop->iteration}}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            @if ($coaching->isProcessOrDone())
                            @php
                                $detailsByUserId = $session->details->keyBy('coaching_user_id');
                            @endphp

                            <div class="w-full table-responsive rounded-lg mb-8">
                                <table class="table-fixed w-full border mb-10">
                                    <thead>
                                        <tr>
                                            <th class="border px-4 py-2">Nama Coachee</th>
                                            <th class="border px-4 py-2">Status</th>
                                            <th class="border px-4 py-2">Kegiatan</th>
                                            <th class="border px-4 py-2">Catatan Coach</th>
                                            <th class="border px-4 py-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($coaching->joinedCoachees as $coachee)
                                            @php
                                                $coachingUserId = $coachee->pivot->id;
                                                $detail = $detailsByUserId->get($coachingUserId);
                                            @endphp
                                            <tr>
                                                <td class="border px-4 py-2">{{ $coachee->name }}</td>
                                                <td class="border px-4 py-2">
                                                    @if ($detail)
                                                        <span class="badge bg-info">Terisi</span>
                                                    @else
                                                        <span class="badge bg-danger">Belum Terisi</span>
                                                    @endif
                                                </td>
                                                <td class="border px-4 py-2 max-w-[50px] text-break whitespace-normal">
                                                    {{ $detail?->activity ? truncate(strip_tags($detail->activity)) : '-' }} <br/>
                                                    @if ($detail?->created_at)
                                                        <small class="text-muted">
                                                            Dibuat pada {{ $detail->created_at->translatedFormat('d F Y H:i') }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td class="border px-4 py-2">
                                                   {!! $detail?->coaching_note ? truncate(strip_tags($detail->coaching_note)) : '<em>Belum direview</em>' !!}
                                                </td>
                                                <td class="border px-4 py-2">
                                                    <div class="dashboard__action d-inline-flex align-items-center gap-2">
                                                    @if ($detail)
                                                        @php
                                                            $imageUrl = $detail->image && Storage::disk('private')->exists($detail->image)
                                                                        ? route('student.coach.view.img', $detail->id)
                                                                        : null;

                                                            $modalData = [
                                                                'coachee_name' => $coachee->name,
                                                                'activity' => $detail->activity ?? '',
                                                                'obstacle' => $detail->description ?? '',
                                                                'image_url' => $imageUrl,
                                                                'note' => $detail->coaching_note ?? '',
                                                                'instructions' => $detail->coaching_instructions ?? '',
                                                                'session_id' => $session->id,
                                                                'coaching_user_id' => $coachee->pivot->id,
                                                            ];
                                                        @endphp

                                                        <button type="button"
                                                            class="btn-action-primary btn-detail-modal"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#dynamicDetailModal"
                                                            data-detail='@json($modalData)'>
                                                            <i class="fa fa-eye"></i>&nbsp;{{ __('Review') }}
                                                        </button>
                                                    @else
                                                        <a href="javascript:void(0)"
                                                            class="btn-action-primary disabled"
                                                            title="Laporan belum tersedia"
                                                            onclick="return false;"
                                                            style="pointer-events: none; opacity: 0.5;">
                                                            <i class="fa fa-eye"></i> &nbsp;{{ __('Review') }}
                                                        </a>
                                                    @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center px-4 py-2 text-gray-500">Belum ada coachee yang bergabung</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <div class="text-center">
                                    <h4 class="text-muted">Belum ada kegiatan</h4>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('modals')
<div class="modal fade" id="dynamicDetailModal" tabindex="-1" aria-labelledby="dynamicDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form method="POST" action="{{ route('student.coach.review') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="session_id" id="modal-session-id">
        <input type="hidden" name="coaching_user_id" id="modal-coaching-user-id">

        <div class="modal-header">
          <h5 class="modal-title" id="dynamicDetailModalLabel">Berikan Catatan dan Arahan lebih lanjut</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
            <div class="row">
                <!-- Detail laporan penugasan coachee-->
                <div class="col-md-6 border-end pe-4">
                    <div class="mb-3">
                        <strong>Nama Coachee:</strong>
                        <div id="modal-coachee-name" class="text-muted break-words"></div>
                    </div>

                    <div class="mb-3">
                        <strong>Kegiatan:</strong>
                        <div id="modal-activity" class="text-muted break-words" style="max-width: 100%; overflow-wrap: anywhere;"></div>
                    </div>

                    <div class="mb-3">
                        <strong>Hambatan:</strong>
                        <div id="modal-obstacle" class="text-muted break-words" style="max-width: 100%; overflow-wrap: anywhere;"></div>
                    </div>

                    <div class="mb-3">
                        <strong>Dokumentasi:</strong><br>
                        <img id="modal-image" src="" alt="Dokumentasi" class="img-fluid rounded" style="max-height:200px; display:none;">
                        <p id="modal-image-placeholder" class="text-muted" style="display:none;"><em>Dokumentasi belum tersedia</em></p>
                    </div>
                </div>

                <!-- Form Catatan dan arahan coach -->
                <div class="col-md-6 ps-4">
                    <div id="note-container" class="mb-3">
                        <label for="modal-review-note" class="form-label">Catatan <code>*</code></label>
                        <div id="modal-note-display" class="text-muted break-words d-none" style="max-width: 100%; overflow-wrap: anywhere;"></div>
                        <textarea name="review_note" id="modal-review-note" class="form-control d-none" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="modal-review-instruction" class="form-label">Arahan Lebih Lanjut</label>
                        <div id="modal-instruction-display" class="text-muted break-words d-none" style="max-width: 100%; overflow-wrap: anywhere;"></div>
                        <textarea name="review_instruction" id="modal-review-instruction" class="form-control d-none"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer" id="modal-button">
          <button type="submit" class="btn btn-primary">Kirim Review</button>
        </div>

      </form>
    </div>
  </div>
</div>
@endpush


@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script>
    function handleInitConsensus(event) {
        event.preventDefault();
        swal.fire({
            title: 'Perhatian',
            text: 'Apakah Anda yakin ingin menginisiasi konsensus coaching ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Buat!',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#init_consensus').submit();
            }
        })
    }

    function handleStartCoaching(event) {
        event.preventDefault();
        swal.fire({
            title: 'Perhatian',
            text: 'Coachee yang belum merespon tidak akan tergabung dalam coaching ini. Apakah Anda yakin ingin memulai proses coaching ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Proses!',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#process_coaching').submit();
            }
        })
    }

$(document).ready(function () {
  const modal = document.getElementById('dynamicDetailModal');

  $('.btn-detail-modal').on('click', function () {
    const data = JSON.parse(this.getAttribute('data-detail'));

    $('#modal-coachee-name').text(data.coachee_name || '-');
    $('#modal-activity').text(data.activity || '-');
    $('#modal-obstacle').html(data.obstacle || '-');
    $('#modal-instructions').text(data.instructions || '-');

    const imgEl = document.getElementById('modal-image');
    const placeholder = document.getElementById('modal-image-placeholder');
    if (data.image_url) {
      imgEl.src = data.image_url;
      imgEl.style.display = 'block';
      placeholder.style.display = 'none';
    } else {
      imgEl.src = '';
      imgEl.style.display = 'none';
      placeholder.style.display = 'block';
    }

    $('#modal-session-id').val(data.session_id || '');
    $('#modal-coaching-user-id').val(data.coaching_user_id || '');

    if (data.note) {
      $('#modal-note-display').removeClass('d-none').html(data.note);
      $('#modal-review-note').addClass('d-none');
      $('#modal-instruction-display').removeClass('d-none').html(data.instructions);
      $('#modal-review-instruction').addClass('d-none');
      $('#modal-button').addClass('d-none');

      // Jangan initialize Summernote kalau tidak diperlukan
      if ($('#modal-review-note').next('.note-editor').length) {
        $('#modal-review-note').summernote('destroy');
      }
      if ($('#modal-review-instruction').next('.note-editor').length) {
        $('#modal-review-instruction').summernote('destroy');
      }

    } else {
      $('#modal-note-display').addClass('d-none');
      $('#modal-review-note').removeClass('d-none');

      if ($('#modal-review-note').next('.note-editor').length) {
        $('#modal-review-note').summernote('destroy');
      }

      $('#modal-review-note').summernote({
        height: 120,
        placeholder: 'Tulis catatan review...',
      }).summernote('code', '');

      $('#modal-instruction-display').addClass('d-none');
      $('#modal-review-instruction').removeClass('d-none');

      if ($('#modal-review-instruction').next('.note-editor').length) {
        $('#modal-review-instruction').summernote('destroy');
      }

      $('#modal-review-instruction').summernote({
        height: 120,
        placeholder: 'Tulis arahan jika ada...',
      }).summernote('code', '');

      $('#modal-button').removeClass('d-none');
    }
  });
});
</script>
@endpush
