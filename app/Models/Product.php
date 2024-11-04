<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'quantity',
        'image',
        'category_id',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'image'=>'array'
    ];
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, "updated_by");
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
