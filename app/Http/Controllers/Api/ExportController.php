<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Services\Export\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct(protected ExportService $export) {}

    public function excel(Request $request)
    {
        $request->validate(['business_id' => 'required|exists:businesses,id']);
        $business = Business::findOrFail($request->input('business_id'));
        return $this->export->toCsv($business, $request);
    }

    public function pdf(Request $request)
    {
        $request->validate(['business_id' => 'required|exists:businesses,id']);
        $business = Business::findOrFail($request->input('business_id'));
        return $this->export->toPdfHtml($business, $request);
    }
}
