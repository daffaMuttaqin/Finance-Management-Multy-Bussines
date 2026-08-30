<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'business_id','type','status','category_id','account_id',
        'from_account_id','to_account_id','amount','transaction_date',
        'description','reference_number','party','created_by','updated_by'
    ];
    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'integer',
    ];

    public function business(): BelongsTo { return $this->belongsTo(Business::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function account(): BelongsTo { return $this->belongsTo(Account::class); }
    public function fromAccount(): BelongsTo { return $this->belongsTo(Account::class, 'from_account_id'); }
    public function toAccount(): BelongsTo { return $this->belongsTo(Account::class, 'to_account_id'); }

    public function scopePosted($q) { return $q->where('status', 'POSTED'); }
    public function scopeVoided($q) { return $q->where('status', 'VOIDED'); }
}
