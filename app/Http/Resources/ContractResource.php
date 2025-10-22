<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
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
            'contract_number' => $this->contract_number,
            'notes' => $this->notes,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'status' => $this->status,
            'attachment' => $this->attachment ? asset('storage/' . $this->attachment) : null,

            // العلاقات
            'client' => new UserResource($this->whenLoaded('client')),
            'project' => new ProjectResource($this->whenLoaded('project')),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'creator' => new UserResource($this->whenLoaded('creator')),

            // مواعيد الإنشاء والتحديث
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
    }
}
