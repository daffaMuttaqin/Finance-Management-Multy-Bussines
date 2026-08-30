<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = ['business_id','name','category','purchase_date','purchase_price','account_id','description','attachment','status'];
    protected $casts = ['purchase_date' => 'date', 'purchase_price' => 'integer'];

    public function business(): BelongsTo { return $this->belongsTo(Business::class); }
    public function account(): BelongsTo { return $this->belongsTo(Account::class); }
}
