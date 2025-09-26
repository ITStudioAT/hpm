<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'homepage_id' => $this->homepage_id,
            'name' => $this->name,
            'path' => $this->path,
            'type' => $this->type,
            'structure' => $this->structure,
        ];
    }
}
