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
            {{-- <li class="{{ Route::is('vacancies-participant.index') ? 'active' : '' }}">
                <a href="{{ route('vacancies-participant.index') }}">
                    <img src="{{ asset('uploads/website-images/announcement.svg') }}">{{ __('ContinuingEducation') }}</a>
            </li> --}}

            <li class="">
                <a class="d-flex align-items-center text-decoration-none" data-bs-toggle="collapse"
                    href="#continuingEducationMenu" role="button" aria-expanded="true"
                    aria-controls="continuingEducationMenu" id="continuingEducationToggle">
                    <img src="{{ asset('uploads/website-images/announcement.svg') }}" width="16" height="16">
                    {{ __('ContinuingEducation') }}
                    <i class="fa fa-chevron-down ms-auto collapse-icon " style="font-size: 10px;"></i>
                </a>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.continuing-education') || Route::is('student.continuing-education.registration') ? 'show' : '' }}"
                    id="continuingEducationMenu">
                    <li
                        class="list-group-item ps-3 text-sm {{ Route::is('student.continuing-education') ? 'active' : '' }}">
                        <a href="{{ route('student.continuing-education') }}">
                            <span class="ps-2">
                                - </i> {{ __('Lowongan') }}
                            </span>
                        </a>
                    </li>
                </ul>
                <ul class="collapse list-unstyled mt-2 ps-4 {{ Route::is('student.continuing-education') || Route::is('student.continuing-education.registration') ? 'show' : '' }}"
                    id="continuingEducationMenu">
                    <li
                        class="list-group-item ps-3 text-sm {{ Route::is('student.continuing-education.registration') ? 'active' : '' }}">
                        <a href="{{ route('student.continuing-education.registration') }}">
                            <span class="ps-2">
                                - {{ __('Pendaftaran') }}
                            </span>
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
