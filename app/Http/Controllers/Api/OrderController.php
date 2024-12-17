<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Order::latest()->paginate(5);
        return response()->json([
            "status" => "Berhasil",
            "data" => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Pastikan user ada
            'nama' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email',
            'category' => 'required|string',
            'service' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'pickup_delivery' => 'required|string',
            'note' => 'nullable|string',
            'progress' => 'required|string|in:pending,on progress,done', // Status pemesanan
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        // Simpan data pemesanan
        $order = Order::create($request->all());

        return response()->json([
            'status' => 'Berhasil',
            'message' => 'Pesanan berhasil dibuat!',
            'order' => $order
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Cari pesanan berdasarkan ID
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'Berhasil',
            'data' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Cari pesanan berdasarkan ID
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);
        }

        // Validasi inputan update
        $validator = Validator::make($request->all(), [
            'nama' => 'nullable|string|max:255',
            'alamat_lengkap' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'category' => 'nullable|string',
            'service' => 'nullable|string',
            'jumlah' => 'nullable|integer|min:1',
            'pickup_delivery' => 'nullable|string',
            'note' => 'nullable|string',
            'progress' => 'nullable|string|in:pending,on progress,done', // Update status
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        // Update data pesanan
        $order->update($request->all());

        return response()->json([
            'status' => 'Berhasil',
            'message' => 'Pesanan berhasil diperbarui!',
            'order' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari pesanan berdasarkan ID
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);
        }

        // Hapus pesanan
        $order->delete();

        return response()->json([
            'status' => 'Berhasil',
            'message' => 'Pesanan berhasil dihapus!'
        ]);
    }
}
