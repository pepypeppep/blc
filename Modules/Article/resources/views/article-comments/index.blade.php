@extends('admin.master_layout')
@section('title')
    <title>{{ __('Article Comments') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">{{ __('Article Comments') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Article Comment') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.knowledge-comments.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')" class="form_padding">
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <input type="text" name="keyword" value="{{ request()->get('keyword') }}"
                                                class="form-control" placeholder="{{ __('Search') }}">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">{{ __('Status') }}</option>
                                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                                    {{ __('Approved') }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">{{ __('Status') }}</option>
                                                <option value="published"
                                                    {{ request('status') == 'published' ? 'selected' : '' }}>
                                                    {{ __('PUBLISHED') }}
                                                </option>
                                                <option value="unpublished"
                                                    {{ request('status') == 'unpublished' ? 'selected' : '' }}>
                                                    {{ __('UNPUBLISHED') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Comment List') }}</h4>
                                <div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>{{ __('Article') }}</th>
                                                <th>{{ __('Comments') }}</th>
                                                <th>{{ __('By') }}</th>
                                                <th>{{ __('Reported') }}</th>
                                                <th>{{ __('Created At') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th class="text-center">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($comments as $comment)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>
                                                        <a href="{{ route('article.show', $comment->post->slug) }}"
                                                            target="_blank">
                                                            {{ $comment->post->title }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $comment->description }}</td>
                                                    <td>{{ $comment?->user?->name }}</td>
                                                    <td>{{ $comment->reported_count }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($comment->created_at)->format('d F Y, H:i') }}
                                                        WIB
                                                    </td>
                                                    <td>
                                                        @if ($comment->status == 'published')
                                                            <span class="badge badge-success">{{ __('Published') }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ __('Unpublished') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div>
                                                            <a href="{{ route('admin.knowledge-comments.detail', $comment->id) }}"
                                                                class="m-1 text-white btn btn-sm btn-primary"
                                                                title="Show">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            {{-- <a href="javascript:;" data-toggle="modal"
                                                                data-target="#deleteModal" class="btn btn-danger btn-sm"
                                                                onclick="deleteData({{ $comment->id }})"><i
                                                                    class="fa fa-trash" aria-hidden="true"></i></a> --}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Comment')" route="admin.course-review.create"
                                                    create="no" :message="__('No data found!')" colspan="6"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $comments->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <x-admin.delete-modal />
@endsection

@push('js')
    <script>
        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/course-review/') }}" + "/" + id)
        }
    </script>
@endpush

@push('css')
    <style>
        .dd-custom-css {
            position: absolute;
            will-change: transform;
            top: 0px;
            left: 0px;
            transform: translate3d(0px, -131px, 0px);
        }

        .max-h-400 {
            min-height: 400px;
        }
    </style>
@endpush
