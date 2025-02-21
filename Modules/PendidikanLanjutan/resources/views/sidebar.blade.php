@if (Module::isEnabled('Language') && Route::has('admin.vacancies.index'))
    <li
        class="nav-item dropdown {{ isRoute(['admin.pendidikanlanjutan.*', 'admin.pendidikanlanjutan-vacancy.*'], 'active') }}">
        <a href="javascript:void()" class="nav-link has-dropdown"><i
                class="fas fa-book"></i><span>{{ __('ContinuingEducation') }}</span></a>

        <ul class="dropdown-menu">
            @adminCan('pendidikanlanjutan.view')
                <li class="{{ isRoute('admin.pendidikanlanjutan.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.vacancies.index') }}">
                        {{ __('Vacancy') }}
                    </a>
                </li>
                <li class="{{ isRoute('admin.verifikasi.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.vacancies.verification.index') }}">
                        {{ __('Verification') }}
                    </a>
                </li>
                <li class="{{ isRoute('admin.assesment.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.vacancies.assesment.index') }}">
                        {{ __('Assesment') }}
                    </a>
                </li>
                <li class="{{ isRoute('admin.sk.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.vacancies.sk.index') }}">
                        {{ __('Draft SK') }}
                    </a>
                </li>
                <li class="{{ isRoute('admin.report.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.vacancies.report.index') }}">
                        {{ __('Report') }}
                    </a>
                </li>
                <li class="{{ isRoute('admin.extension.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.vacancies.extension.index') }}">
                        {{ __('Extension') }}
                    </a>
                </li>
            @endadminCan
        </ul>
    </li>
@endif
