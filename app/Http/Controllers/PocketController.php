<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class PocketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'bank') {
            $wallet = Wallet::latest()->get();
        } else {
            // Jika user bukan admin, hanya tampilkan transaksi miliknya
            $wallet = Wallet::where('user_id', $user->id)->latest()->get();
        }

        return view('pocket',  compact('wallet'));
    }

    public function topUp(Request $request)
    {
        $request->validate([
            'account_number' => 'required|exists:users,account_number',
            'amount' => 'required|numeric|min:1000',
        ]);

        $user = User::where('account_number', $request->account_number)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Account number not found.');
        }

        // Hitung saldo terakhir
        // Menghitung balance dengan benar
        $income = Wallet::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->sum('income');

        $outcome = Wallet::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->sum('outcome');

        $balance = $income - $outcome;

        // Simpan transaksi dengan status accepted dan update income & balance
        Wallet::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 'topup',
            'status' => 'accepted',
            'income' => $request->amount, // Income bertambah
            'outcome' => 0
        ]);

        return redirect()->back()->with('success', 'Top-up successful.');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'account_number' => 'required|exists:users,account_number',
            'amount' => 'required|numeric|min:1000',
        ]);

        $user = User::where('account_number', $request->account_number)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Account number not found.');
        }

        // Menghitung balance dengan benar
        $income = Wallet::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->sum('income');

        $outcome = Wallet::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->sum('outcome');

        $balance = $income - $outcome;

        // Periksa apakah saldo mencukupi
        if ($request->amount > $balance) {
            return redirect()->back()->with('error', 'Insufficient balance.');
        }

        // Simpan transaksi dengan status accepted dan update outcome & balance
        Wallet::create([
            'user_id' => $user->id,
            'amount' => $request->amount, // Tetap positif, tidak negatif
            'type' => 'withdraw',
            'status' => 'accepted',
            'income' => 0,
            'outcome' => $request->amount // Outcome bertambah
        ]);

        return redirect()->back()->with('success', 'Withdraw successful.');
    }
}
