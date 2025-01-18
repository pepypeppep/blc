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
            @endadminCan
        </ul>
    </li>
@endif
