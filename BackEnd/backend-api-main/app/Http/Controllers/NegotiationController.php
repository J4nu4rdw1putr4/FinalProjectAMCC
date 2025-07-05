<?php

namespace App\Http\Controllers;

use App\Models\Negotiation;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NegotiationController extends Controller
{
    public function index(Request $request)
    {
        $negos = Negotiation::where('user_id', $request->user()->id)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($negos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'offered_price' => 'required|numeric|min:100'
        ]);

        $existing = Negotiation::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Kamu sudah mengajukan penawaran untuk produk ini. Tunggu respon dulu.'
            ], 409);
        }

        $nego = Negotiation::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
            'offered_price' => $request->offered_price,
            'status' => 'pending'
        ]);

        $this->notifySeller($nego);

        return response()->json([
            'message' => 'Penawaran berhasil dikirim.',
            'negotiation' => $nego
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        $nego = Negotiation::with(['product', 'user'])->findOrFail($id);

        if ($request->user()->id !== $nego->product->user_id) {
            return response()->json(['message' => 'Kamu bukan pemilik produk ini'], 403);
        }

        if ($request->status === 'accepted') {
            $alreadyAccepted = Negotiation::where('product_id', $nego->product_id)
                ->where('status', 'accepted')
                ->first();

            if ($alreadyAccepted && $alreadyAccepted->id !== $nego->id) {
                return response()->json(['message' => 'Sudah ada penawaran yang diterima untuk produk ini.'], 409);
            }
        }

        DB::beginTransaction();

        try {
            $nego->status = $request->status;
            $nego->save();

            if ($nego->status === 'accepted') {
                // Tolak semua penawaran lain
                Negotiation::where('product_id', $nego->product_id)
                    ->where('id', '!=', $nego->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'rejected']);

                // ✅ Buat order otomatis
                $order = Order::create([
                    'user_id' => $nego->user_id,
                    'payment_method' => 'negotiation', // atau 'manual'
                    'total' => $nego->offered_price,
                    'status' => 'unpaid',
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $nego->product_id,
                    'quantity' => 1,
                    'price' => $nego->offered_price,
                ]);
            }

            $this->notifyBuyer($nego);

            DB::commit();

            return response()->json([
                'message' => 'Status penawaran diperbarui.',
                'negotiation' => $nego
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal memperbarui status.', 'error' => $e->getMessage()], 500);
        }
    }

    protected function notifySeller(Negotiation $nego)
    {
        $product = $nego->product;
        \Log::info("📢 Notifikasi: User {$nego->user->name} menawar produk '{$product->nama}' sebesar Rp{$nego->offered_price}");
    }

    protected function notifyBuyer(Negotiation $nego)
    {
        $status = strtoupper($nego->status);
        $buyerName = $nego->user->name;
        $productName = $nego->product->nama;
        \Log::info("📢 Notifikasi ke $buyerName: Penawaran kamu untuk produk '$productName' telah $status.");
    }

    public function incoming(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $productIds = $request->user()->products()->pluck('id');

        $negos = Negotiation::whereIn('product_id', $productIds)
            ->with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($negos);
    }
}
