<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'descritpion'=>$this->description,
            'price'=>$this->price,
            'quantity'=>$this->quantity,
            'image'=>$this->image,
            'category_id'=>new CategoryResource($this->category),
            'createdBy'=>$this->createdBy->id,
            'updatedBy'=>$this->updatedBy->id,
        ];
    }
}
