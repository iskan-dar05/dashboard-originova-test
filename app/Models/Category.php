<?php

namespace App\Models;
// use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Product;

class Category extends Model
{
    public function products()
    {
        return $this->hasMany(Product::class, "category_id","id");
    }
    protected $fillable = [
        'title',
    ];


}
