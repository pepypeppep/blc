@if (Module::isEnabled('Language') && Route::has('admin.vacancies.index'))
    {{-- @adminCan('pendidikanlanjutan.view') --}}
    @adminCan('pendidikanlanjutan.pendaftar')
        <li class="{{ isRoute('admin.peserta.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.peserta.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Pegawai') }}</span>
            </a>
        </li>
    @endadminCan
    <li class="{{ isRoute('admin.pendidikanlanjutan.*', 'active') }}">
        <a class="nav-link" href="{{ route('admin.vacancies.index') }}">
            <i class="fas fa-book"></i><span>{{ __('Vacancy') }}</span>
        </a>
    </li>
    @adminCan('pendidikanlanjutan.verifikasi')
        <li class="{{ isRoute('admin.verifikasi.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.verification.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Verification') }}</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.assessment.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.assessment.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Assesment') }}</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.sk.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.sk.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Draft SK') }}</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.report.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.report.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Report') }}</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.extend.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.extend.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Extension') }}</span>
            </a>
        </li>
        <li class="{{ isRoute('admin.done.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.done.index') }}">
                <i class="fas fa-book"></i><span>{{ __('Done') }}</span>
            </a>
        </li>
    @endadminCan
    {{-- @endadminCan --}}
@endif
