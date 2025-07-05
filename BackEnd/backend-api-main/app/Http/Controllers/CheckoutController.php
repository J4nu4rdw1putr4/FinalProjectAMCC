<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Negotiation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        if ($request->user()->role !== 'user') {
            return response()->json(['message' => 'Hanya user yang bisa checkout'], 403);
        }
        

        $request->validate([
            'payment_method' => 'required|in:qris,bank,barter',
        ]);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Keranjang kosong'], 400);
        }

        DB::beginTransaction();
        try {
            $total = 0;
            $orderItems = [];

            foreach ($cart->items as $item) {
                $product = $item->product;

                if (!$product || $product->price === null) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Checkout gagal: produk tidak valid atau belum memiliki harga',
                        'product_id' => $item->product_id
                    ], 400);
                }

                // ✅ Cek apakah produk ini membutuhkan penawaran yang diterima
                $nego = Negotiation::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->orderByDesc('id')
                    ->first();

                // Jika produk pernah dinego, maka hanya bisa checkout jika ada yg accepted
                if ($nego && $nego->status !== 'accepted') {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Penawaran untuk produk '{$product->name}' belum diterima.",
                        'status_penawaran' => $nego->status,
                        'product_id' => $product->id
                    ], 403);
                }

                $finalPrice = $nego && $nego->status === 'accepted'
                    ? $nego->offered_price
                    : $product->price;

                $total += $finalPrice * $item->quantity;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'price' => $finalPrice
                ];

                \Log::info("🛒 Produk {$product->name}, Harga Final: Rp" . $finalPrice);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'payment_method' => $request->payment_method,
                'total' => $total,
            ]);

            foreach ($orderItems as $data) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity'],
                    'price' => $data['price'],
                ]);
            }

            // Kosongkan keranjang
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'message' => 'Checkout berhasil',
                'order' => $order->load('items.product')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Checkout gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
