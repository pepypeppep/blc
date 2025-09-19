@php
    // $pendingRequestCount = \Modules\InstructorEvaluation\app\Models\InstructorEvaluation::where('status', 'pending')->count();
    $pendingRequestCount=0;
@endphp
<li
    class="nav-item dropdown {{ isRoute(['admin.instructor-request-setting.*', 'admin.instructor-request.*'], 'active') }}">
    <a href="javascript:void()" class="nav-link has-dropdown"><i class="fas fa-chalkboard-teacher"></i><span
            class="{{ $pendingRequestCount > 0 ? 'beep parent' : '' }}">{{ __('Instructor Evaluation') }}</span></a>

    <ul class="dropdown-menu">
        <li class="{{ isRoute('admin.instructor-request.*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.instructorevaluation.index') }}">
                {{ __('Instructor Evaluation List') }}
            </a>
        </li>
    </ul>
</li>
