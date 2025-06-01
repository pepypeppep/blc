@if (Module::isEnabled('Pengumuman') && Route::has('admin.pengumuman.index'))
    <li class="{{ isRoute('admin.pengumuman.*', 'active') }}">
        <a class="nav-link" href="{{ route('admin.pengumuman.index') }}">
            <i class="fas fa-book"></i><span>{{ __('Pengumuman') }}</span>
        </a>
    </li>
@endif
