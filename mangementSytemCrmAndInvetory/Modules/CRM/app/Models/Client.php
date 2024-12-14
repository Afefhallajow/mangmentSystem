<?php

namespace Modules\CRM\Models;

use App\Models\BaseEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CRM\Database\Factories\ClientFactory;

class Client extends BaseEntity
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge($this->fillable, [
            "email", "phone", "address"
        ]);

        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     */
    // protected static function newFactory(): ClientFactory
    // {
    //     // return ClientFactory::new();
    // }
}
