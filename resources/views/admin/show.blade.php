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
                    <div class="table-responsive">
                        <div class="card-body">      
                            <table class="table table-responsive-lg table-no-border">                  
                                <tbody>
                                    <tr>
                                        <th scope="row">User</th>
                                        <td scope="col">{{ $wishlist->user?->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Product</th>
                                        <td scope="col">{{ $wishlist->product?->name ?? 'N/A' }}</td>                                   
                                    </tr>                                
                                    <tr>
                                        <th scope="row">Created At</th>
                                        <td scope="col">
                                            {{ $wishlist->created_at
                                                ? $wishlist->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                : '—' }}
                                        </td>                                   
                                    </tr>                                
                                </tbody>
                            </table>   
                                             
                            <a href="{{ route('admin.wishlists.index') }}" class="btn btn-secondary">Back</a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
