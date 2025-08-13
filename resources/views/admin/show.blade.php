@extends('admin::admin.layouts.master')

@section('title', 'Wishlist Management')

@section('page-title', 'Wishlist Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('admin.wishlists.index') }}">Wishlist Manager</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Wishlist Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">
                                @if (class_exists(\admin\users\Models\User::class))
                                    {{ $wishlist?->user?->full_name ?? 'N/A' }}
                                @endif
                            </h4>
                            <div>
                                <a href="{{ route('admin.wishlists.index') }}" class="btn btn-secondary ml-2">
                                    Back
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Return Refund Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">User:</label>
                                                    <p>
                                                        @if (class_exists(\admin\users\Models\User::class))
                                                            {{ $wishlist?->user?->full_name ?? 'N/A' }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    @if (class_exists(\admin\products\Models\Product::class))
                                                        <label class="font-weight-bold">Product:</label>
                                                        <p>{{ $wishlist?->product?->name ?? 'N/A' }}</p>
                                                    @elseif(class_exists(\admin\courses\Models\Course::class))
                                                        <label class="font-weight-bold">Course:</label>
                                                        <p>{{ $wishlist?->course?->title ?? 'N/A' }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Created At:</label>
                                            <p>
                                                {{ $wishlist->created_at
                                                    ? $wishlist->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                    : '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
