<?php

namespace Modules\InventoryManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class products extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'price' => number_format($this->price, 2, '.', ','),
            'attachments' => $this->attachments->map(function ($attachment) {
                return $attachment->full_url; // Use the accessor to return the full URL
            }),
        ];
    }
}