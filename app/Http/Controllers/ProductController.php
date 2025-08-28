<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Jobs\ProductWriteJob;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');
        $search = $request->get('search', '');

        // ソート可能なフィールドを定義
        $allowedSortFields = ['id', 'name', 'barcode', 'category', 'price', 'stock', 'created_at'];

        // 不正なソートフィールドの場合はデフォルト値を使用
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'id';
        }

        // ソート方向の検証
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // 検索クエリの構築
        $query = Product::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy($sortField, $sortDirection)->paginate(10);
        $products->setPath('products');

        return view('products.index', compact('products', 'sortField', 'sortDirection', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255|unique:products,barcode',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'image_path' => 'nullable|string|max:255',
        ]);

        // 軽量キュー処理でSQLite書き込み制限を回避
        try {
            ProductWriteJob::dispatch('create', $request->all());

            return redirect()->route('products.index')
                ->with('success', '製品の登録がキューに追加されました。処理完了までお待ちください。');
        } catch (\Exception $e) {
            // フォールバック: 直接処理
            Product::create($request->all());
            return redirect()->route('products.index')
                ->with('success', '製品が正常に登録されました。');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255|unique:products,barcode,' . $id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'image_path' => 'nullable|string|max:255',
        ]);

        // 軽量キュー処理でSQLite書き込み制限を回避
        try {
            ProductWriteJob::dispatch('update', $request->all(), $id);

            return redirect()->route('products.index')
                ->with('success', '製品の更新がキューに追加されました。処理完了までお待ちください。');
        } catch (\Exception $e) {
            // フォールバック: 直接処理
            $product = Product::findOrFail($id);
            $product->update($request->all());
            return redirect()->route('products.index')
                ->with('success', '製品が正常に更新されました。');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // 軽量キュー処理でSQLite書き込み制限を回避
        try {
            ProductWriteJob::dispatch('delete', [], $id);

            return redirect()->route('products.index')
                ->with('success', '製品の削除がキューに追加されました。処理完了までお待ちください。');
        } catch (\Exception $e) {
            // フォールバック: 直接処理
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->route('products.index')
                ->with('success', '製品が正常に削除されました。');
        }
    }
}
