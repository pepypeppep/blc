<div class="modal fade" id="certificate-signer-modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 25px 25px 0px 25px;">
                <h5 class="modal-title" id="certificateModalLabel">
                    {{ __('Choose Signer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="certificate-signer-form" action="{{ route('admin.coaching.certificate.store-signer') }}"
                method="POST">
                @csrf
                <input type="hidden" name="coaching_id" value="{{ $coaching->id }}">
                <div class="modal-body row">
                    <div class="col">
                        <div class="row">
                            {{-- form --}}



                            {{-- TTE Depan --}}
                            <div class="col mt-4">
                                <div class="partner_instructor_list">
                                    <label for="cpacity">{{ __('TTE Depan') }}</label>
                                    <select class="tte_select" id="front_tte" name="front_tte">
                                        @foreach ($coaching->signers as $signer)
                                            @if ($signer->step == 2)
                                                <option selected value="{{ $signer->user_id }}">
                                                    {{ $signer->user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- /TTE Depan --}}

                            {{-- TTE Belakang --}}
                            <div class="col mt-4">
                                <div class="partner_instructor_list">
                                    <label for="cpacity">{{ __('TTE Belakang') }}</label>
                                    <select class="tte_select" id="back_tte" name="back_tte">
                                        @foreach ($coaching->signers as $signer)
                                            @if ($signer->step == 1)
                                                <option selected value="{{ $signer->user_id }}">
                                                    {{ $signer->user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- /TTE Belakang --}}

                        </div>

                        <div class="row">
                            <div class="col mt-4">
                                <button class="btn btn-primary mt-auto" type="submit">{{ __('Save') }}</button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@push('js')
    <script>
        $(document).ready(function() {
            $(".tte_select").select2({
                ajax: {
                    url: "{{ route('admin.coaching.certificate.list-signer') }}",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: false,
                            },
                        };
                    },
                    cache: true,
                },
                dropdownParent: $("#certificate-signer-modal"),
                // minimumInputLength: 1,
                templateResult: (param) => {
                    if (param.loading) {
                        return param.text;
                    }

                    var $container = $(
                        "<div class='select2-result-repository clearfix'>" +
                        // "<div class='select2-result-repository__avatar'><img src='" +
                        // "/" +
                        // param.image +
                        // "' /></div>" +
                        "<div class='select2-result-repository__meta'>" +
                        "<div class='select2-result-repository__title'>" +
                        param.name +
                        "</div>" +
                        "<div class='select2-result-repository__description'>" +
                        param.jabatan +
                        "</div>" +
                        "</div>" +
                        "</div>"
                    );

                    return $container;
                },
                templateSelection: (repo) => {
                    return repo.name || repo.text;
                },
            });

        })
        $('.input-daterange').datepicker({
            todayBtn: "linked",
            clearBtn: true,
            autoclose: false,
            format: "yyyy-mm-dd"
        });

        @if ($coaching?->certificate_id != null)
            $('#certificateBg').html(
                '<img src="{{ route('admin.certificate-builder.getBg', $coaching->certificate_id) }}" alt="" style="width: 100%; height: auto;" />'
            );
        @endif
    </script>
@endpush
