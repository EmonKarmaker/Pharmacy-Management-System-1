<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'sub_cat_name',
        'sub_cat_slug',
        'created_by',
        'updated_by',
    ];
    public function category(){
        return $this->belongsTo(FoodCategory::class,'category_id','food_categories_id');
    }
    public function foodItem(){
        return $this->hasMany(FoodItem::class,'sub_category_id','id');
    }
}


