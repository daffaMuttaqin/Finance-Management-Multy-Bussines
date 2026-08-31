<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditService
{
    public static function log(
        int $businessId,
        ?int $userId,
        string $action,
        string $entityType,
        ?string $entityId,
        ?array $old = null,
        ?array $new = null,
        string $detail = ''
    ): AuditLog {
        return AuditLog::create([
            'business_id' => $businessId,
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 500),
            'created_at' => now(),
        ]);
    }

    public static function fromRequest(Request $request, string $action, string $entityType, ?string $entityId, ?array $old = null, ?array $new = null): AuditLog
    {
        $businessId = $request->attributes->get('business_id') ?? $request->input('business_id') ?? 1;
        return static::log($businessId, $request->user()?->id, $action, $entityType, $entityId, $old, $new);
    }
}
