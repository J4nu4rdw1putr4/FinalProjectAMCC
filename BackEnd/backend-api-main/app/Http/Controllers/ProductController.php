<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // 🔐 Semua endpoint butuh auth
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // 🔍 Lihat semua produk
    public function index()
    {
        return Product::with('user')->latest()->get();
    }

    // ➕ Upload produk
    public function store(Request $request)
    {
        if ($request->user()->role !== 'reseller') {
            return response()->json(['message' => 'Hanya reseller yang bisa menambah produk'], 403);
        }
        
        $request->validate([
            'nama'     => 'required|string|max:255',
            'deskripsi'=> 'nullable|string',
            'harga'    => 'required|numeric',
            'stok'     => 'required|integer',
            'diskon'   => 'boolean',
            'gambar'   => 'nullable|image|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('products', 'public');
        }

        $product = Product::create([
            'user_id'  => $request->user()->id,
            'nama'     => $request->nama,
            'deskripsi'=> $request->deskripsi,
            'harga'    => $request->harga,
            'stok'     => $request->stok,
            'diskon'   => $request->diskon ?? false,
            'gambar'   => $path
        ]);

        return response()->json($product, 201);
    }

    // ✏️ Update produk
    public function update(Request $request, Product $product)
    {
        if ($request->user()->role !== 'reseller') {
            return response()->json(['message' => 'Hanya reseller yang bisa menambah produk'], 403);
        }
        
        // Pastikan hanya pemilik produk yang bisa update
        if ($product->user_id != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        // Validasi input, semua field opsional (pakai 'sometimes')
        $request->validate([
            'nama'      => 'sometimes|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'     => 'sometimes|numeric',
            'stok'      => 'sometimes|integer',
            'diskon'    => 'sometimes|boolean',
            'gambar'    => 'nullable|image|max:2048'
        ]);
        
        // dd($request->all());
        
    
        // Ambil data yang mau diupdate
        $data = $request->only(['nama', 'deskripsi', 'harga', 'stok', 'diskon']);

        // Tambahkan log untuk melihat isi data yang mau disimpan
        Log::info('Data yang akan diupdate:', $data);
        
    
        // Jika gambar dikirim, simpan file baru dan hapus yang lama
        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }
    
        // Update data produk
        $product->update($data);
    
        // Optional logging untuk debug (bisa dihapus nanti)
        Log::info('Produk berhasil diupdate', $data);
    
        // Response sukses
        return response()->json([
            'message' => 'Produk berhasil diupdate',
            'data' => $product
        ]);
    }

    // ❌ Hapus produk
    public function destroy(Product $product, Request $request)
    {
        if ($product->user_id != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }
}
