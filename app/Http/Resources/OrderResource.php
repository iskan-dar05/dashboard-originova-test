<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'fullname'=>$this->fullname,
            'email'=>$this->email,
            'status'=>$this->status,
            'price'=>$this->price,
            'products'=>$this->products->pluck('title'),
        ];
    }
}
