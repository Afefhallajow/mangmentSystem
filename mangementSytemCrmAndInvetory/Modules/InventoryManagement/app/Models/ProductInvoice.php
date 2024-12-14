<?php

namespace Modules\InventoryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\InventoryManagement\Database\Factories\ProductInvioceFactory;

class ProductInvoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["product_id", "invoice_id", "quantity", "price"];

    // protected static function newFactory(): ProductInvioceFactory
    // {
    //     // return ProductInvioceFactory::new();
    // }
}
