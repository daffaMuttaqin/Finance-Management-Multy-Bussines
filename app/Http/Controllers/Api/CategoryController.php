<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Audit\AuditService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $bid = $request->input('business_id', 1);
        return Category::where('business_id',$bid)
            ->when($request->input('type'), fn($q,$v)=> $q->where('type',$v))
            ->orderBy('type')->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:100',
            'type' => 'required|in:INCOME,EXPENSE',
            'classification' => 'required|string|max:50',
            'affects_profit' => 'sometimes|boolean',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        if (Category::where('business_id',$data['business_id'])->where('type',$data['type'])->where('name',$data['name'])->exists()) {
            return response()->json(['message' => 'Kategori sudah ada.'], 422);
        }
        $cat = Category::create([
            'business_id' => $data['business_id'],
            'name' => $data['name'],
            'type' => $data['type'],
            'classification' => $data['classification'],
            'affects_profit' => $data['type'] === 'INCOME' ? true : ($data['affects_profit'] ?? true),
            'parent_id' => $data['parent_id'] ?? null,
        ]);
        AuditService::log($cat->business_id, $request->user()?->id, 'CREATE_CATEGORY', 'category', (string)$cat->id, null, $cat->toArray());
        return response()->json($cat, 201);
    }

    public function update(Request $request, Category $category)
    {
        if ($request->has('business_id') && (int)$category->business_id !== (int)$request->input('business_id')) abort(403);
        $old = $category->toArray();
        $data = $request->validate([
            'name' => 'sometimes|string|max:100',
            'classification' => 'sometimes|string|max:50',
            'affects_profit' => 'sometimes|boolean',
            'is_archived' => 'sometimes|boolean',
        ]);
        $category->update($data);
        AuditService::log($category->business_id, $request->user()?->id, $category->is_archived ? 'ARCHIVE_CATEGORY':'UPDATE_CATEGORY', 'category', (string)$category->id, $old, $category->fresh()->toArray());
        return response()->json($category);
    }

    public function archive(Request $request, Category $category)
    {
        if ($request->has('business_id') && (int)$category->business_id !== (int)$request->input('business_id')) abort(403);
        $old = $category->toArray();
        // prevent archiving if used
        if (!$category->is_archived && \App\Models\Transaction::where('category_id',$category->id)->where('status','POSTED')->exists()) {
            // allow archive but not delete — PRD allows archive even if used, just hide from picks
        }
        $category->update(['is_archived' => !$category->is_archived]);
        AuditService::log($category->business_id, $request->user()?->id, $category->is_archived ? 'ARCHIVE_CATEGORY':'ACTIVATE_CATEGORY', 'category', (string)$category->id, $old, $category->fresh()->toArray());
        return response()->json($category);
    }

    public function destroy(Category $category, Request $request)
    {
        if ($request->has('business_id') && (int)$category->business_id !== (int)$request->input('business_id')) abort(403);
        if (\App\Models\Transaction::where('category_id',$category->id)->exists()) {
            return response()->json(['message' => 'Kategori masih dipakai transaksi — archive saja.'], 422);
        }
        $category->delete();
        AuditService::log($category->business_id, $request->user()?->id, 'DELETE_CATEGORY', 'category', (string)$category->id, $category->toArray(), null);
        return response()->json(['message' => 'Deleted']);
    }
}
