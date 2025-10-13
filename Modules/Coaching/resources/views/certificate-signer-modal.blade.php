<div class="modal fade" id="certificate-signer-modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 25px 25px 0px 25px;">
                <h5 class="modal-title" id="certificateModalLabel">
                    {{ __('Choose Certificate') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="certificate-signer-form" action="{{ route('admin.coaching.certificate.store-signer') }}"
                method="POST">
                @csrf
                <input type="hidden" name="coaching_id" value="{{ $coaching->id }}">
                <input type="hidden" name="certificate_name" value="{{ $coaching->certificate_template_name }}">

                <div class="modal-body row">
                    <div class="col">
                        {{-- type --}}
                        <div class="d-flex flex-wrap">
                            @foreach ($templates as $template)
                                <div class="certificate-wrapper
                                @if ($template == $coaching->certificate_template_name) certificate-selected @endif
                                d-flex flex-column"
                                    data-certificate="{{ $template }}">
                                    <img class="img-thumbnail"
                                        src="{{ route('admin.coaching.certificate.get-image', $template) }}"
                                        alt="">
                                    <button class="btn btn-primary mt-auto" type="button"
                                        onclick="chooseCertificate('{{ $template }}')">{{ __('Choose') }}</button>
                                </div>
                            @endforeach
                        </div>


                        {{-- /type --}}

                        {{-- list penandatangan --}}
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
                        {{-- /list penandatangan --}}


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


@push('css')
    <style>
        .certificate-selected {
            border: 3px solid #007bff !important;
            border-radius: 0.25rem;
            padding: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }

        .certificate-wrapper {
            width: 300px;
            height: 300px;
            margin: 10px;
            border: 1px solid #ccc;
            cursor: pointer;
        }
    </style>
@endpush

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
    </script>



    <script>
        function chooseCertificate(id) {
            $('input[name="certificate_name"]').val(id);

            // Remove selected border from all certificates
            $('.certificate-wrapper').removeClass('certificate-selected');

            // Add selected border to the chosen certificate
            $('.certificate-wrapper[data-certificate="' + id + '"]').addClass('certificate-selected');
        }
    </script>
@endpush
