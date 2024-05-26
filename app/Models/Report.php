<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $fillable = [
        'report_file_id',
        'report_key',
        'key_parent_id',
        'formatted_value',
        'value',
        'name',
        'field_name'
    ];
}
