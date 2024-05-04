<?php

namespace App\Models;

use App\Models\admin\Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    } 

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function product_rating()
    {
        return $this->hasMany(ProductRating::class)->where('status',1);
    }
}
