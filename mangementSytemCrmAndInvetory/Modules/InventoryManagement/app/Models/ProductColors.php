<?php

namespace Modules\InventoryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\InventoryManagement\Database\Factories\ProductColorsFactory;

class ProductColors extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["color", "product_id"];

    // protected static function newFactory(): ProductColorsFactory
    // {
    //     // return ProductColorsFactory::new();
    // }

    public function product()
    {
        $this->belongsTo(Product::class, "product_id");
    }
}
