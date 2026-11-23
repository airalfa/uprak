<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    public function index()
    {
        $orders = [];

        if (Schema::hasTable('orders')) {
            $rows = DB::table('orders')->orderByDesc('id')->get();
            foreach ($rows as $r) {
                $orders[] = [
                    'id'            => $r->id,
                    'code'          => $r->code,
                    'service'       => $r->service,
                    'customer'      => $r->customer,
                    'phone'         => $r->phone ?? '-',
                    'items'         => $r->items,
                    'payment'       => $r->payment ?? 'QRIS',
                    'total'         => $r->total,
                    'status'        => $r->status ?? 'Menunggu',
                    'note'          => $r->note ?? '',
                    'cancel_reason' => $r->cancel_reason ?? '',
                ];
            }
        }

        return view('admin.pesanan', compact('orders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service'  => ['required', 'string', 'max:50'],
            'customer' => ['required', 'string', 'max:120'],
            'phone'    => ['nullable', 'string', 'max:40'],
            'items'    => ['required', 'string'],
            'payment'  => ['nullable', 'string', 'max:60'],
            'total'    => ['required', 'string', 'max:60'],
            'note'     => ['nullable', 'string'],
        ]);

        if (!Schema::hasTable('orders')) {
            return response()->json([
                'ok'      => false,
                'message' => 'Tabel orders belum dimigrasi.',
            ], 500);
        }

        $nextNumber = (DB::table('orders')->max('id') ?? 0) + 1;
        $code = '#KS' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);

        DB::table('orders')->insert([
            'code'       => $code,
            'service'    => $data['service'],
            'customer'   => $data['customer'],
            'phone'      => $data['phone'] ?? '-',
            'items'      => $data['items'],
            'payment'    => $data['payment'] ?? 'QRIS',
            'total'      => $data['total'],
            'status'     => 'Menunggu',
            'note'       => $data['note'] ?? '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'ok'      => true,
            'code'    => $code,
            'message' => 'Pesanan ' . $code . ' berhasil dikirim dan masuk ke antrean admin.',
        ]);
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status'        => ['required', 'string', 'max:40'],
            'cancel_reason' => ['nullable', 'string'],
        ]);

        if (Schema::hasTable('orders')) {
            $update = [
                'status'     => $data['status'],
                'updated_at' => now(),
            ];
            if (array_key_exists('cancel_reason', $data)) {
                $update['cancel_reason'] = $data['cancel_reason'];
            }
            DB::table('orders')->where('id', $id)->update($update);
        }

        return response()->json(['ok' => true]);
    }
}