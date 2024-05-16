<?php

namespace App\Http\Requests\Report;

use App\Http\Requests\Request;

class ImportRequest extends Request
{
    public function rules(): array
    {
        return [
            'file' => 'required'
        ];
    }
}
