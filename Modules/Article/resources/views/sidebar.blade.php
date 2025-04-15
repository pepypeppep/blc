@if (Module::isEnabled('Language') && Route::has('admin.knowledge.index'))
    <li
        class="nav-item dropdown {{ isRoute(['admin.articles.*', 'admin.article-category.*', 'admin.article-comment.*'], 'active') }}">
        <a href="javascript:void()" class="nav-link has-dropdown"><i
                class="fas fa-newspaper"></i><span>{{ __('Manage Articles') }}</span></a>

        <ul class="dropdown-menu">
            @adminCan('article.category.view')
                <li class="{{ isRoute('admin.article-category.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.article-category.index') }}">
                        {{ __('Category List') }}
                    </a>
                </li>
            @endadminCan
            @adminCan('article.view')
                <li class="{{ isRoute('admin.articles.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.articles.index') }}">
                        {{ __('Post List') }}
                    </a>
                </li>
            @endadminCan
            @adminCan('article.comment.view')
                <li class="{{ isRoute('admin.article-comment.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.article-comment.index') }}">
                        {{ __('Post Comments') }}
                    </a>
                </li>
            @endadminCan
        </ul>
    </li>
    <li class="{{ isRoute('admin.knowledge.*', 'active') }}">
        <a class="nav-link" href="{{ route('admin.knowledge.index') }}">
            <i class="fas fa-book"></i><span>{{ __('Verification') }}</span>
        </a>
    </li>
@endif
