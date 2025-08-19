<?php

namespace admin\wishlists\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Schema;

class Wishlist extends Model
{
    use HasFactory, Sortable;


    protected $fillable = [
        'user_id',
        'product_id',
        'course_id',
    ];

    public $sortable = [
        'user',
        'product.name',
        'course.title',
        'created_at'
    ];

    public function userSortable($query, $direction)
    {
         return $query
        ->leftJoin('users', 'wishlists.user_id', '=', 'users.id')
        ->orderByRaw("CONCAT(users.first_name, ' ', users.last_name) {$direction}")
        ->select('wishlists.*');
    }

    public function courseSortable($query, $direction)
    {
        return $query->join('courses', 'wishlists.course_id', '=', 'courses.id')
            ->orderBy('courses.title', $direction)
            ->select('wishlists.*');
    }

    public function productSortable($query, $direction)
    {
        return $query->join('products', 'wishlists.product_id', '=', 'products.id')
            ->orderBy('products.name', $direction)
            ->select('wishlists.*');
    }

    public function scopeFilter($query, $filters)
    {
        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];

            if (Schema::hasTable('users') && method_exists($this, 'user')) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$keyword}%"]);
                });
            }

            if (Schema::hasTable('products') && method_exists($this, 'product')) {
                $query->orWhereHas('product', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            }

            if (Schema::hasTable('courses') && method_exists($this, 'course')) {
                $query->orWhereHas('course', function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            }

        }
        
        return $query;
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }

    public function user()
    {
        if (class_exists(\admin\users\Models\User::class)) {
            return $this->belongsTo(\admin\users\Models\User::class, 'user_id');
        }
    }
    public function product()
    {
        if (class_exists(\admin\products\Models\Product::class)) {
            return $this->belongsTo(\admin\products\Models\Product::class);
        }
    }

    public function course()
    {
        if (class_exists(\admin\courses\Models\Course::class)) {
            return $this->belongsTo(\admin\courses\Models\Course::class);
        }
    }
}
