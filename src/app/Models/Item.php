<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class Item extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_IN_TRANSACTION = 'in_transaction';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'buyer_id',
        'item_image',
        'category_id',
        'condition',
        'name',
        'brand',
        'description',
        'price',
        'status',
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
        return $this->status === self::STATUS_COMPLETED;
    }

    public function getIsInTransactionAttribute()
    {
        return $this->status === self::STATUS_IN_TRANSACTION;
    }

    public function getIsActiveAttribute()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function ratings()
    {
        return $this->hasMany(UserRating::class);
    }

    public function ratingFrom($userId)
    {
        return $this->ratings()->where('rater_id', $userId)->first();
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
