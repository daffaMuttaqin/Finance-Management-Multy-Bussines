<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $bid = $request->input('business_id', 1);
        return AuditLog::where('business_id',$bid)->orderByDesc('created_at')->limit(50)->get();
    }
}
