<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Services\Audit\AuditService;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $bid = $request->input('business_id', 1);
        return Asset::with('account')->where('business_id',$bid)->orderByDesc('purchase_date')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|integer|min:1',
            'account_id' => 'required|exists:accounts,id',
            'description' => 'nullable|string|max:500',
        ]);
        // ensure account belongs to business
        $acc = \App\Models\Account::find($data['account_id']);
        if ((int)$acc->business_id !== (int)$data['business_id']) abort(403, 'Akun bukan milik business ini.');
        $asset = Asset::create($data);
        AuditService::log($asset->business_id, $request->user()?->id, 'CREATE_ASSET', 'asset', (string)$asset->id, null, $asset->toArray());
        return response()->json($asset->load('account'), 201);
    }
}
