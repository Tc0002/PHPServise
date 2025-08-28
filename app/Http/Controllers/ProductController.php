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
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
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
