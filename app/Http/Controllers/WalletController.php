<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Wallet::where('user_id', $user->id);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
    
            $query->where(function ($q) use ($keyword) {
                $q->where('description', 'like', '%' . $keyword . '%')
                  ->orWhere('type', 'like', '%' . $keyword . '%')
                  ->orWhereHas('user', function ($q2) use ($keyword) {
                      $q2->where('name', 'like', '%' . $keyword . '%');
                  })
                  ->orWhereDate('created_at', 'like', '%' . $keyword . '%') // tambahkan ini
                  ->orWhereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') like ?", ["%$keyword%"]) // agar lebih fleksibel
                  ->orWhereRaw("DATE_FORMAT(created_at, '%Y-%m') like ?", ["%$keyword%"])   // buat yyyy-mm
                  ->orWhereRaw("DATE_FORMAT(created_at, '%Y') like ?", ["%$keyword%"]);     // buat tahun saja
            });            
        }
        $wallet = $query->latest()->get();
        // $wallet = Wallet::where('user_id', $user->id)->latest()->get();
        $account_number = auth()->user()->account_number;

        // Menghitung balance dengan benar
        $income = Wallet::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->sum('income');

        $outcome = Wallet::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->sum('outcome');

        $balance = $income - $outcome;

        return view('wallet', compact('wallet', 'balance', 'income', 'outcome', 'account_number'));
    }

    public function topUp(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        Wallet::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'type' => 'topup',
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Top-up request submitted, waiting for approval.');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        $user = Auth::user();

        // Menghitung balance dengan benar
        $income = Wallet::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->sum('income');

        $outcome = Wallet::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->sum('outcome');

        $balance = $income - $outcome;

        if ($request->amount > $balance) {
            return redirect()->back()->with('error', 'Insufficient balance.');
        }

        Wallet::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 'withdraw',
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Withdraw request submitted, waiting for approval.');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'account_number' => 'required|exists:users,account_number',
            'amount' => 'required|numeric|min:1000',
        ]);

        $sender = Auth::user();
        $receiver = User::where('account_number', $request->account_number)->first();

        if (!$receiver) {
            return redirect()->back()->with('error', 'Account number not found.');
        }

        // Menghitung balance dengan benar
        $income = Wallet::where('user_id', $sender->id)
            ->where('status', 'accepted')
            ->sum('income');

        $outcome = Wallet::where('user_id', $sender->id)
            ->where('status', 'accepted')
            ->sum('outcome');

        $balance = $income - $outcome;

        if ($request->amount > $balance) {
            return redirect()->back()->with('error', 'Insufficient balance.');
        }

        // Kurangi saldo pengirim
        Wallet::create([
            'user_id' => $sender->id,
            'amount' => $request->amount,
            'type' => 'transfer',
            'status' => 'accepted',
            'outcome' => $request->amount,
            'description' => 'Transfer to ' . $receiver->name,
        ]);

        // Tambahkan saldo ke penerima
        Wallet::create([
            'user_id' => $receiver->id,
            'amount' => $request->amount,
            'type' => 'receive',
            'status' => 'accepted',
            'income' => $request->amount,
            'description' => 'Received from ' . $sender->name,
        ]);

        return redirect()->back()->with('success', 'Transfer successful.');
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected,pending',
        ]);

        $wallet = Wallet::findOrFail($id);

        if ($request->status == 'accepted') {
            if ($wallet->type == 'topup') {
                $wallet->income += $wallet->amount;
            } elseif ($wallet->type == 'withdraw') {
                $wallet->outcome += $wallet->amount;
            }
        }

        $wallet->status = $request->status;
        $wallet->save();

        return redirect()->back()->with('success', 'Transaction status updated.');
    }
}
