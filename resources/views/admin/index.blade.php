@extends('admin::admin.layouts.master')

@section('title', 'Wishlists Management')

@section('page-title', 'Wishlist Manager')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Wishlist Manager</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">Filter</h4>
                    <form action="{{ route('admin.wishlists.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="title">Keyword</label>
                                    <input type="text" name="keyword" id="keyword" class="form-control"
                                        value="{{ app('request')->query('keyword') }}" placeholder="Enter keyword">
                                </div>
                            </div>
                            <div class="col-auto mt-1 text-right">
                                <div class="form-group">
                                    <label for="created_at">&nbsp;</label>
                                    <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                    <a href="{{ route('admin.wishlists.index') }}" class="btn btn-secondary mt-4">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h4 class="card-title">Manage Wishlist</h4> --}}
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">S. No.</th>
                                        <th>User Name</th>
                                        @php
                                            $productPackageInstalled = \DB::table('packages')
                                                ->where(['package_name' => 'admin/products', 'is_installed' => 1])
                                                ->whereNull('deleted_at') // if using soft deletes
                                                ->exists();
                                            $coursePackageInstalled = \DB::table('packages')
                                                ->where(['package_name' => 'admin/courses', 'is_installed' => 1])
                                                ->whereNull('deleted_at')
                                                ->exists();
                                        @endphp

                                        @if ($productPackageInstalled)
                                            <th scope="col">Product</th>
                                        @elseif($coursePackageInstalled)
                                            <th scope="col">Course</th>
                                        @endif
                                        <th>Created At</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($wishlists) && $wishlists->count() > 0)
                                        @php
                                            $i = ($wishlists->currentPage() - 1) * $wishlists->perPage() + 1;
                                        @endphp
                                        @foreach ($wishlists as $wishlist)
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td>
                                                    @if (class_exists(\admin\users\Models\User::class))
                                                        {{ $wishlist?->user?->full_name ?? 'N/A' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                @if (class_exists(\admin\products\Models\Product::class))
                                                    <td>
                                                        {{ $wishlist?->product?->name }}
                                                    </td>
                                                @elseif(class_exists(\admin\users\Models\Course::class))
                                                    <td>
                                                        {{ $wishlist?->course?->title }}
                                                    </td>
                                                @endif
                                                <td>
                                                    {{ $wishlist->created_at ? $wishlist->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}
                                                </td>
                                                <td style="width: 10%;">
                                                    @admincan('wishlists_manager_view')
                                                        <a href="{{ route('admin.wishlists.show', $wishlist) }}"
                                                            data-toggle="tooltip" data-placement="top" title="View this record"
                                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                                    @endadmincan
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No records found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!--pagination move the right side-->
                            @if ($wishlists->count() > 0)
                                {{ $wishlists->links('admin::pagination.custom-admin-pagination') }}
                            @endif


                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
