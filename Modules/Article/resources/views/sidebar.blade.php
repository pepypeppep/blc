@if (Module::isEnabled('Language') && Route::has('admin.knowledge.index'))
    <li class="{{ isRoute('admin.knowledge.*', 'active') }}">
        <a class="nav-link" href="{{ route('admin.knowledge.index') }}">
            <i class="fas fa-book"></i><span>{{ __('Verification') }}</span>
        </a>
    </li>
    <li class="{{ isRoute('admin.knowledge-comment.*', 'active') }}">
        <a class="nav-link" href="{{ route('admin.knowledge-comments.index') }}">
            <i class="fas fa-comment-dots"></i><span>{{ __('Article Comments') }}</span>
        </a>
    </li>
@endif
