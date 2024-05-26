<?php

namespace App\Http\Resources\ReportFile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;

class ReportFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['id'         => "mixed",
                  'path'       => "string",
                  'created_at' => "mixed",
                  'updated_at' => "mixed"
    ])] public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'path' => asset('xml/' . $this->path),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
