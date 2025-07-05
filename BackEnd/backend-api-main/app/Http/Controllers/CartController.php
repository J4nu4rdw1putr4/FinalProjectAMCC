<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        return response()->json($cart->load('items.product'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        $item = $cart->items()->where('product_id', $request->product_id)->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $item = $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Item ditambahkan ke keranjang', 'item' => $item]);
    }

    
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
    
        $user = $request->user();
    
        if (!$user) {
            return response()->json(['message' => 'User tidak terautentikasi'], 401);
        }
    
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
    
        $item = $cart->items()->where('id', $itemId)->first();
    
        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan di cart'], 404);
        }
    
        $item->quantity = $request->quantity;
        $item->save();
    
        return response()->json([
            'message' => 'Jumlah item berhasil diupdate',
            'item' => $item
        ]);
    }
    


    public function remove(Request $request, $itemId)
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();
        if (!$cart) {
            return response()->json(['message' => 'Cart tidak ditemukan'], 404);
        }

        $item = $cart->items()->where('id', $itemId)->first();
        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'Item dihapus dari keranjang']);
    }
}
