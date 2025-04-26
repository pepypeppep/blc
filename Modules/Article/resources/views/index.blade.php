@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Knowledge') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Manage Knowledge') }} (KMS)</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Manage Knowledge') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="dashboard__review-table">
                            <div class="dashboard__nav-wrap">
                                <ul class="nav nav-tabs" id="statusTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == null ? 'active' : '' }}"
                                        href="{{ route('admin.knowledge.index', ['status' => 'all']) }}">
                                            {{ __('All') }} &nbsp;
                                            <span class="badge {{ $status == null ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $totalArticles ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == 'verification' ? 'active' : '' }}"
                                        href="{{ route('admin.knowledge.index', ['status' => 'verification']) }}">
                                            {{ __('Verification') }} &nbsp;
                                            <span class="badge {{ $status == 'verification' ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $statusCounts['verification'] ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == 'rejected' ? 'active' : '' }}"
                                        href="{{ route('admin.knowledge.index', ['status' => 'rejected']) }}">
                                            {{ __('Rejected') }} &nbsp;
                                            <span class="badge {{ $status == 'rejected' ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $statusCounts['rejected'] ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navigation-btn d-inline-flex align-items-center {{ $status == 'published' ? 'active' : '' }}"
                                        href="{{ route('admin.knowledge.index', ['status' => 'published']) }}">
                                            {{ __('Published') }} &nbsp;
                                            <span class="badge {{ $status == 'published' ? 'bg-white text-dark border border-secondary' : 'bg-light text-primary' }}">
                                                {{ $statusCounts['published'] ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Knowledge List') }}</h4>
                                <div>
                                    <!-- <a href="" class="btn btn-primary"><i class="fa fa-plus"></i>{{ __('Add New') }}</a> -->
                                </div>
                            </div>
                            <div class="card-body">
                                <div class=" max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Author') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($articles as $index => $article)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td><a
                                                        href="{{ route('admin.knowledge.detail', $article->id) }}">{{ $article->title }}</a>
                                                </td>
                                                <td>{{ $article->category }}</td>
                                                <td><p title="{!! clean($article->description) !!}">{!! clean(truncate($article->description)) !!}</p></td>
                                                <td>
                                                    {{ $article->author->name }}
                                                </td>
                                                <td>
                                                    <div class="badge badge-{{ $article->stat['color'] }}">{{ $article->stat['label'] }}</div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.knowledge.detail', $article->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <x-empty-table :name="__('Knowledges')" route="" create="no" :message="__('No data found!')"
                                                colspan="9"></x-empty-table>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $articles->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Success", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Error", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif
@endpush

@push('css')
    <style>
    </style>
@endpush
