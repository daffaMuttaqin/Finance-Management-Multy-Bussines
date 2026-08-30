<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    protected $fillable = ['name', 'type', 'logo', 'currency', 'timezone', 'settings', 'owner_id'];
    protected $casts = ['settings' => 'array'];

    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function users(): BelongsToMany { return $this->belongsToMany(User::class, 'business_users')->withPivot('role')->withTimestamps(); }
    public function accounts(): HasMany { return $this->hasMany(Account::class); }
    public function categories(): HasMany { return $this->hasMany(Category::class); }
    public function transactions(): HasMany { return $this->hasMany(Transaction::class); }
    public function assets(): HasMany { return $this->hasMany(Asset::class); }
    public function auditLogs(): HasMany { return $this->hasMany(AuditLog::class); }
}
