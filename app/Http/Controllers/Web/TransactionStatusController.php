<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionStatusController extends Controller
{
    /**
     * Update status transaksi (via web session, khusus admin).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:baru,cuci,kering,setrika,selesai,diambil',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status berhasil diperbarui.',
            'data'    => $transaction,
        ]);
    }
}
