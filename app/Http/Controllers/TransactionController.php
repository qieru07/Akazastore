<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::orderBy('id', 'desc')->get();
        return view('transactions.index', compact('transactions'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $transaction->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return back()->with('success', 'Data transaksi berhasil dihapus!');
    }

    public function clearCompleted()
    {
        Transaction::whereIn('status', ['Lunas', 'Batal'])->delete();
        return back()->with('success', 'Semua riwayat transaksi (Selesai/Batal) telah dibersihkan!');
    }
}