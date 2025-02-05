<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'report_file_id' => $this->report_file_id,
            'report_key' => $this->report_key,
            'key_parent_id' => $this->key_parent_id,
            'formatted_value' => $this->formatted_value,
            'value' => $this->value,
            'name' => $this->name,
            'field_name' => $this->field_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
