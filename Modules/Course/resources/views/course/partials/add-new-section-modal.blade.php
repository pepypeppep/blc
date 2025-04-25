<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalTitle" aria-hidden="true"
    data-bs-backdrop='static'>
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.course-chapter.store', request('id')) }}" class="instructor__profile-form"
                    method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="title">{{ __('Title') }} <code>*</code></label>
                            <input id="title" name="title" type="text" value="" class="form-control">
                        </div>
                        <div class="form-group {{ count($course->allInstructors) > 0 ? 'col-md-4' : 'col-md-12' }}">
                            <label for="jp">{{ __('JPL') }} <code>*</code></label>
                            <input id="jp" name="jp" type="number" value="" class="form-control">
                        </div>
                        @if (count($course->allInstructors) > 0)
                        <div class="form-group col-md-8">
                            <label for="instructor">{{ __('Instructor') }} <code>*</code></label>
                            <select name="instructor" class="form-control select2" id="">
                                @foreach ($course->allInstructors as $instructor)
                                    <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
