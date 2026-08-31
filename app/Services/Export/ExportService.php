<?php

namespace App\Services\Export;

use App\Models\Business;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /**
     * Export CSV (Excel-compatible) — §34 mengikuti filter aktif
     */
    public function toCsv(Business $business, Request $request): StreamedResponse
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $type = $request->input('type');
        $categoryId = $request->input('category_id');
        $accountId = $request->input('account_id');

        $txs = $business->transactions()->with(['category','account','fromAccount','toAccount'])
            ->where('status','POSTED')
            ->when($from, fn($q)=> $q->where('transaction_date','>=',$from))
            ->when($to, fn($q)=> $q->where('transaction_date','<=',$to))
            ->when($type, fn($q)=> $q->where('type',$type))
            ->when($categoryId, fn($q)=> $q->where('category_id',$categoryId))
            ->when($accountId, fn($q)=> $q->where(function($w) use ($accountId){
                $w->where('account_id',$accountId)->orWhere('from_account_id',$accountId)->orWhere('to_account_id',$accountId);
            }))
            ->orderByDesc('transaction_date')->get();

        $filename = 'keukita-'.$business->name.'-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function() use ($txs){
            $out = fopen('php://output','w');
            // BOM for Excel UTF-8
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['Tanggal','Tipe','Kategori','Akun / Dari→Ke','Deskripsi','Jumlah','Status','Referensi']);
            foreach($txs as $t){
                $cat = $t->category?->name ?? ($t->type==='TRANSFER'?'Transfer':'-');
                $acc = $t->type==='TRANSFER'
                    ? ($t->fromAccount?->name.' → '.$t->toAccount?->name)
                    : ($t->account?->name ?? '-');
                fputcsv($out, [
                    $t->transaction_date->format('Y-m-d'),
                    $t->type,
                    $cat,
                    $acc,
                    $t->description,
                    $t->amount,
                    $t->status,
                    $t->reference_number,
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export PDF — HTML printable (§34). Jika dompdf terpasang, bisa pakai PDF::loadView.
     * Untuk MVP tanpa dompdf, return HTML yang di-print via browser (window.print).
     */
    public function toPdfHtml(Business $business, Request $request): \Illuminate\Http\Response
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $type = $request->input('type');

        $txs = $business->transactions()->with(['category','account','fromAccount','toAccount'])
            ->where('status','POSTED')
            ->when($from, fn($q)=> $q->where('transaction_date','>=',$from))
            ->when($to, fn($q)=> $q->where('transaction_date','<=',$to))
            ->when($type, fn($q)=> $q->where('type',$type))
            ->orderByDesc('transaction_date')->limit(200)->get();

        $metrics = app(\App\Services\Finance\FinanceService::class)->metrics($business);

        $html = view('exports.report-pdf', [
            'business' => $business,
            'transactions' => $txs,
            'metrics' => $metrics,
            'filters' => $request->only(['from','to','type','category_id','account_id']),
            'generatedAt' => now()->format('d M Y H:i'),
        ])->render();

        // Jika barryvdh/laravel-dompdf terpasang, aktifkan:
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4','landscape');
            return $pdf->download('keukita-'.$business->name.'-'.now()->format('Ymd-His').'.pdf');
        }

        return response($html)->header('Content-Type','text/html');
    }
}
