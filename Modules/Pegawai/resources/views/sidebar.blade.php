@if (Module::isEnabled('Pegawai'))
    @adminCan('pegawai.pendidikanlanjutan')
        <li class="{{ isRoute('admin.pegawai.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.pegawai.pendidikanlanjutan.index') }}">
                <i class="fas fa-book"></i>{{ __('Pendidikan Lanjutan') }}
            </a>
        </li>
    @endadminCan
@endif
