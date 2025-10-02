 <div class="card">
     <div class="card-header d-flex justify-content-between">
         <h4>{{ __('Sertifikat Coaching') }}</h4>
     </div>
     <div class="card-body">

         <div id="certificateTemplate"></div>

         {{-- pilih sertifikat modal --}}
         <button type="button" class="btn btn-primary mt-3" data-toggle="modal"
             data-target="#certificate-type-modal">{{ __('Choose Certificate') }}</button>
         {{-- pilih penanda tangan modal --}}
         <button type="button" class="btn btn-primary mt-3" data-toggle="modal"
             data-target="#certificate-signer-modal">{{ __('Choose Signer') }}</button>
         {{-- Generate Sertifikat --}}
         <a href="{{ route('admin.coaching.certificate.generate', $coaching->id) }}"
             class="btn btn-primary mt-3">{{ __('Generate Certificate') }}</a>

         {{-- Kirim ke Bantara --}}
         <a href="{{ route('admin.coaching.certificate.send', $coaching->id) }}"
             class="btn btn-primary mt-3">{{ __('Send to Bantara') }}</a>
         {{-- Batalkan TTE --}}
         <a href="#" class="btn btn-danger mt-3">{{ __('Cancel TTE Bantara') }}</a>

         {{-- table list sertifikat --}}
         {{-- column name, link for download --}}
         <div class="table-responsive">

             <table class="table">
                 <thead>
                     <tr>
                         <th>{{ __('Name') }}</th>
                         <th>{{ __('Status') }}</th>
                         <th>{{ __('Download') }}</th>
                     </tr>
                 </thead>
                 <tbody>
                     @forelse ($coaching->completedCoachingUsers as $coachingUser)
                         <tr>
                             <td>{{ $coachingUser->coachee->name }}</td>
                             <td>
                                 @if ($coachingUser->signed_certificate_path)
                                     <span class="badge badge-success">{{ __('TTE') }}</span>
                                 @elseif ($coachingUser->certificate_uuid)
                                     <span class="badge badge-warning">{{ __('Menunggu TTE') }}</span>
                                 @else
                                     <span class="badge badge-danger">{{ __('Belum TTE') }}</span>
                                 @endif
                             </td>
                             <td>
                                 <a target="_blank"
                                     href="{{ route('admin.coaching.certificate.download', $coachingUser->id) }}"
                                     class="btn btn-primary">{{ __('Preview') }}</a>
                             </td>
                         </tr>
                     @empty
                         <tr>
                             <td colspan="2">{{ __('No data found') }}</td>
                         </tr>
                     @endforelse
                 </tbody>
             </table>
         </div>


     </div>
 </div>

 @push('body-bottom')
     @include('coaching::certificate-type-modal')
     @include('coaching::certificate-signer-modal')
 @endpush

 @push('js')
     <script>
         function previewTemplate(name, parent) {
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
                         iframe.style =
                             'width:1122px; height:800px; border:1px solid #ccc; transform:scale(0.4); transform-origin: 0 0;';
                         iframeWrapper.appendChild(iframe);
                         parent.appendChild(iframeWrapper);


                         // const iframe = document.getElementById('previewFrame');
                         const doc = iframe.contentDocument || iframe.contentWindow.document;
                         doc.open();
                         doc.write(html);
                         doc.close();
                     });
         }

         $(document).ready(function() {
             const parent = document.getElementById('certificateTemplate');
             previewTemplate('{{ $coaching->certificate_template_name }}', parent);
         });
     </script>
 @endpush
