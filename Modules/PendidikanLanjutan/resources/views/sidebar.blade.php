@if (Module::isEnabled('Language') && Route::has('admin.vacancies.index'))
    @adminCan('pendidikanlanjutan.view')
        <li class="{{ isRoute('admin.pendidikanlanjutan.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.index') }}">
                <i class="fas fa-book"></i>{{ __('Vacancy') }}
            </a>
        </li>
        <li class="{{ isRoute('admin.verifikasi.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.verification.index') }}">
                <i class="fas fa-book"></i>{{ __('Verification') }}
            </a>
        </li>
        <li class="{{ isRoute('admin.assesment.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.assesment.index') }}">
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
        <li class="{{ isRoute('admin.extension.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.vacancies.extension.index') }}">
                <i class="fas fa-book"></i>{{ __('Extension') }}
            </a>
        </li>
    @endadminCan
@endif
