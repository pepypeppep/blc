@if (Module::isEnabled('Language') && Route::has('admin.course.index'))
    @php
        $pendingCourseCount = \App\Models\Course::where('is_approved', 'pending')->count();
        if (auth()->user()?->hasRole('Super Admin') || adminAuth()->hasRole('Super Admin')) {
            $courseIds = \App\Models\Course::where('status', 'active')->get()->pluck('id');
        } else {
            $courseIds = \App\Models\Course::where('instansi_id', auth()->user()->instansi_id)->where('status', 'active')->get()->pluck('id');
        }
            $pendingEnrolledCount = \Modules\Order\app\Models\Enrollment::whereIn('course_id', $courseIds)->whereNull('has_access')->count();
    @endphp
    <li
        class="nav-item dropdown {{ isRoute(['admin.courses.*', 'admin.course-category.*', 'admin.course-filter.*', 'admin.course-language.*', 'admin.course-level.*', 'admin.course-review.*', 'admin.course-delete-request.*', 'admin.course-sub-category.*'], 'active') }}">
        <a href="javascript:void()" class="nav-link has-dropdown"><i class="fas fa-graduation-cap"></i><span
                class="{{ $pendingCourseCount > 0 ? 'beep parent' : '' }}">{{ __('Manage Courses') }}</span></a>

        <ul class="dropdown-menu">
            <li class="{{ isRoute('admin.courses.*', 'active') }}">
                <a class="nav-link" href="{{ route('admin.courses.index') }}">
                    {{ __('Courses') }}
                    @if ($pendingCourseCount > 0)
                        <small class="badge badge-danger ml-2">{{ $pendingCourseCount }}</small>
                    @endif
                    @if ($pendingEnrolledCount > 0)
                        <small class="badge badge-info ml-2" title="{{ __('Pending Enrollments') }}">{{ $pendingEnrolledCount }}</small>
                    @endif
                </a>
            </li>
            @if (checkAdminHasPermission('course.category.management'))
                <li
                    class="{{ isRoute('admin.course-category.*', 'active') }} {{ isRoute('admin.course-sub-category.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.course-category.index') }}">
                        {{ __('Categories') }}
                    </a>
                </li>
            @endif

            @if (checkAdminHasPermission('course.language.management'))
                <li class="{{ isRoute('admin.course-language.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.course-language.index') }}">
                        {{ __('languages') }}
                    </a>
                </li>
            @endif

            @if (checkAdminHasPermission('course.level.management'))
                <li class="{{ isRoute('admin.course-level.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.course-level.index') }}">
                        {{ __('levels') }}
                    </a>
                </li>
            @endif

            @if (checkAdminHasPermission('course.review.management'))
                <li class="{{ isRoute('admin.course-review.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.course-review.index') }}">
                        {{ __('Course Reviews') }}
                    </a>
                </li>
            @endif

            @if (getAdminAuthRole() == 'Super Admin')
                <li class="{{ isRoute('admin.course-tos.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.course-tos.create') }}">
                        {{ __('Term of Service') }}
                    </a>
                </li>
            @endif

            {{-- @if (getAdminAuthRole() != 'Admin OPD')
                <li class="{{ isRoute('admin.course-delete-request.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.course-delete-request.index') }}">
                        {{ __('Course Delete Requests') }}
                    </a>
                </li>
            @endif --}}
        </ul>
    </li>
@endif
