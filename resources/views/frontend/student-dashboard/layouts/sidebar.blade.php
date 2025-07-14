<div class="dashboard__sidebar-wrap">
    <div class="dashboard__sidebar-title mb-20">
        <h6 class="title">{{ __('Welcome') }}, {{ userAuth()->name }}</h6>
    </div>
    <nav class="dashboard__sidebar-menu">
        <ul class="list-wrap">
            <li class="{{ Route::is('student.dashboard') ? 'active' : '' }}">
                <a href="{{ route('student.dashboard') }}">
                    <img src="{{ asset('uploads/website-images/dashboard.svg') }}">
                    {{ __('Dashboard') }}
                </a>
            </li>
            <li class="{{ Route::is('student.pengetahuan.*') ? 'active' : '' }}">
                <a href="{{ route('student.pengetahuan.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                        <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
                    </svg>
                    {{ __('Pengetahuan') }}
                </a>
            </li>
            <li class="">
                <a class="d-flex align-items-center text-decoration-none" data-bs-toggle="collapse" href="#courseMenu"
                    role="button" aria-expanded="true" aria-controls="courseMenu" id="courseMenuToggle">
                    <img src="{{ asset('uploads/website-images/announcement.svg') }}" width="16" height="16">
                    {{ __('Course') }}
                    <i class="fa fa-chevron-down ms-auto collapse-icon " style="font-size: 10px;"></i>
                </a>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.enrolled-courses') || Route::is('student.reviews.index') || Route::is('student.quiz-attempts') || Route::is('student.follow-up-action.index') ? 'show' : '' }}"
                    id="courseMenu">
                    <li class="{{ Route::is('student.enrolled-courses') ? 'active' : '' }}">
                        <a href="{{ route('student.enrolled-courses') }}">
                            <i class="flaticon-mortarboard"></i>{{ __('Enrolled Courses') }}</a>
                    </li>
                </ul>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.enrolled-courses') || Route::is('student.reviews.index') || Route::is('student.quiz-attempts') || Route::is('student.follow-up-action.index') ? 'show' : '' }}"
                    id="courseMenu">
                    <li class="{{ Route::is('student.reviews.index') ? 'active' : '' }}">
                        <a href="{{ route('student.reviews.index') }}">
                            <img src="{{ asset('uploads/website-images/reviews.svg') }}">{{ __('Reviews') }}</a>
                    </li>
                </ul>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.enrolled-courses') || Route::is('student.reviews.index') || Route::is('student.quiz-attempts') || Route::is('student.follow-up-action.index') ? 'show' : '' }}"
                    id="courseMenu">
                    <li class="{{ Route::is('student.quiz-attempts') ? 'active' : '' }}">
                        <a href="{{ route('student.quiz-attempts') }}">
                            <img src="{{ asset('uploads/website-images/quiz.svg') }}">{{ __('My Quiz Attempts') }}</a>
                    </li>
                </ul>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.enrolled-courses') || Route::is('student.reviews.index') || Route::is('student.quiz-attempts') || Route::is('student.follow-up-action.index') ? 'show' : '' }}"
                    id="courseMenu">
                    <li class="{{ Route::is('student.follow-up-action.index') ? 'active' : '' }}">
                        <a href="{{ route('student.follow-up-action.index') }}">
                            <i class="fa fa-receipt"></i>{{ __('FollowUpAction') }}</a>
                    </li>
                </ul>
            </li>

            <li class="">
                <a class="d-flex align-items-center text-decoration-none" data-bs-toggle="collapse"
                    href="#continuingEducationMenu" role="button" aria-expanded="true"
                    aria-controls="continuingEducationMenu" id="continuingEducationToggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-backpack" viewBox="0 0 16 16">
                        <path d="M4.04 7.43a4 4 0 0 1 7.92 0 .5.5 0 1 1-.99.14 3 3 0 0 0-5.94 0 .5.5 0 1 1-.99-.14M4 9.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm1 .5v3h6v-3h-1v.5a.5.5 0 0 1-1 0V10z"/>
                        <path d="M6 2.341V2a2 2 0 1 1 4 0v.341c2.33.824 4 3.047 4 5.659v5.5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5V8a6 6 0 0 1 4-5.659M7 2v.083a6 6 0 0 1 2 0V2a1 1 0 0 0-2 0m1 1a5 5 0 0 0-5 5v5.5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5V8a5 5 0 0 0-5-5"/>
                    </svg>
                    {{ __('ContinuingEducation') }}
                    <i class="fa fa-chevron-down ms-auto collapse-icon " style="font-size: 10px;"></i>
                </a>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.continuing-education') || Route::is('student.continuing-education.registration') ? 'show' : '' }}"
                    id="continuingEducationMenu">
                    <li
                        class="list-group-item ps-3 text-sm {{ Route::is('student.continuing-education') ? 'active' : '' }}">
                        <a href="{{ route('student.continuing-education') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-briefcase" viewBox="0 0 16 16">
                                <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                            {{ __('Lowongan') }}
                        </a>
                    </li>
                </ul>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.continuing-education') || Route::is('student.continuing-education.registration') ? 'show' : '' }}"
                    id="continuingEducationMenu">
                    <li
                        class="list-group-item ps-3 text-sm {{ Route::is('student.continuing-education.registration') ? 'active' : '' }}">
                        <a href="{{ route('student.continuing-education.registration') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-steps" viewBox="0 0 16 16">
                                <path d="M.5 0a.5.5 0 0 1 .5.5v15a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 .5 0M2 1.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            {{ __('Riwayat Pendaftaran') }}
                        </a>
                    </li>
                </ul>
            </li>


            <li class="">
                <a class="d-flex align-items-center text-decoration-none" data-bs-toggle="collapse"
                    href="#mentoringMenu" role="button" aria-expanded="true"
                    aria-controls="mentoringMenu" id="mentoringToggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-backpack" viewBox="0 0 16 16">
                        <path d="M4.04 7.43a4 4 0 0 1 7.92 0 .5.5 0 1 1-.99.14 3 3 0 0 0-5.94 0 .5.5 0 1 1-.99-.14M4 9.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm1 .5v3h6v-3h-1v.5a.5.5 0 0 1-1 0V10z"/>
                        <path d="M6 2.341V2a2 2 0 1 1 4 0v.341c2.33.824 4 3.047 4 5.659v5.5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5V8a6 6 0 0 1 4-5.659M7 2v.083a6 6 0 0 1 2 0V2a1 1 0 0 0-2 0m1 1a5 5 0 0 0-5 5v5.5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5V8a5 5 0 0 0-5-5"/>
                    </svg>
                    {{ __('Mentoring') }}
                    <i class="fa fa-chevron-down ms-auto collapse-icon " style="font-size: 10px;"></i>
                </a>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.mentee.index') || Route::is('student.mentor.index') ? 'show' : '' }}"
                    id="mentoringMenu">
                    <li
                        class="list-group-item ps-3 text-sm {{ Route::is('student.mentee.index') ? 'active' : '' }}">
                        <a href="{{ route('student.mentee.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-briefcase" viewBox="0 0 16 16">
                                <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                            {{ __('Mentee') }}
                        </a>
                    </li>
                </ul>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.mentee.index') || Route::is('student.mentor.index') ? 'show' : '' }}"
                    id="mentoringMenu">
                    <li
                        class="list-group-item ps-3 text-sm {{ Route::is('student.mentor.index') ? 'active' : '' }}">
                        <a href="{{ route('student.mentor.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-steps" viewBox="0 0 16 16">
                                <path d="M.5 0a.5.5 0 0 1 .5.5v15a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 .5 0M2 1.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            {{ __('Mentor') }}
                        </a>
                    </li>
                </ul>
            </li>


            <li class="">
                <a class="d-flex align-items-center text-decoration-none" data-bs-toggle="collapse"
                    href="#coachingMenu" role="button" aria-expanded="true"
                    aria-controls="coachingMenu" id="coachingToggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-backpack" viewBox="0 0 16 16">
                        <path d="M4.04 7.43a4 4 0 0 1 7.92 0 .5.5 0 1 1-.99.14 3 3 0 0 0-5.94 0 .5.5 0 1 1-.99-.14M4 9.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm1 .5v3h6v-3h-1v.5a.5.5 0 0 1-1 0V10z"/>
                        <path d="M6 2.341V2a2 2 0 1 1 4 0v.341c2.33.824 4 3.047 4 5.659v5.5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5V8a6 6 0 0 1 4-5.659M7 2v.083a6 6 0 0 1 2 0V2a1 1 0 0 0-2 0m1 1a5 5 0 0 0-5 5v5.5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5V8a5 5 0 0 0-5-5"/>
                    </svg>
                    {{ __('Coaching') }}
                    <i class="fa fa-chevron-down ms-auto collapse-icon " style="font-size: 10px;"></i>
                </a>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.coachee.index') || Route::is('student.coach.index') ? 'show' : '' }}"
                    id="coachingMenu">
                    <li
                        class="list-group-item ps-3 text-sm {{ Route::is('student.coachee.index') ? 'active' : '' }}">
                        <a href="{{ route('student.coachee.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-briefcase" viewBox="0 0 16 16">
                                <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                            {{ __('Coachee') }}
                        </a>
                    </li>
                </ul>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.coachee.index') || Route::is('student.coach.index') ? 'show' : '' }}"
                    id="coachingMenu">
                    <li
                        class="list-group-item ps-3 text-sm {{ Route::is('student.coach.index') ? 'active' : '' }}">
                        <a href="{{ route('student.coach.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-steps" viewBox="0 0 16 16">
                                <path d="M.5 0a.5.5 0 0 1 .5.5v15a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 .5 0M2 1.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            {{ __('Coach') }}
                        </a>
                    </li>
                </ul>
            </li>


        </ul>
    </nav>
    <div class="dashboard__sidebar-title mt-20 mb-20">
        <h6 class="title">{{ __('User') }}</h6>
    </div>
    <nav class="dashboard__sidebar-menu">
        <ul class="list-wrap">
            <li>
                <a href="{{ route('student.notifications') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
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
