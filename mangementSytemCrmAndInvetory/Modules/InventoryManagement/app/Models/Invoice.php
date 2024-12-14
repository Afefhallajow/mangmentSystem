<?php

namespace Modules\InventoryManagement\Models;

use App\Events\AttendanceCheckedOut;
use App\Models\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\InventoryManagement\Events\InvoiceUpdated;
use Modules\InventoryManagement\Extra\InvoiceType;

class Invoice extends BaseEntity
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public function __construct(array $attributes = [])
    {

        $this->fillable = array_merge($this->fillable, [
            "total", "type"
        ]);

         $this->with = array_merge($this->with, [
             "products"
         ]);

        parent::__construct($attributes);
    }
    protected $casts = [
        'type' => InvoiceType::class, // This will dispatch the event on model update
    ];

    public function setTypeAttribute($value){
        if (!InvoiceType::tryFrom($value)) {
            throw new \InvalidArgumentException("invalid invoice type value " . $value);
        }
        $this->attributes["type"] = $value;
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'product_invoices')
            ->select(['products.id', 'products.name', 'product_invoices.quantity']);
    }
    // protected static function newFactory(): InvoiceFactory
    // {
    //     // return InvoiceFactory::new();
    // }
}
