<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function getProductsByCategory(Request $request)
    {
        \Log::info("🔹 Request diterima. category_name: " . ($request->category_name ?? 'NULL'));

        $query = Product::with('category', 'galleries');

        if ($request->has('category_name') && !empty($request->category_name)) {
            \Log::info("🔹 Filter berdasarkan kategori: " . $request->category_name);
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', '=', $request->category_name);
            });
        }

        $products = $query->paginate(6);

        \Log::info("🔹 Produk ditemukan: " . $products->count());

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Data list produk berhasil diambil'
            ],
            'data' => $products
        ]);
    }
}