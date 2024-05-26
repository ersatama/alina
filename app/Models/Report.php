<?php

namespace App\Models;

use App\Models\Scopes\Page;
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

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new Page);
    }
}
