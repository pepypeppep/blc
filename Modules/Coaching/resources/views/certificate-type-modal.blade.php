<div class="modal fade" id="certificate-type-modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="certificate-type-form" action="{{ route('admin.coaching.certificate.store-type') }}"
                method="POST">
                @csrf
                <input type="hidden" name="coaching_id" value="{{ $coaching->id }}">
                <input type="hidden" name="certificate_name" value="">
                <div class="modal-header" style="padding: 25px 25px 0px 25px;">
                    <h5 class="modal-title" id="certificateModalLabel">
                        {{ __('Choose Certificate') }}</h5>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap">
                        @foreach ($templates as $template)
                            <div class="certificate-wrapper d-flex flex-column">
                                <img class="img-thumbnail"
                                    src="{{ route('admin.coaching.certificate.get-image', $template) }}" alt="">
                                <button class="btn btn-primary mt-auto" type="button"
                                    onclick="chooseCertificate('{{ $template }}')">{{ __('Choose') }}</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@push('js')
    <script>
        function chooseCertificate(id) {
            $('input[name="certificate_name"]').val(id);
            $('#certificate-type-form').submit();
        }
    </script>
@endpush

@push('css')
    <style>
        .certificate-wrapper {
            width: 300px;
            height: 300px;
            margin: 10px;
            border: 1px solid #ccc;
            cursor: pointer;
        }
    </style>
@endpush
