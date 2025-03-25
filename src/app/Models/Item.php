<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_image',
        'category_id',
        'condition',
        'name',
        'brand',
        'description',
        'price',
        'status',
        'is_sold'
    ];
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'item_id');
    }
    public function getIsSoldAttribute()
    {
        return $this->purchases()->exists();
    }
}
