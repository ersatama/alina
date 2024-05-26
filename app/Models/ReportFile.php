<?php

namespace App\Models;

use App\Models\Scopes\Page;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportFile extends Model
{
    use HasFactory;
    protected $table = 'report_files';
    protected $fillable = [
        'path'
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new Page);
    }
}
