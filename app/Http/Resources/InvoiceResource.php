<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'amount' => $this->amount,
            'status' => $this->status,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'client' => new UserResource($this->whenLoaded('client')),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
