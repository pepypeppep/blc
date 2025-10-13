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


         {{-- pilih penanda tangan modal --}}
         <button type="button" class="btn btn-primary mt-3" data-toggle="modal"
             data-target="#certificate-signer-modal">{{ __('Choose Certificate') }}</button>

         {{-- Kirim ke Bantara --}}
         <a href="{{ route('admin.coaching.certificate.send', $coaching->id) }}"
             class="btn btn-primary mt-3">{{ __('Send to Bantara') }}</a>


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

 @push('body-bottom')
     @include('coaching::certificate-signer-modal')
 @endpush
