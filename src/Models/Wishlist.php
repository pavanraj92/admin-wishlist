<?php

namespace admin\pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;

class Wishlist extends Model
{
    use HasFactory, Sortable;


    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public $sortable = [
        'user_id',
        'product_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wishlist) {
            if (empty($wishlist->slug)) {
                $wishlist->slug = Str::slug($wishlist->title);
            }
        });

        static::updating(function ($wishlist) {
            if (empty($wishlist->slug)) {
                $wishlist->slug = Str::slug($wishlist->title);
            }
        });
    }
    public function scopeFilter($query, $title)
    {
        if ($title) {
            return $query->where('title', 'like', '%' . $title . '%');
        }
        return $query;
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }
}
