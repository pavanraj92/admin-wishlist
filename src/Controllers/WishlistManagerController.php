<?php

namespace admin\wishlists\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\wishlists\Models\Wishlist;

class WishlistManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:wishlists_manager_list')->only(['index']);
        $this->middleware('admincan_permission:wishlists_manager_view')->only(['show']);
    }

    public function index(Request $request)
    {
        try {
            $wishlists = Wishlist::with(['user', 'product'])
                ->filter($request->only(['keyword'])) 
                ->sortable()
                ->latest()
                ->paginate(Wishlist::getPerPageLimit())
                ->withQueryString();

            return view('wishlists::admin.index', compact('wishlists'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load Wishlist: ' . $e->getMessage());
        }
    }


    public function show(Wishlist $wishlist)
    {
        try {
            return view('wishlists::admin.show', compact('wishlist'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load wishlist: ' . $e->getMessage());
        }
    }
}
