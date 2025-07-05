<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan atau bukan milik kamu'], 404);
        }

        $file = $request->file('payment_proof');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/payment_proofs', $filename);

        $order->payment_proof = $filename;
        $order->status = 'paid';
        $order->save();

        return response()->json([
            'message' => 'Bukti bayar berhasil diupload',
            'payment_proof_url' => asset('storage/payment_proofs/' . $filename),
            'status' => $order->status
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:paid,processing,shipped,done'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Status pesanan diperbarui.',
            'status' => $order->status
        ]);
    }
    public function index(Request $request)
{
    $orders = $request->user()->orders()
        ->with(['items.product']) // eager load
        ->latest()
        ->get();

    // Tambahkan URL lengkap untuk bukti bayar
    $orders->transform(function ($order) {
        $order->payment_proof_url = $order->payment_proof 
            ? asset('storage/payment_proofs/' . $order->payment_proof)
            : null;
        return $order;
    });

    return response()->json($orders);
}

public function adminIndex(Request $request)
{
    // Cek: hanya admin/reseller boleh akses
    if (!in_array($request->user()->role, ['admin', 'reseller'])) {
        return response()->json(['message' => 'Akses ditolak'], 403);
    }
    

    $orders = \App\Models\Order::with(['user', 'items.product'])
        ->latest()
        ->get();

    // Tambahkan full URL bukti bayar
    $orders->transform(function ($order) {
        $order->payment_proof_url = $order->payment_proof 
            ? asset('storage/payment_proofs/' . $order->payment_proof)
            : null;
        return $order;
    });

    return response()->json($orders);
}

public function markAsDone(Request $request, $id)
{
    // Ambil order milik user
    $order = Order::where('id', $id)
        ->where('user_id', $request->user()->id)
        ->first();

    if (!$order) {
        return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
    }

    if ($order->status !== 'shipped') {
        return response()->json(['message' => 'Pesanan belum dikirim, tidak bisa ditandai selesai'], 400);
    }

    $order->status = 'done';
    $order->save();

    return response()->json([
        'message' => 'Pesanan berhasil ditandai sebagai selesai',
        'status' => $order->status
    ]);
}

}
