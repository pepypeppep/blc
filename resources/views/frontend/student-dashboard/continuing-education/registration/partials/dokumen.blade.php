@php
    use Modules\PendidikanLanjutan\app\Models\VacancyUser;
@endphp

<div class="tab-pane fade" id="itemFour-tab-pane" role="tabpanel" aria-labelledby="itemFour-tab" tabindex="0">

    <div class="dashboard__review-table " style="overflow: hidden;">
        <table class="table">
            <tbody>
                @foreach ($attachments as $attachment)
                    @if ($attachment->category == 'syarat')
                        <tr>
                            <td width="100%">{{ $attachment->vacancyattachment->name }}</td>
                            <td class="text-center">
                                <a target="_blank"
                                    href="{{ route('vacancies-participant.get.file', [$attachment->vacancyattachment->id, auth()->user()->id]) }}"
                                    class="align-middle" data-bs-toggle="tooltip" title="Lihat Berkas">
                                    <i class="fas fa-eye"></i> {{ __('View') }}
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="pt-4">
            @if ($vacancy->status === VacancyUser::STATUS_REGISTER)
                <div class="col-md-12 mb-2">
                    <div class="alert alert-warning alert-has-icon">
                        <div class="alert-icon"><i class="bi bi-hourglass-split"></i> Menunggu Pengajuan Berkas Lengkap
                        </div>
                    </div>
                </div>
            @elseif($vacancy->status === VacancyUser::STATUS_VERIFICATION)
                <div class="col-md-12 mb-2">
                    <div class="alert alert-info alert-has-icon">
                        <div class="alert-icon"><i class="bi bi-hourglass-split"></i> Menunggu Verifikasi Berkas</div>
                    </div>$vacancy->status === VacancyUser::STATUS_DONE
                </div>
            @elseif($vacancy->status === VacancyUser::STATUS_ASSESSMENT)
                <div class="col-md-12 mb-2">
                    <div class="alert alert-primary alert-has-icon">
                        <div class="alert-icon"><i class="bi bi-hourglass-split"></i> Berkas telah diverifikasi</div>
                    </div>
                </div>
            @elseif(
                $vacancy->status === VacancyUser::STATUS_ELIGIBLE ||
                    $vacancy->status === VacancyUser::STATUS_DONE ||
                    $vacancy->status === VacancyUser::STATUS_REPORT ||
                    $vacancy->status === VacancyUser::STATUS_EXTEND)
                <div class="col-md-12 mb-2">
                    <div class="alert alert-success alert-has-icon">
                        <div class="alert-icon"><i class="far fa-check-circle"></i> Berkas disetujui</div>
                    </div>
                    <div class="alert alert-success">
                        <div class="alert-body">
                            @if ($logs->where('status', 'eligible')->last())
                                Alasan :
                                {!! $logs->where('status', 'eligible')->last()->description !!}
                            @endif
                        </div>
                    </div>
                </div>
            @elseif($vacancy->status === VacancyUser::STATUS_REJECTED)
                <div class="col-md-12 mb-2">
                    <div class="alert alert-danger alert-has-icon">
                        <div class="alert-icon"><i class="bi bi-x-circle"></i> Berkas dikembalikan</div>
                    </div>
                    <div class="alert alert-danger">
                        <div class="alert-body">
                            @if ($logs->where('status', 'rejected')->last())
                                Alasan :
                                {!! $logs->where('status', 'rejected')->last()->description !!}
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12 mb-2">
                    <div class="alert alert-danger alert-has-icon">
                        <div class="alert-icon"><i class="bi bi-question-circle"></i> Status Berkas
                            {{ $vacancy->status }}
                        </div>
                    </div>
                    <div class="alert alert-danger">
                        <div class="alert-body">
                            @if ($logs->last())
                                Alasan :
                                {!! $logs->last()->description !!}
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>


        <table class="table table-borderless">
            <div class="blog__details-bottom">
                <div class="row">
                    <div class="col-8">
                        <div class="tg-post-tag">
                            <h5 class="tag-title">Status Assesment :</h5>
                            <ul class="list-wrap p-0 mb-0">
                                @if (
                                    $vacancy->status === VacancyUser::STATUS_ELIGIBLE ||
                                        $vacancy->status === VacancyUser::STATUS_DONE ||
                                        $vacancy->status === VacancyUser::STATUS_REPORT ||
                                        $vacancy->status === VacancyUser::STATUS_EXTEND)
                                    <li><a class="bg-success text-white " href="javascript:;">
                                            <strong>Sudah Assesmen</strong> </a></li>
                                    <li>
                                        <span tabindex="0" class="fa fa-info-circle text-dark"
                                            data-bs-toggle="popover" data-bs-trigger="hover focus"
                                            data-bs-placement="top"
                                            data-bs-content="Alasan : {{ $logs->whereIn('status', ['eligible', 'done'])->last()->description }}">
                                        </span>
                                    </li>
                                @else
                                    <li><a class="bg-warning text-white " href="javascript:;">
                                            <strong>Belum assesmen</strong> </a></li>
                                    <li>
                                        <span tabindex="0" class="fa fa-info-circle text-dark"
                                            data-bs-toggle="popover" data-bs-trigger="hover focus"
                                            data-bs-placement="top"
                                            data-bs-content="Alasan : {{ $logs->last()->description }}">
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </table>
    </div>
</div>
