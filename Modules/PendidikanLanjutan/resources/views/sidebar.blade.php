@if (Module::isEnabled('Language') && Route::has('admin.vacancies.index'))
    <li class="nav-item dropdown {{ isRoute(['vacancies.master.*'], 'active') }}">
        <a href="javascript:void()" class="nav-link has-dropdown"><i class="fas fa-graduation-cap"></i><span
                class="beep parent">{{ __('Master') }}</span></a>

        <ul class="dropdown-menu">
            <li class="{{ isRoute('vacancies.master.file.*', 'active') }}">
                <a class="nav-link" href="{{ route('admin.vacancies.master.file.index') }}">
                    {{ __('Berkas Laporan') }}
                </a>
            </li>
            <li class="{{ isRoute('vacancies.master.schedule.*', 'active') }}">
                <a class="nav-link" href="{{ route('admin.vacancies.master.schedule.index') }}">
                    {{ __('Schedule') }}
                </a>
            </li>
        </ul>
    </li>
    @adminCan('pendidikanlanjutan.pendaftar')
        <li class="{{ isRoute('admin.peserta.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.peserta.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Pegawai Terdaftar') }}</span>
            </a>
        </li>
    @endadminCan
    <li class="{{ isRoute('admin.pendidikanlanjutan.*', 'active') }}">
        <a class="nav-link" href="{{ route('admin.vacancies.index') }}">
            <i class="fas fa-book"></i><span>Renbut</span>
        </a>
    </li>
    @adminCan('pendidikanlanjutan.verifikasi')
        <li class="{{ isRoute('admin.verifikasi.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.verification.index') }}">
                <i class="fas fa-book"></i><span>{{ __('File Verification') }}</span>
                <span class="badge badge-pill badge-primary w-auto" id="verif">0</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.assessment.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.assessment.index') }}">
                <i class="fas fa-book"></i><span>Assesment</span>
                <span class="badge badge-pill badge-primary w-auto" id="asses">0</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.sk.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.sk.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Draft SK') }}</span>
                <span class="badge badge-pill badge-primary w-auto" id="sk">0</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.report.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.report.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Lecture Report') }}</span>
                <span class="badge badge-pill badge-primary w-auto" id="rpt">0</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.extend.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.activation.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Reactivation') }}</span>
                <span class="badge badge-pill badge-primary w-auto" id="act">0</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.extend.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.extend.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Extension') }}</span>
                <span class="badge badge-pill badge-primary w-auto" id="ext">0</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.done.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.done.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Done') }}</span>
            </a>
        </li>
    @endadminCan
    {{-- @endadminCan --}}

    {{-- @if (request()->routeIs('admin.vacancies.*')) --}}
        <script src="{{ asset('global/js/jquery-3.7.1.min.js') }}"></script>
        <script>
            $.ajax({
                url: "{{ route('admin.vacancies.sidebar.counter') }}",
                success: function(data) {
                    $.each(data, function(key, value) {
                        $('#'+key).text(value);
                    });
                }
            })
        </script>
    {{-- @endif --}}
@endif
