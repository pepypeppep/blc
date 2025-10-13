 <div class="card">
     <div class="card-header d-flex justify-content-between">
         <h4>{{ __('Sertifikat Coaching') }}</h4>
     </div>
     <div class="card-body">

         <div id="certificateTemplate">
             <img class="img-thumbnail"
                 src="{{ route('admin.coaching.certificate.get-image', $coaching->certificate_template_name) }}"
                 alt="">
         </div>

         <div class="d-flex flex-wrap align-items-center mt-3">
             @can('chooseCertificate', $coaching)
                 {{-- pilih penanda tangan modal --}}
                 <button type="button" class="btn btn-primary mr-2 mb-2" data-toggle="modal"
                     data-target="#certificate-signer-modal">{{ __('Choose Certificate') }}</button>
             @endcan

             @can('sendToBantara', $coaching)
                 {{-- Kirim ke Bantara --}}
                 <form id="send-to-bantara-form"
                     action="{{ route('admin.coaching.certificate.send-bantara', $coaching->id) }}" method="POST"
                     class="mb-2">
                     @csrf
                     <button type="button" class="btn btn-primary send-to-bantara">{{ __('Send to Bantara') }}</button>
                 </form>
             @endcan
         </div>

         {{-- table list sertifikat --}}
         {{-- column name, link for download --}}
         <div class="table-responsive">
             <table class="table table-bordered">
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
                             <td class="py-2">
                                 <a target="_blank"
                                     href="{{ route('admin.coaching.certificate.download', $coachingUser->id) }}"
                                     class="btn btn-primary">{{ __('Preview') }}</a>

                                 {{-- batalkan tte --}}
                                 {{-- <a href="#" class="btn btn-danger mt-3">{{ __('Cancel') }}</a> --}}
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

 @push('js')
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <script>
         $(document).on('click', '.send-to-bantara', function(e) {
             e.preventDefault();
             const form = $('#send-to-bantara-form');
             Swal.fire({
                 title: '{{ __('Confirmation') }}',
                 text: '{{ __('Send certificates to Bantara?') }}',
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonText: '{{ __('Yes') }}',
                 cancelButtonText: '{{ __('Cancel') }}'
             }).then((result) => {
                 if (result.isConfirmed) {
                     form.trigger('submit');
                 }
             });
         });
     </script>
 @endpush

 @push('body-bottom')
     @include('coaching::certificate-signer-modal')
 @endpush
