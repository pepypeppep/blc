<div class="dashboard__sidebar-wrap">
    <div class="dashboard__sidebar-title mb-20">
        <h6 class="title">{{ __('Welcome') }}, {{ userAuth()->name }}</h6>
    </div>
    <nav class="dashboard__sidebar-menu">
        <ul class="list-wrap">
            <li class="{{ Route::is('instructor.dashboard') ? 'active' : '' }}">
                <a href="{{ route('instructor.dashboard') }}">
                    <img src="{{ asset('uploads/website-images/dashboard.svg') }}">{{ __('Dashboard') }}</a>
            </li>
            <li class="{{ Route::is('instructor.courses.*') ? 'active' : '' }}">
                <a href="{{ route('instructor.courses.index') }}">
                    <i class="flaticon-mortarboard"></i>
                    {{ __('Courses') }}
                </a>
            </li>
            <li class="{{ Route::is('instructor.lesson-questions.index') ? 'active' : '' }}">
                <a href="{{ route('instructor.lesson-questions.index') }}">
                    <img src="{{ asset('uploads/website-images/questions.svg') }}">
                    {{ __('Lesson Questions') }}
                </a>
            </li>

            <li class="{{ Route::is('instructor.announcements.index') ? 'active' : '' }}">
                <a href="{{ route('instructor.announcements.index') }}">
                    <img src="{{ asset('uploads/website-images/announcement.svg') }}">
                    {{ __('Announcement') }}
                </a>
            </li>


        </ul>
    </nav>
    <div class="dashboard__sidebar-title mt-30 mb-20">
        <h6 class="title">{{ __('User') }}</h6>
    </div>
    <nav class="dashboard__sidebar-menu">
        <ul class="list-wrap">

            {{-- <li class="{{ Route::is('instructor.setting.index') ? 'active' : '' }}">
                <a href="{{ route('instructor.setting.index') }}">
                    <i class="flaticon-user"></i>
                    {{ __('Profile Settings') }}
                </a>
            </li> --}}
            <li>
                <a href="{{ route('instructor.notifications') }}">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C13.1 22 14 21.1 14 20H10C10 21.1 10.9 22 12 22ZM18 16V11C18 7.93 16.37 5.36 13.5 4.68V4C13.5 3.17 12.83 2.5 12 2.5C11.17 2.5 10.5 3.17 10.5 4V4.68C7.64 5.36 6 7.92 6 11V16L4 18V19H20V18L18 16Z" fill="currentColor"/>
                    </svg>
                    {{ __('Notifications') }}
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); $('#logout-form').trigger('submit');">
                    <img src="{{ asset('uploads/website-images/logout.svg') }}">
                    {{ __('Logout') }}
                </a>
            </li>
        </ul>
    </nav>
</div>

{{-- start admin logout form --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
{{-- end admin logout form --}}
