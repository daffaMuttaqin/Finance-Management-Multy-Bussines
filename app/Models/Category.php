<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $fillable = ['business_id', 'name', 'type', 'classification', 'parent_id', 'affects_profit', 'is_archived'];
    protected $casts = ['affects_profit' => 'boolean', 'is_archived' => 'boolean'];

    public function business(): BelongsTo { return $this->belongsTo(Business::class); }
    public function parent(): BelongsTo { return $this->belongsTo(Category::class, 'parent_id'); }
}
