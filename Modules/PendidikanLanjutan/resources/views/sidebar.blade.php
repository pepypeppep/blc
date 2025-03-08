@if (Module::isEnabled('Language') && Route::has('admin.vacancies.index'))
    @adminCan('pendidikanlanjutan.view')
        <li class="{{ isRoute('admin.pendidikanlanjutan.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.index') }}">
                <i class="fas fa-book"></i>{{ __('Vacancy') }}
            </a>
        </li>
        @adminCan('pendidikanlanjutan.pendaftar')
            <li class="{{ isRoute('admin.peserta.*', 'active') }}">
                <a class="nav-link" href="{{ route('admin.vacancies.peserta.index') }}">
                    <i class="fas fa-book"></i>{{ __('Daftar Peserta') }}
                </a>
            </li>
        @endadminCan
        @adminCan('pendidikanlanjutan.verifikasi')
            <li class="{{ isRoute('admin.verifikasi.*', 'active') }}">
                <a class="nav-link" href="{{ route('admin.vacancies.verification.index') }}">
                    <i class="fas fa-book"></i>{{ __('Verification') }}
                </a>
            </li>
        @endadminCan
        <li class="{{ isRoute('admin.assessment.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.assessment.index') }}">
                <i class="fas fa-book"></i>{{ __('Assesment') }}
            </a>
        </li>
        <li class="{{ isRoute('admin.sk.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.sk.index') }}">
                <i class="fas fa-book"></i>{{ __('Draft SK') }}
            </a>
        </li>
        <li class="{{ isRoute('admin.report.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.report.index') }}">
                <i class="fas fa-book"></i>{{ __('Report') }}
            </a>
        </li>
        <li class="{{ isRoute('admin.extend.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.extend.index') }}">
                <i class="fas fa-book"></i>{{ __('Extension') }}
            </a>
        </li>
        <li class="{{ isRoute('admin.done.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.done.index') }}">
                <i class="fas fa-book"></i>{{ __('Done') }}
            </a>
        </li>
    @endadminCan
@endif
