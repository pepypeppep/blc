 <div class="card">
     <div class="card-header d-flex justify-content-between">
         <h4>{{ __('Sertifikat Coaching') }}</h4>
     </div>
     <div class="card-body">

         <div id="certificateBg"></div>


         {{-- @forelse ($coaching->signers as $signer)
                    @if ($signer->step == 1)
                        <input type="hidden" name="tte1" value="{{ $signer->user_id }}">
                    @endif
                    @if ($signer->step == 2)
                        <input type="hidden" name="tte2" value="{{ $signer->user_id }}">
                    @endif
                @empty
                    <input type="hidden" name="tte1">
                    <input type="hidden" name="tte2">
                @endforelse --}}

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
