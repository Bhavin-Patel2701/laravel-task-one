<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'title', 'description', 'status', 'quantity', 'price', 'image', 'child_category_id', 'user_id', 'sku', 'multi_image'
    ];

    /**
     * Get the category that owns the product.
     */
    function category()
    {
        return $this->hasOne(Category::class, 'id');
    }

    function user()
    {
        return $this->hasOne(User::class, 'id');
    }
}
