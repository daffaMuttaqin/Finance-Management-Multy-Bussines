<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = ['business_id', 'name', 'type', 'opening_balance', 'is_archived', 'notes'];
    protected $casts = ['is_archived' => 'boolean', 'opening_balance' => 'integer'];

    public function business(): BelongsTo { return $this->belongsTo(Business::class); }

    // Current balance = opening + incomes - expenses ± transfers - assets (computed via FinanceService)
}
