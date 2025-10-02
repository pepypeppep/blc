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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col">
                        <div class="row">
                            <div class="col mt-4">
                                <button class="btn btn-primary mt-auto" type="submit">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col">
                    <div id="previewContainer" class="d-flex flex-wrap">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@push('js')
    <script src="{{ asset('backend/js/default/courses.js') }}"></script>

    <script>
        function previewHTML(name, parent) {
            // get html from endpoint
            fetch(`{{ route('admin.coaching.certificate.get-html') }}/${name}`).then(response => response.text())
                .then(
                    html => {
                        const iframeWrapper = document.createElement('div');
                        iframeWrapper.className = 'iframe-wrapper';
                        // preview.style =
                        // 'width:500px; height:300px; border:1px solid #ccc; padding: 100px;';

                        const iframe = document.createElement('iframe');
                        iframe.id = 'previewFrame';
                        iframe.className = 'iframe-content';
                        iframe.style =
                            'width:1122px; height:800px; border:1px solid #ccc; transform:scale(0.4); transform-origin: 0 0;';
                        iframeWrapper.appendChild(iframe);
                        parent.appendChild(iframeWrapper);


                        // const iframe = document.getElementById('previewFrame');
                        const doc = iframe.contentDocument || iframe.contentWindow.document;
                        doc.open();
                        doc.write(html);
                        doc.close();

                        // render certificate name below iframe
                        const certificateName = document.createElement('div');
                        certificateName.style = 'position: absolute; bottom: 10px; left: 10px;';
                        certificateName.innerHTML = name;
                        iframeWrapper.appendChild(certificateName);

                        // render select button on iframe wrapper, under iframe 
                        const selectButton = document.createElement('button');
                        selectButton.className = 'btn btn-primary';
                        selectButton.innerHTML = 'Select';
                        selectButton.onclick = function() {
                            console.log(name);
                            chooseCertificate(name);
                            $('#certificate-type-modal').modal('hide');
                        };
                        selectButton.style = 'position: absolute; bottom: 10px; right: 10px;';
                        iframeWrapper.appendChild(selectButton);
                    });
        }

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


            // fetch list template
            fetch("{{ route('admin.coaching.certificate.list-template') }}").then(response => response.json()).then(
                data => {
                    data.forEach(template => {
                        const parent = document.getElementById('previewContainer');
                        previewHTML(template, parent);
                    });
                });




        })
        $('.input-daterange').datepicker({
            todayBtn: "linked",
            clearBtn: true,
            autoclose: false,
            format: "yyyy-mm-dd"
        });

        @if ($coaching?->certificate_template_name != null)
            $('#certificateBg').html(
                '<img src="{{ route('admin.certificate-builder.getBg', $coaching->certificate_id) }}" alt="" style="width: 100%; height: auto;" />'
            );
        @endif


        function chooseCertificate(id) {
            $('input[name="certificate_name"]').val(id);
            // submit form
            $('#certificate-type-form').submit();
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

        .iframe-wrapper {
            position: relative;
            width: 500px;
            height: 400px;
            margin: 10px;
            border: 1px solid #ccc;
            // padding-top: 56.25%;
            /* 16:9 ratio, adjust as needed */
            // overflow: hidden;
        }

        .iframe-content {
            margin: 10px;
        }
    </style>
@endpush
