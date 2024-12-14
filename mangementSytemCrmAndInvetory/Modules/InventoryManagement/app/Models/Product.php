<?php

namespace Modules\InventoryManagement\Models;

use App\Models\Attachment;
use App\Models\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseEntity
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge($this->fillable, [
            "quantity", "price"
        ]) ;
        $this->with = array_merge($this->with, [
            "attachments"
        ]);
        parent::__construct($attributes);
    }
    protected $hidden = ['pivot']; // Hide the pivot field

    function attachments(){
        return $this->morphMany(Attachment::class, "model");
    }
    function colors(){
        return $this->hasMany(ProductColors::class, "product_id");
    }

    // protected static function newFactory(): ProductFactory
    // {
    //     // return ProductFactory::new();
    // }
}
