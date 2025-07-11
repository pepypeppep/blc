<div class="tab-pane fade" id="itemSix-tab-pane" role="tabpanel" aria-labelledby="itemSix-tab" tabindex="0">
    {{-- <div class="faq__wrap "> --}}
    <div class="accordion" id="accordionExample">
        @if ($lampirans->isEmpty())
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target={{ '#collapse' . 1 }} aria-expanded="false" aria-controls={{ '#collapse' . 1 }}>
                        Perjanjian Kinerja
                    </button>
                </h2>
                <div id={{ 'collapse' . 1 }} class="accordion-collapse collapse" data-bs-parent="#accordionExample"
                    style="">
                    <div class="accordion-body">
                        <object data={{ Route('vacancies-participant.get.file', [1, auth()->user()->id]) }}
                            width="100%" height="500px">
                            <span class="full-width rounded p-2 bg-danger text-white">Dokumen
                                Tidak Ditemukan</span>
                        </object>

                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target={{ '#collapse' . 2 }} aria-expanded="false" aria-controls={{ '#collapse' . 2 }}>
                        SK
                    </button>
                </h2>
                <div id={{ 'collapse' . 2 }} class="accordion-collapse collapse" data-bs-parent="#accordionExample"
                    style="">
                    <div class="accordion-body">
                        <object data={{ Route('vacancies-participant.get.file', [2, auth()->user()->id]) }}
                            width="100%" height="500px">
                            <span class="full-width rounded p-2 bg-danger text-white">Dokumen
                                Tidak Ditemukan</span>
                        </object>

                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target={{ '#collapse' . 3 }} aria-expanded="false" aria-controls={{ '#collapse' . 3 }}>
                        Petikan
                    </button>
                </h2>
                <div id={{ 'collapse' . 3 }} class="accordion-collapse collapse" data-bs-parent="#accordionExample"
                    style="">
                    <div class="accordion-body">
                        <object data={{ Route('vacancies-participant.get.file', [3, auth()->user()->id]) }}
                            width="100%" height="500px">
                            <span class="full-width rounded p-2 bg-danger text-white">Dokumen
                                Tidak Ditemukan</span>
                        </object>

                    </div>
                </div>
            </div>
        @else
            @foreach ($lampirans as $lampiran)
                @if ($lampiran->category == 'lampiran')
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target={{ '#collapse' . $lampiran->id }} aria-expanded="false"
                                aria-controls={{ '#collapse' . $lampiran->id }}>
                                {{ $lampiran->name }}
                            </button>
                        </h2>
                        <div id={{ 'collapse' . $lampiran->id }} class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                @if ($attachments->where('vacancy_attachment_id', $lampiran->id)->where('vacancy_user_id', $vacancyUser->id)->first())
                                    <object
                                        data={{ Route('vacancies-participant.get.file', [$attachments->where('vacancy_attachment_id', $lampiran->id)->where('vacancy_user_id', $vacancyUser->id)->first()->vacancy_attachment_id, $vacancyUser->id]) }}
                                        width="100%" height="500px">
                                        <span class="full-width rounded p-2 bg-danger text-white">Dokumen
                                            Tidak Ditemukan</span>
                                    </object>
                                @else
                                    <span class="full-width rounded p-2 bg-danger text-white">Dokumen
                                        Tidak Ditemukan</span>
                                @endif

                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

    </div>
    {{-- </div> --}}

</div>
