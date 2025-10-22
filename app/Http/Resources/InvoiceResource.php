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
            'invoice_date' => $this->invoice_date?->format('Y-m-d'),
            'total' => $this->total,
            'status' => $this->status,
            'attachment' => $this->attachment ? asset('storage/' . $this->attachment) : null,
            'client' => new UserResource($this->whenLoaded('client')),
            'project' => new ProjectResource($this->whenLoaded('project')),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
