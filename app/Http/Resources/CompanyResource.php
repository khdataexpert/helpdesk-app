<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'image' => asset('storage/' . $this->image),
            'style' => $this->whenLoaded('style', function () {
                return new CompanyStyleResource($this->style);
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
