<div class="modal fade" id="certificate-type-modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="certificate-type-form" action="{{ route('admin.coaching.certificate.store-type') }}"
                method="POST">
                @csrf
                <input type="hidden" name="coaching_id" value="{{ $coaching->id }}">
                <input type="hidden" name="certificate_builder_id" value="">
                <div class="modal-header" style="padding: 25px 25px 0px 25px;">
                    <h5 class="modal-title" id="certificateModalLabel">
                        {{ __('Choose Certificate') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col">
                        <div class="row">
                            @foreach ($certificates as $certificate)
                                <div class="col-md-3 d-flex flex-column">
                                    <img src="{{ route('admin.certificate-builder.getBg', $certificate->id) }}"
                                        alt="" style="width: 100%; height: auto;">
                                    <button type="button" class="btn btn-primary mt-auto"
                                        onclick="chooseCertificate({{ $certificate->id }})">{{ __('Choose') }}</button>
                                </div>
                            @endforeach
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
    <script src="{{ asset('backend/js/default/courses.js') }}"></script>

    <script>
        $(document).ready(function() {
            const $name = $("#title"),
                $slug = $("#slug");

            $name.on("keyup", function(e) {
                $slug.val(convertToSlug($name.val()));
            });

            function convertToSlug(text) {
                return text
                    .toLowerCase()
                    .replace(/[^a-z\s-]/g, "") // Remove all non-word characters (except -)
                    .replace(/\s+/g, "-") // Replace spaces with -
                    .replace(/-+/g, "-"); // Replace multiple - with single -
            }




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


        function chooseCertificate(id) {
            $('input[name="certificate_builder_id"]').val(id);
            $('#certificateBg').html(
                '<img src="{{ route('admin.certificate-builder.getBg', ':id') }}" alt="" style="width: 100%; height: auto;" />'
                .replace(':id', id));
        }
    </script>
@endpush

@push('css')
    <style>
        .dd-custom-css {
            position: absolute;
            will-change: transform;
            top: 0px;
            left: 0px;
            transform: translate3d(0px, -131px, 0px);
        }

        .max-h-400 {
            min-height: 400px;
        }
    </style>
@endpush
