@extends('admin.master_layout')
@section('title')
    <title>{{ __('Comment Details') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">{{ __('Comment Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Comment Details') }}</div>
                </div>
            </div>

            <a href="{{ route('admin.knowledge-comments.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Details') }}</h4>
                                <div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>{{ __('Course') }}</td>
                                            <td>
                                                <a href="{{ route('article.show', $comment->post->slug) }}" target="_blank">
                                                    {{ $comment->post->title }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Comment') }}</td>
                                            <td>{{ $comment->description }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Date') }}</td>
                                            <td>{{ formatDate($comment->created_at) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Status') }}</td>
                                            <td>
                                                <form action="{{ route('admin.knowledge-comments.update', $comment->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="d-flex">
                                                        <select name="status" id="" class="form-control w-25">
                                                            <option @selected($comment->status == 'unpublished') value="unpublished">
                                                                {{ __('Unpublished') }}</option>
                                                            <option @selected($comment->status == 'published') value="published">
                                                                {{ __('Published') }}</option>
                                                        </select>
                                                        <div><button type="submit"
                                                                class="btn btn-primary ml-2 mt-1">{{ __('Update') }}</button>
                                                        </div>
                                                    </div>

                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-1 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Report List') }}</h4>
                                <div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Reporter') }}</th>
                                            <th>{{ __('Comment') }}</th>
                                            <th>{{ __('Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($comment->reports as $report)
                                            <tr>
                                                <td>{{ $report->user->name }}</td>
                                                <td>{{ $report->reason }}</td>
                                                <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d F Y, H:i') }}
                                                    WIB
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">
                                                    {{ __('No Data') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
